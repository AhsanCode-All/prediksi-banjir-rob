<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataHarian;
use Illuminate\Http\Request;

class DataHarianController extends Controller
{
    public function index(Request $request)
    {
        $query = DataHarian::with('kelurahan')
            ->orderByDesc('tanggal');

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter berdasarkan kelurahan
        if ($request->filled('kelurahan_id')) {
            $query->where('kelurahan_id', $request->kelurahan_id);
        }

        $dataHarian = $query->paginate(20)->withQueryString();

        $daftarKelurahan = \App\Models\Kelurahan::orderBy(
            'nama_kelurahan'
        )->get();

        return view('admin.data.index', compact(
            'dataHarian',
            'daftarKelurahan'
        ));
    }
}