<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\DataHarianImport;
use App\Models\Impor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImporController extends Controller
{
    public function index()
    {
        $riwayatImpor = Impor::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.impor.riwayat', compact('riwayatImpor'));
    }
    public function store(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');

        // Simpan file ke storage lokal
        $path = $file->store('impor', 'local');

        // Buat riwayat import
        $impor = Impor::create([
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'total_data' => 0,
            'data_berhasil' => 0,
            'data_gagal' => 0,
            'status' => 'diproses',
            'keterangan' => 'Proses import sedang berlangsung.',
            'user_id' => null,
        ]);

        $dataImport = new DataHarianImport($impor->id);

        try {
            DB::transaction(function () use ($dataImport, $path) {

                // Jalankan import Excel
                Excel::import($dataImport, $path, 'local');

                // Perbarui riwayat jika import berhasil
                $impor = Impor::findOrFail($dataImport->imporId);

                $impor->update([
                    'total_data' => $dataImport->totalData,
                    'data_berhasil' => $dataImport->dataBerhasil,
                    'data_gagal' => 0,
                    'status' => 'berhasil',
                    'keterangan' => 'Seluruh data berhasil diimpor.',
                ]);
            });

            return back()->with(
                'success',
                "Import berhasil! Total: {$dataImport->totalData}, berhasil: {$dataImport->dataBerhasil}."
            );

        } catch (Throwable $e) {

            // Jika transaksi gagal, catat kegagalannya
            $impor->update([
                'total_data' => $dataImport->totalData,
                'data_berhasil' => 0,
                'data_gagal' => $dataImport->totalData,
                'status' => 'gagal',
                'keterangan' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Import gagal dan perubahan database dibatalkan: '
                . $e->getMessage()
            );
        }
    }
}