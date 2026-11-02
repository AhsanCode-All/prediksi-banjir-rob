<?php

namespace App\Console\Commands;

use App\Models\DataHarian;
use App\Models\Kelurahan;
use App\Models\Prediksi;
use App\Models\StatusPrediksi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrediksiHistoris extends Command
{
    protected $signature = 'prediksi:historis
                            {--mulai= : Tanggal awal (YYYY-MM-DD)}
                            {--akhir= : Tanggal akhir (YYYY-MM-DD)}';

    protected $description = 'Menjalankan prediksi historis banjir rob untuk seluruh tanggal dataset';

    public function handle(): int
    {
        $query = DataHarian::query()
            ->select('tanggal')
            ->distinct()
            ->orderBy('tanggal');

        if ($this->option('mulai')) {
            $query->whereDate('tanggal', '>=', $this->option('mulai'));
        }

        if ($this->option('akhir')) {
            $query->whereDate('tanggal', '<=', $this->option('akhir'));
        }

        $tanggalList = $query->pluck('tanggal');

        if ($tanggalList->isEmpty()) {
            $this->warn('Tidak ada tanggal yang ditemukan.');
            return self::SUCCESS;
        }

        $kelurahan = Kelurahan::orderBy('nama_kelurahan')->get();

        if ($kelurahan->count() !== 31) {
            $this->error('Jumlah kelurahan harus tepat 31.');
            return self::FAILURE;
        }

        $this->info("Ditemukan {$tanggalList->count()} tanggal.");
        $this->info('Jumlah kelurahan: 31.');
        $this->newLine();

        $berhasil = 0;
        $dilewati = 0;
        $gagal = 0;

        foreach ($tanggalList as $tanggalRaw) {
            $tanggal = Carbon::parse($tanggalRaw)->toDateString();

            $statusLama = StatusPrediksi::whereDate('tanggal', $tanggal)->first();

            if ($statusLama && $statusLama->status === 'berhasil') {
                $this->line("[LEWATI] {$tanggal} - sudah berhasil.");
                $dilewati++;
                continue;
            }

            $this->info("[PROSES] {$tanggal}");

            $statusPrediksi = null;

            try {
                $dataHarian = DataHarian::with('kelurahan')
                    ->whereDate('tanggal', $tanggal)
                    ->get()
                    ->keyBy('kelurahan_id');

                if ($dataHarian->count() !== 31) {
                    throw new \RuntimeException(
                        "Data tidak lengkap: ditemukan {$dataHarian->count()} dari 31 kelurahan."
                    );
                }

                $statusPrediksi = StatusPrediksi::updateOrCreate(
                    ['tanggal' => $tanggal],
                    [
                        'status' => 'diproses',
                        'model_versi' => 'Random Forest Tuned',
                        'is_simulasi' => false,
                        'total_data' => 0,
                        'pesan' => null,
                        'mulai_diproses' => now(),
                        'selesai_diproses' => null,
                    ]
                );

                $hasil = [];

                foreach ($kelurahan as $wilayah) {
                    $data = $dataHarian->get($wilayah->id);

                    if (!$data) {
                        throw new \RuntimeException(
                            "Data {$wilayah->nama_kelurahan} tidak ditemukan."
                        );
                    }

                    $response = Http::timeout(30)->post(
                        rtrim(config('services.fastapi.url'), '/') . '/predict',
                        [
                            'elevasi' => (float) $wilayah->elevasi,
                            'curah_hujan' => (float) $data->curah_hujan,
                            'pasang_tertinggi' => (float) $data->pasang_tertinggi,
                            'gelombang_tertinggi' => (float) $data->gelombang_tertinggi,
                            'jarak_pantai' => (float) $wilayah->jarak_pantai,
                        ]
                    );

                    if (!$response->successful()) {
                        throw new \RuntimeException(
                            "API gagal untuk {$wilayah->nama_kelurahan}: "
                            . $response->body()
                        );
                    }

                    $hasilApi = $response->json();

                    if (
                        !isset($hasilApi['prediction']) ||
                        !isset($hasilApi['probability']) ||
                        !in_array($hasilApi['prediction'], [0, 1], true) ||
                        !is_numeric($hasilApi['probability']) ||
                        $hasilApi['probability'] < 0 ||
                        $hasilApi['probability'] > 1
                    ) {
                        throw new \RuntimeException(
                            "Format hasil API tidak valid untuk {$wilayah->nama_kelurahan}."
                        );
                    }

                    $hasil[] = [
                        'kelurahan_id' => $wilayah->id,
                        'status_prediksi_id' => $statusPrediksi->id,
                        'tanggal' => $tanggal,
                        'probabilitas' => $hasilApi['probability'],
                        'tingkat' => 'rendah',
                        'is_banjir' => $hasilApi['prediction'],
                    ];
                }

                DB::transaction(function () use ($hasil, $statusPrediksi, $tanggal) {
                    foreach ($hasil as $item) {
                        Prediksi::updateOrCreate(
                            [
                                'kelurahan_id' => $item['kelurahan_id'],
                                'tanggal' => $tanggal,
                            ],
                            $item
                        );
                    }

                    $statusPrediksi->update([
                        'status' => 'berhasil',
                        'total_data' => count($hasil),
                        'pesan' => 'Prediksi berhasil untuk seluruh kelurahan.',
                        'selesai_diproses' => now(),
                    ]);
                });

                $this->info("[BERHASIL] {$tanggal} - 31 kelurahan.");
                $berhasil++;

            } catch (\Throwable $error) {
                Log::error('Prediksi historis gagal', [
                    'tanggal' => $tanggal,
                    'error' => $error->getMessage(),
                ]);

                if ($statusPrediksi) {
                    $statusPrediksi->update([
                        'status' => 'gagal',
                        'pesan' => $error->getMessage(),
                        'selesai_diproses' => now(),
                    ]);
                }

                $this->error("[GAGAL] {$tanggal} - {$error->getMessage()}");
                $gagal++;
            }
        }

        $this->newLine();
        $this->info('===== RINGKASAN PREDIKSI HISTORIS =====');
        $this->info("Berhasil : {$berhasil}");
        $this->info("Dilewati : {$dilewati}");
        $this->info("Gagal    : {$gagal}");

        return $gagal > 0 ? self::FAILURE : self::SUCCESS;
    }
}