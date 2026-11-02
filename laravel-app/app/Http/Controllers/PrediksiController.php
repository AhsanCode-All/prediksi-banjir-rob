<?php

namespace App\Http\Controllers;

use App\Models\DataHarian;
use App\Models\Kelurahan;
use App\Models\Prediksi;
use App\Models\StatusPrediksi;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrediksiController extends Controller
{
    public function index()
    {
        $riwayat = StatusPrediksi::withCount('prediksi')
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('admin.prediksi.index', compact('riwayat'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();

        $kelurahan = Kelurahan::orderBy('nama_kelurahan')->get();

        if ($kelurahan->count() !== 31) {
            return back()->with(
                'error',
                'Jumlah kelurahan harus tepat 31.'
            );
        }

        $dataHarian = DataHarian::with('kelurahan')
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('kelurahan_id');

        if ($dataHarian->count() !== 31) {
            return back()->with(
                'error',
                'Data tanggal tersebut belum lengkap untuk seluruh kelurahan.'
            );
        }

        $statusPrediksi = null;

        try {
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

                $response = Http::timeout(30)
                    ->post(
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

            DB::transaction(function () use (
                $hasil,
                $statusPrediksi,
                $request,
                $tanggal
            ) {
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

                LogAktivitas::create([
                    'user_id' => $request->user()?->id,
                    'aktivitas' => 'Prediksi Banjir Rob',
                    'deskripsi' => "Prediksi tanggal {$tanggal} berhasil untuk "
                        . count($hasil) . ' kelurahan.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            });

            return redirect()
                ->route('admin.prediksi.index')
                ->with('success', 'Prediksi berhasil untuk 31 kelurahan.');

        } catch (\Throwable $error) {
            Log::error('Proses prediksi gagal', [
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

            return back()->with(
                'error',
                'Prediksi gagal: ' . $error->getMessage()
            );
        }
    }
}