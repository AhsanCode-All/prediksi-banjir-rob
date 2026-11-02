<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelurahan::query();

        if ($request->filled('cari')) {
            $query->where(
                'nama_kelurahan',
                'like',
                '%' . $request->cari . '%'
            );
        }

        $kelurahan = $query
            ->orderBy('nama_kelurahan')
            ->paginate(10)
            ->withQueryString();

        $totalKelurahan = Kelurahan::count();

        return view('admin.kelurahan.index', compact(
            'kelurahan',
            'totalKelurahan'
        ));
    }

    public function show($id)
    {
        $kelurahan = Kelurahan::findOrFail($id);

        $dataHarian = $kelurahan->dataHarian()
            ->orderByDesc('tanggal')
            ->paginate(15);

        $totalData = $kelurahan->dataHarian()->count();

        $tanggalAwal = $kelurahan->dataHarian()->min('tanggal');
        $tanggalAkhir = $kelurahan->dataHarian()->max('tanggal');

        return view('admin.kelurahan.show', compact(
            'kelurahan',
            'dataHarian',
            'totalData',
            'tanggalAwal',
            'tanggalAkhir'
        ));
    }
}