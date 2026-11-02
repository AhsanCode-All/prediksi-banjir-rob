@extends('layouts.admin')

@section('title', 'Prediksi Banjir Rob')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white">
            Prediksi Banjir Rob
        </h1>
        <p class="mt-2 text-sm text-slate-400">
            Jalankan prediksi indikasi banjir rob menggunakan model
            Random Forest Tuned untuk seluruh kelurahan.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Prediksi --}}
    <div class="rounded-2xl border border-slate-700 bg-slate-800 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-white">
                Jalankan Prediksi
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Pilih tanggal yang memiliki data lengkap untuk 31 kelurahan.
            </p>
        </div>

        <form
            action="{{ route('admin.prediksi.proses') }}"
            method="POST"
            class="space-y-5"
            onsubmit="return confirm('Apakah kamu yakin ingin menjalankan prediksi untuk tanggal ini?')"
        >
            @csrf

            <div>
                <label for="tanggal" class="mb-2 block text-sm font-medium text-slate-300">
                    Tanggal Prediksi
                </label>

                <input
                    type="date"
                    name="tanggal"
                    id="tanggal"
                    value="{{ old('tanggal') }}"
                    required
                    class="w-full rounded-xl border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                >

                <p class="mt-2 text-xs text-slate-500">
                    Prediksi menggunakan data elevasi, curah hujan,
                    pasang tertinggi, gelombang tertinggi, dan jarak pantai.
                </p>
            </div>

            <div class="rounded-xl border border-blue-500/20 bg-blue-500/10 p-4">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-blue-300">
                            Informasi proses
                        </p>
                        <p class="mt-1 text-xs leading-5 text-slate-400">
                            Sistem akan memproses 31 kelurahan secara
                            otomatis. Hasil prediksi disimpan setelah
                            seluruh permintaan ke model berhasil.
                        </p>
                    </div>
                </div>
            </div>

            <button
                type="submit"
                class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500"
            >
                Jalankan Prediksi
            </button>
        </form>
    </div>

    {{-- Riwayat Prediksi --}}
    <div class="overflow-hidden rounded-2xl border border-slate-700 bg-slate-800">

        <div class="border-b border-slate-700 p-6">
            <h2 class="text-lg font-semibold text-white">
                Riwayat Prediksi
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Daftar proses prediksi yang pernah dijalankan.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900/70 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-6 py-4">No.</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Model</th>
                        <th class="px-6 py-4">Total Data</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu Proses</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-700 text-slate-300">
                    @forelse ($riwayat as $item)
                        <tr class="transition hover:bg-slate-700/30">
                            <td class="px-6 py-4">
                                {{ $riwayat->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 font-medium text-white">
                                {{ $item->tanggal->format('d-m-Y') }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->model_versi ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->total_data }} / 31
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $statusClass = match ($item->status) {
                                        'berhasil' => 'bg-emerald-500/10 text-emerald-400',
                                        'gagal' => 'bg-red-500/10 text-red-400',
                                        'diproses' => 'bg-amber-500/10 text-amber-400',
                                        default => 'bg-slate-500/10 text-slate-400',
                                    };
                                @endphp

                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-slate-400">
                                {{ $item->selesai_diproses?->format('d-m-Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Belum ada riwayat prediksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-700 p-5">
            {{ $riwayat->links() }}
        </div>
    </div>

</div>
@endsection