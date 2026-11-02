<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DataHarian;
use App\Models\Impor;
use App\Models\Kelurahan;
use App\Models\Prediksi;
use App\Models\StatusPrediksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik data utama
        $totalData = DataHarian::count();
        $totalKelurahan = Kelurahan::count();
        $totalImpor = Impor::count();

        $tanggalAwal = DataHarian::min('tanggal');
        $tanggalAkhir = DataHarian::max('tanggal');

        $imporTerakhir = Impor::with('user')
            ->latest()
            ->first();

        // Statistik prediksi
        $totalTanggalPrediksi = StatusPrediksi::where(
            'status',
            'berhasil'
        )->count();

        $totalPrediksi = Prediksi::count();

        $totalIndikasiBanjir = Prediksi::where(
            'is_banjir',
            true
        )->count();

        $totalTidakBanjir = Prediksi::where(
            'is_banjir',
            false
        )->count();

        $totalPrediksiGagal = StatusPrediksi::where(
            'status',
            'gagal'
        )->count();

        // Prediksi terbaru
        $prediksiTerakhir = StatusPrediksi::where(
            'status',
            'berhasil'
        )
            ->orderByDesc('tanggal')
            ->first();

        // Aktivitas terbaru
        $aktivitasTerakhir = DB::table('log_aktivitas')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalData',
            'totalKelurahan',
            'totalImpor',
            'tanggalAwal',
            'tanggalAkhir',
            'imporTerakhir',
            'totalTanggalPrediksi',
            'totalPrediksi',
            'totalIndikasiBanjir',
            'totalTidakBanjir',
            'totalPrediksiGagal',
            'prediksiTerakhir',
            'aktivitasTerakhir'
        ));
    }

    public function grafikHarian(): JsonResponse
    {
        $data = DB::table('data_harian')
            ->select(
                'tanggal',
                DB::raw('AVG(curah_hujan) as rata_curah_hujan'),
                DB::raw('AVG(pasang_tertinggi) as rata_pasang'),
                DB::raw('AVG(gelombang_tertinggi) as rata_gelombang')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return response()->json($data);
    }
}