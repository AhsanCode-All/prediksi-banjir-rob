@extends('layouts.admin')

@section('title', 'Upload Data')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-white">Upload Data Harian</h2>
    <p class="mt-2 text-sm text-slate-400">
        Impor data lingkungan harian untuk kebutuhan prediksi banjir rob.
    </p>
</div>

<!-- Informasi -->
<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">

    <div class="rounded-xl border border-blue-500/20 bg-blue-500/10 p-5">
        <div class="flex items-start gap-3">
            <div class="rounded-lg bg-blue-500/20 p-2 text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M12 18a6 6 0 100-12 6 6 0 000 12z"/>
                </svg>
            </div>

            <div>
                <h3 class="font-semibold text-blue-300">Informasi Import</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-300">
                    Gunakan file Excel dataset implementasi yang berisi data
                    lingkungan harian dari seluruh kelurahan.
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-amber-500/20 bg-amber-500/10 p-5">
        <div class="flex items-start gap-3">
            <div class="rounded-lg bg-amber-500/20 p-2 text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                </svg>
            </div>

            <div>
                <h3 class="font-semibold text-amber-300">Perhatian</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-300">
                    Pastikan struktur kolom dan format data sesuai dengan
                    template dataset implementasi sebelum melakukan import.
                </p>
            </div>
        </div>
    </div>

</div>

<!-- Pesan Error Validasi -->
@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 p-5">
        <h3 class="mb-2 font-semibold text-red-400">Import gagal divalidasi</h3>

        @foreach ($errors->all() as $error)
            <p class="text-sm text-red-300">{{ $error }}</p>
        @endforeach
    </div>
@endif

<!-- Form Upload -->
<div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">

    <div class="border-b border-slate-800 px-6 py-5">
        <h3 class="font-semibold text-white">Pilih File Dataset</h3>
        <p class="mt-1 text-sm text-slate-400">
            Unggah file Excel untuk memasukkan data ke dalam sistem.
        </p>
    </div>

    <form action="{{ route('admin.impor.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="p-6">

        @csrf

        <label for="file"
               class="mb-3 block text-sm font-medium text-slate-300">
            File Excel
        </label>

        <div class="rounded-xl border-2 border-dashed border-slate-700 bg-slate-950 p-8 text-center transition hover:border-blue-500">

            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="1.5"
                          d="M12 16V4m0 0L8 8m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3"/>
                </svg>
            </div>

            <p class="mb-2 font-medium text-white">
                Pilih file Excel dari komputer
            </p>

            <p class="mb-5 text-xs text-slate-500">
                Format yang didukung: .xlsx dan .xls
            </p>

            <input
                type="file"
                name="file"
                id="file"
                accept=".xlsx,.xls"
                required
                class="block w-full cursor-pointer rounded-lg border border-slate-700 bg-slate-900 text-sm text-slate-300 file:mr-4 file:border-0 file:bg-blue-600 file:px-4 file:py-3 file:font-medium file:text-white hover:file:bg-blue-500"
            >

        </div>

        <div class="mt-6 flex flex-col justify-between gap-4 border-t border-slate-800 pt-6 sm:flex-row sm:items-center">

            <p class="text-xs text-slate-500">
                Pastikan file yang dipilih merupakan dataset implementasi.
            </p>

            <button
                type="submit"
                class="rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">
                Import Data
            </button>

        </div>

    </form>

</div>

@endsection