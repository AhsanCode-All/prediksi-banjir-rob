@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('admin.kelurahan.index') }}"
               class="text-sm text-blue-400 hover:text-blue-300">
                ← Kembali ke Master Kelurahan
            </a>

            <h1 class="mt-3 text-2xl font-bold text-white">
                {{ $kelurahan->nama_kelurahan }}
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Detail wilayah dan riwayat data harian.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <p class="text-sm text-slate-400">Kode Wilayah</p>
            <p class="mt-2 font-semibold text-blue-400">
                {{ $kelurahan->kode_wilayah }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <p class="text-sm text-slate-400">Elevasi</p>
            <p class="mt-2 text-xl font-bold text-white">
                {{ number_format($kelurahan->elevasi, 2, ',', '.') }} m
            </p>
        </div>

        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <p class="text-sm text-slate-400">Jarak Pantai</p>
            <p class="mt-2 text-xl font-bold text-white">
                {{ number_format($kelurahan->jarak_pantai, 2, ',', '.') }} km
            </p>
        </div>

        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <p class="text-sm text-slate-400">Total Data Harian</p>
            <p class="mt-2 text-xl font-bold text-white">
                {{ number_format($totalData) }}
            </p>
        </div>

    </div>

    <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
        <h2 class="text-lg font-semibold text-white">Periode Data</h2>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <p class="text-sm text-slate-400">Tanggal Awal</p>
                <p class="mt-1 font-medium text-white">
                    {{ $tanggalAwal ? \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') : '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-400">Tanggal Akhir</p>
                <p class="mt-1 font-medium text-white">
                    {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
        <h2 class="mb-5 text-lg font-semibold text-white">
            Riwayat Data Harian
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-700 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Curah Hujan</th>
                        <th class="px-4 py-3">Pasang Tertinggi</th>
                        <th class="px-4 py-3">Gelombang Tertinggi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @forelse ($dataHarian as $data)
                        <tr class="hover:bg-slate-800/60">
                            <td class="px-4 py-3">
                                {{ $dataHarian->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3 text-white">
                                {{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format($data->curah_hujan, 2, ',', '.') }} mm
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format($data->pasang_tertinggi, 2, ',', '.') }} m
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format($data->gelombang_tertinggi, 2, ',', '.') }} m
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                Belum ada data harian untuk kelurahan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $dataHarian->links() }}
        </div>
    </div>

</div>
@endsection