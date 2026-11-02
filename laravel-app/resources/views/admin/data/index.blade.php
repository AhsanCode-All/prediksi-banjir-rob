@extends('layouts.admin')

@section('title', 'Data Harian')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-white">Data Harian</h2>
    <p class="mt-2 text-sm text-slate-400">
        Kelola dan lihat data lingkungan harian untuk setiap kelurahan.
    </p>
</div>

<!-- Ringkasan -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
        <p class="text-sm text-slate-400">Total Data</p>
        <h3 class="mt-2 text-2xl font-bold text-white">
            {{ $dataHarian->total() }}
        </h3>
        <p class="mt-1 text-xs text-slate-500">Data sesuai filter</p>
    </div>

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
        <p class="text-sm text-slate-400">Jumlah Kelurahan</p>
        <h3 class="mt-2 text-2xl font-bold text-white">
            {{ $daftarKelurahan->count() }}
        </h3>
        <p class="mt-1 text-xs text-slate-500">Kelurahan terdaftar</p>
    </div>

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
        <p class="text-sm text-slate-400">Periode Data</p>
        <h3 class="mt-2 text-2xl font-bold text-white">2026</h3>
        <p class="mt-1 text-xs text-slate-500">Periode implementasi</p>
    </div>
</div>

<!-- Filter -->
<div class="mb-6 rounded-xl border border-slate-800 bg-slate-900 p-5">
    <h3 class="mb-4 font-semibold text-white">Filter Data</h3>

    <form action="{{ route('admin.data.index') }}" method="GET"
          class="grid grid-cols-1 items-end gap-4 md:grid-cols-3">

        <div>
            <label class="mb-2 block text-sm text-slate-400">Tanggal</label>
            <input type="date" name="tanggal"
                   value="{{ request('tanggal') }}"
                   class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="mb-2 block text-sm text-slate-400">Kelurahan</label>
            <select name="kelurahan_id"
                    class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none">
                <option value="">Semua Kelurahan</option>

                @foreach ($daftarKelurahan as $kelurahan)
                    <option value="{{ $kelurahan->id }}"
                        {{ request('kelurahan_id') == $kelurahan->id ? 'selected' : '' }}>
                        {{ $kelurahan->nama_kelurahan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-500">
                Terapkan Filter
            </button>

            <a href="{{ route('admin.data.index') }}"
               class="rounded-lg border border-slate-700 px-4 py-2 text-sm text-slate-300 transition hover:bg-slate-800">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Tabel Data -->
<div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">

    <div class="flex flex-col justify-between gap-2 border-b border-slate-800 px-5 py-4 sm:flex-row sm:items-center">
        <div>
            <h3 class="font-semibold text-white">Daftar Data Lingkungan</h3>
            <p class="mt-1 text-xs text-slate-400">
                Menampilkan {{ $dataHarian->count() }} dari {{ $dataHarian->total() }} data.
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap text-left text-sm">
            <thead class="bg-slate-950 text-xs uppercase text-slate-400">
                <tr>
                    <th class="px-5 py-4">Tanggal</th>
                    <th class="px-5 py-4">Kelurahan</th>
                    <th class="px-5 py-4">Elevasi (m)</th>
                    <th class="px-5 py-4">Curah Hujan (mm)</th>
                    <th class="px-5 py-4">Pasang (m)</th>
                    <th class="px-5 py-4">Gelombang (m)</th>
                    <th class="px-5 py-4">Jarak Pantai (km)</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-800">
                @forelse ($dataHarian as $data)
                    <tr class="transition hover:bg-slate-800/50">
                        <td class="px-5 py-4 text-slate-300">
                            {{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}
                        </td>

                        <td class="px-5 py-4 font-medium text-white">
                            {{ $data->kelurahan->nama_kelurahan ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ number_format($data->kelurahan->elevasi ?? 0, 2, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ number_format($data->curah_hujan, 2, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ number_format($data->pasang_tertinggi, 2, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ number_format($data->gelombang_tertinggi, 2, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ number_format($data->kelurahan->jarak_pantai ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                            Tidak ada data yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="border-t border-slate-800 px-5 py-4">
        {{ $dataHarian->links() }}
    </div>

</div>

@endsection