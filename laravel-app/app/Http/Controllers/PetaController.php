<?php

namespace App\Http\Controllers;

use App\Models\StatusPrediksi;
use App\Models\Prediksi;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        $tanggalPrediksi = StatusPrediksi::where('status', 'berhasil')
            ->orderByDesc('tanggal')
            ->pluck('tanggal')
            ->map(function ($tanggal) {
                return \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
            });

        return view('peta.index', compact('tanggalPrediksi'));
    }

    public function data(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
        ]);

        $tanggal = $request->tanggal;

        $statusPrediksi = StatusPrediksi::whereDate('tanggal', $tanggal)
            ->where('status', 'berhasil')
            ->first();

        if (!$statusPrediksi) {
            return response()->json([
                'tersedia' => false,
                'pesan' => 'Belum ada hasil prediksi untuk tanggal ini.',
                'data' => [],
            ]);
        }

        $prediksi = Prediksi::with('kelurahan')
            ->where('status_prediksi_id', $statusPrediksi->id)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_kelurahan' => strtolower(trim($item->kelurahan->nama_kelurahan)),
                    'kelurahan' => $item->kelurahan->nama_kelurahan,
                    'kecamatan' => $item->kelurahan->kecamatan,
                    'is_banjir' => (int) $item->is_banjir,
                    'probabilitas' => (float) $item->probabilitas,
                ];
            });

        $totalBanjir = $prediksi->where('is_banjir', 1)->count();
        $totalTidakBanjir = $prediksi->where('is_banjir', 0)->count();

        return response()->json([
            'tersedia' => true,
            'tanggal' => $tanggal,
            'model_versi' => $statusPrediksi->model_versi,
            'total_data' => $prediksi->count(),
            'total_banjir' => $totalBanjir,
            'total_tidak_banjir' => $totalTidakBanjir,
            'data' => $prediksi,
        ]);
    }

    public function admin()
    {
        $tanggalPrediksi = StatusPrediksi::where('status', 'berhasil')
            ->orderByDesc('tanggal')
            ->pluck('tanggal')
            ->map(function ($tanggal) {
                return \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
            });

        return view('peta.admin', compact('tanggalPrediksi'));
    }
}