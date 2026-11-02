<?php

namespace App\Imports;

use App\Models\Kelurahan;
use App\Models\DataHarian;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use RuntimeException;

class DataHarianImport implements ToCollection, WithStartRow
{
    public int $imporId;

    public int $totalData = 0;
    public int $dataBerhasil = 0;
    public int $dataGagal = 0;

    public function __construct(int $imporId)
    {
        $this->imporId = $imporId;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows): void
    {
        // Abaikan baris yang benar-benar kosong
        $rows = $rows->filter(function ($row) {
            return $row->filter(
                fn ($value) => $value !== null && $value !== ''
            )->isNotEmpty();
        });

        $this->totalData = $rows->count();

        // Validasi jumlah baris dataset implementasi
        if ($this->totalData !== 8463) {
            throw new RuntimeException(
                "Jumlah data tidak sesuai. Ditemukan {$this->totalData} baris, seharusnya 8463."
            );
        }

        // Validasi jumlah kelurahan
        $namaKelurahan = $rows
            ->map(fn ($row) => trim((string) $row[1]))
            ->unique()
            ->values();

        if ($namaKelurahan->count() !== 31) {
            throw new RuntimeException(
                "Dataset harus memiliki 31 kelurahan. Ditemukan {$namaKelurahan->count()}."
            );
        }

        // Ambil master kelurahan
        $masterKelurahan = Kelurahan::all()->keyBy('nama_kelurahan');

        foreach ($rows as $index => $row) {
            $nomorBaris = $index + 2;

            // Pastikan tujuh kolom tersedia
            if ($row->count() < 7) {
                throw new RuntimeException(
                    "Kolom tidak lengkap pada baris Excel {$nomorBaris}."
                );
            }

            $nama = trim((string) $row[1]);

            if (!$masterKelurahan->has($nama)) {
                throw new RuntimeException(
                    "Kelurahan '{$nama}' tidak ditemukan pada baris {$nomorBaris}."
                );
            }

            // Validasi tanggal
            try {
                if ($row[0] instanceof \DateTimeInterface) {
                    $tanggal = Carbon::instance($row[0])->format('Y-m-d');
                } elseif (is_numeric($row[0])) {
                    $tanggal = Carbon::instance(
                        Date::excelToDateTimeObject($row[0])
                    )->format('Y-m-d');
                } else {
                    $tanggal = Carbon::parse($row[0])->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                throw new RuntimeException(
                    "Tanggal tidak valid pada baris {$nomorBaris}."
                );
            }

            // Validasi nilai numerik
            foreach ([2, 3, 4, 5, 6] as $kolom) {
                if (!is_numeric($row[$kolom])) {
                    throw new RuntimeException(
                        "Nilai kolom numerik tidak valid pada baris {$nomorBaris}."
                    );
                }
            }

            $kelurahan = $masterKelurahan->get($nama);

            // Perbarui data wilayah
            $kelurahan->update([
                'elevasi' => $row[2],
                'jarak_pantai' => $row[6],
            ]);

            // Simpan data harian
            DataHarian::updateOrCreate(
                [
                    'kelurahan_id' => $kelurahan->id,
                    'tanggal' => $tanggal,
                ],
                [
                    'curah_hujan' => $row[3],
                    'pasang_tertinggi' => $row[4],
                    'gelombang_tertinggi' => $row[5],
                    'impor_id' => $this->imporId,
                ]
            );

            $this->dataBerhasil++;
        }
    }
}