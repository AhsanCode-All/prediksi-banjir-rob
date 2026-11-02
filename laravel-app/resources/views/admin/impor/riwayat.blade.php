@extends('layouts.admin')

@section('title', 'Riwayat Impor')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-white">Riwayat Impor</h2>
    <p class="mt-2 text-sm text-slate-400">
        Lihat catatan seluruh proses pengunggahan dan impor dataset.
    </p>
</div>

<!-- Ringkasan -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
        <p class="text-sm text-slate-400">Total Proses Impor</p>
        <h3 class="mt-2 text-2xl font-bold text-white">
            {{ $riwayatImpor->total() }}
        </h3>
        <p class="mt-1 text-xs text-slate-500">Seluruh riwayat tersimpan</p>
    </div>

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
        <p class="text-sm text-slate-400">Data Berhasil</p>
        <h3 class="mt-2 text-2xl font-bold text-emerald-400">
            {{ $riwayatImpor->sum('data_berhasil') }}
        </h3>
        <p class="mt-1 text-xs text-slate-500">Pada halaman ini</p>
    </div>

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-5">
        <p class="text-sm text-slate-400">Data Gagal</p>
        <h3 class="mt-2 text-2xl font-bold text-red-400">
            {{ $riwayatImpor->sum('data_gagal') }}
        </h3>
        <p class="mt-1 text-xs text-slate-500">Pada halaman ini</p>
    </div>

</div>

<!-- Tabel Riwayat -->
<div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">

    <div class="flex flex-col justify-between gap-3 border-b border-slate-800 px-5 py-4 sm:flex-row sm:items-center">
        <div>
            <h3 class="font-semibold text-white">Daftar Riwayat Impor</h3>
            <p class="mt-1 text-xs text-slate-400">
                Menampilkan {{ $riwayatImpor->count() }} dari {{ $riwayatImpor->total() }} proses impor.
            </p>
        </div>

        <a href="{{ route('admin.impor.index') }}"
           class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-500">
            + Upload Data Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap text-left text-sm">

            <thead class="bg-slate-950 text-xs uppercase text-slate-400">
                <tr>
                    <th class="px-5 py-4">No.</th>
                    <th class="px-5 py-4">Nama File</th>
                    <th class="px-5 py-4">Waktu Impor</th>
                    <th class="px-5 py-4">Admin</th>
                    <th class="px-5 py-4">Total Data</th>
                    <th class="px-5 py-4">Berhasil</th>
                    <th class="px-5 py-4">Gagal</th>
                    <th class="px-5 py-4">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-800">

                @forelse ($riwayatImpor as $index => $impor)

                    <tr class="transition hover:bg-slate-800/50">

                        <td class="px-5 py-4 text-slate-400">
                            {{ $riwayatImpor->firstItem() + $index }}
                        </td>

                        <td class="px-5 py-4 font-medium text-white">
                            {{ $impor->nama_file }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ $impor->created_at?->format('d-m-Y H:i') ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ $impor->user->name ?? 'Tidak diketahui' }}
                        </td>

                        <td class="px-5 py-4 text-slate-300">
                            {{ number_format($impor->total_data) }}
                        </td>

                        <td class="px-5 py-4 font-medium text-emerald-400">
                            {{ number_format($impor->data_berhasil) }}
                        </td>

                        <td class="px-5 py-4 font-medium text-red-400">
                            {{ number_format($impor->data_gagal) }}
                        </td>

                        <td class="px-5 py-4">
                            @if ($impor->status === 'berhasil')
                                <span class="rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400">
                                    Berhasil
                                </span>
                            @elseif ($impor->status === 'gagal')
                                <span class="rounded-full border border-red-500/30 bg-red-500/10 px-3 py-1 text-xs font-medium text-red-400">
                                    Gagal
                                </span>
                            @else
                                <span class="rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-400">
                                    Diproses
                                </span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center">
                            <p class="font-medium text-slate-300">
                                Belum ada riwayat impor.
                            </p>
                            <p class="mt-1 text-sm text-slate-500">
                                Riwayat akan muncul setelah proses impor dilakukan.
                            </p>
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>

    <div class="border-t border-slate-800 px-5 py-4">
        {{ $riwayatImpor->links() }}
    </div>

</div>

@endsection