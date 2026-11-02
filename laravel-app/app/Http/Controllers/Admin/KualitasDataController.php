<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataHarian;
use App\Models\Kelurahan;
use Illuminate\Support\Facades\DB;

class KualitasDataController extends Controller
{
    public function index()
    {
        // Informasi dasar dataset
        $totalData = DataHarian::count();
        $totalKelurahan = Kelurahan::count();

        $tanggalAwal = DataHarian::min('tanggal');
        $tanggalAkhir = DataHarian::max('tanggal');

        $totalTanggal = DataHarian::distinct('tanggal')->count('tanggal');

        // Target jumlah data berdasarkan jumlah kelurahan dan tanggal
        $targetData = $totalKelurahan * $totalTanggal;

        // Pemeriksaan kelengkapan
        $persentaseKelengkapan = $targetData > 0
            ? round(($totalData / $targetData) * 100, 2)
            : 0;

        // Pemeriksaan nilai kosong
        $dataKosong = DataHarian::whereNull('curah_hujan')
            ->orWhereNull('pasang_tertinggi')
            ->orWhereNull('gelombang_tertinggi')
            ->count();

        $masterKosong = Kelurahan::whereNull('elevasi')
            ->orWhereNull('jarak_pantai')
            ->count();

        // Pemeriksaan duplikasi
        $totalDuplikat = DB::table('data_harian')
            ->select('kelurahan_id', 'tanggal')
            ->groupBy('kelurahan_id', 'tanggal')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        // Distribusi data setiap kelurahan
        $distribusiKelurahan = Kelurahan::withCount('dataHarian')
            ->orderBy('nama_kelurahan')
            ->get();

        // Statistik variabel lingkungan
        $statistik = DB::table('data_harian')
            ->selectRaw('
                MIN(curah_hujan) as min_hujan,
                MAX(curah_hujan) as max_hujan,
                AVG(curah_hujan) as avg_hujan,
                MIN(pasang_tertinggi) as min_pasang,
                MAX(pasang_tertinggi) as max_pasang,
                AVG(pasang_tertinggi) as avg_pasang,
                MIN(gelombang_tertinggi) as min_gelombang,
                MAX(gelombang_tertinggi) as max_gelombang,
                AVG(gelombang_tertinggi) as avg_gelombang
            ')
            ->first();

        return view('admin.kualitas-data.index', compact(
            'totalData',
            'totalKelurahan',
            'totalTanggal',
            'targetData',
            'persentaseKelengkapan',
            'dataKosong',
            'masterKosong',
            'totalDuplikat',
            'distribusiKelurahan',
            'tanggalAwal',
            'tanggalAkhir',
            'statistik'
        ));
    }
}