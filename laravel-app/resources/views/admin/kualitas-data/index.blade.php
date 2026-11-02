@extends('layouts.admin')

@section('title', 'Kualitas Data')

@section('content')

<div class="space-y-8">

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-white">Pemeriksaan Kualitas Data</h2>
        <p class="mt-2 text-sm text-slate-400">
            Evaluasi kelengkapan dan konsistensi dataset implementasi.
        </p>
    </div>

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Total Data</p>
            <h3 class="mt-3 text-3xl font-bold text-white">
                {{ number_format($totalData, 0, ',', '.') }}
            </h3>
            <p class="mt-2 text-xs text-slate-500">
                Data harian tersimpan
            </p>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Total Kelurahan</p>
            <h3 class="mt-3 text-3xl font-bold text-blue-400">
                {{ number_format($totalKelurahan) }}
            </h3>
            <p class="mt-2 text-xs text-slate-500">
                Wilayah terdaftar
            </p>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Total Tanggal</p>
            <h3 class="mt-3 text-3xl font-bold text-violet-400">
                {{ number_format($totalTanggal) }}
            </h3>
            <p class="mt-2 text-xs text-slate-500">
                Hari dalam dataset
            </p>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Kelengkapan Data</p>
            <h3 class="mt-3 text-3xl font-bold {{ $persentaseKelengkapan == 100 ? 'text-emerald-400' : 'text-amber-400' }}">
                {{ number_format($persentaseKelengkapan, 2, ',', '.') }}%
            </h3>
            <p class="mt-2 text-xs text-slate-500">
                Berdasarkan kombinasi kelurahan dan tanggal
            </p>
        </div>

    </div>

    <!-- Pemeriksaan -->
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <h3 class="text-lg font-semibold text-white">
            Hasil Pemeriksaan
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Status validasi dasar dataset.
        </p>

        <div class="mt-6 space-y-4">

            <div class="flex items-center justify-between rounded-lg bg-slate-950 p-4">
                <div>
                    <p class="font-medium text-white">Nilai kosong data harian</p>
                    <p class="mt-1 text-xs text-slate-400">
                        Curah hujan, pasang, dan gelombang.
                    </p>
                </div>

                <span class="font-semibold {{ $dataKosong == 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ number_format($dataKosong) }} baris
                </span>
            </div>

            <div class="flex items-center justify-between rounded-lg bg-slate-950 p-4">
                <div>
                    <p class="font-medium text-white">Nilai kosong data master</p>
                    <p class="mt-1 text-xs text-slate-400">
                        Elevasi dan jarak pantai.
                    </p>
                </div>

                <span class="font-semibold {{ $masterKosong == 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ number_format($masterKosong) }} kelurahan
                </span>
            </div>

            <div class="flex items-center justify-between rounded-lg bg-slate-950 p-4">
                <div>
                    <p class="font-medium text-white">Kombinasi duplikat</p>
                    <p class="mt-1 text-xs text-slate-400">
                        Kelurahan dan tanggal yang tercatat lebih dari sekali.
                    </p>
                </div>

                <span class="font-semibold {{ $totalDuplikat == 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ number_format($totalDuplikat) }} kombinasi
                </span>
            </div>

        </div>

    </div>

    <!-- Statistik Variabel -->
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <h3 class="text-lg font-semibold text-white">
            Statistik Variabel Lingkungan
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Ringkasan nilai minimum, maksimum, dan rata-rata.
        </p>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-700 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Variabel</th>
                        <th class="px-4 py-3">Minimum</th>
                        <th class="px-4 py-3">Maksimum</th>
                        <th class="px-4 py-3">Rata-rata</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800 text-slate-300">

                    <tr>
                        <td class="px-4 py-4 font-medium text-white">
                            Curah Hujan (mm)
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->min_hujan ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->max_hujan ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->avg_hujan ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4 font-medium text-white">
                            Pasang Tertinggi (m)
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->min_pasang ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->max_pasang ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->avg_pasang ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4 font-medium text-white">
                            Gelombang Tertinggi (m)
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->min_gelombang ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->max_gelombang ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-4">
                            {{ number_format($statistik->avg_gelombang ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

    <!-- Distribusi Kelurahan -->
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <h3 class="text-lg font-semibold text-white">
            Distribusi Data per Kelurahan
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Perbandingan jumlah data yang tersimpan pada setiap wilayah.
        </p>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="border-b border-slate-700 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Kelurahan</th>
                        <th class="px-4 py-3">Jumlah Data</th>
                        <th class="px-4 py-3">Target</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800 text-slate-300">

                    @foreach ($distribusiKelurahan as $index => $item)

                        <tr class="hover:bg-slate-800/50">
                            <td class="px-4 py-4">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-4 py-4 font-medium text-white">
                                {{ $item->nama_kelurahan }}
                            </td>

                            <td class="px-4 py-4">
                                {{ number_format($item->data_harian_count) }}
                            </td>

                            <td class="px-4 py-4">
                                {{ number_format($totalTanggal) }}
                            </td>

                            <td class="px-4 py-4">
                                @if ($item->data_harian_count == $totalTanggal)
                                    <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400">
                                        Lengkap
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-400">
                                        Perlu diperiksa
                                    </span>
                                @endif
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

    <!-- Periode Dataset -->
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <h3 class="text-lg font-semibold text-white">
            Periode Dataset
        </h3>

        <div class="mt-5 grid grid-cols-1 gap-6 sm:grid-cols-2">

            <div>
                <p class="text-sm text-slate-400">Tanggal Awal</p>
                <p class="mt-2 font-semibold text-white">
                    {{ $tanggalAwal ? \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') : '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-400">Tanggal Akhir</p>
                <p class="mt-2 font-semibold text-white">
                    {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') : '-' }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection