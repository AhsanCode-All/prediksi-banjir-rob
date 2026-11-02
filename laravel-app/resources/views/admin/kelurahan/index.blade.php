@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-white">Master Kelurahan</h1>
        <p class="mt-1 text-sm text-slate-400">
            Daftar wilayah yang digunakan dalam sistem prediksi banjir rob.
        </p>
    </div>

    <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
        <p class="text-sm text-slate-400">Total Kelurahan</p>
        <p class="mt-2 text-3xl font-bold text-white">
            {{ number_format($totalKelurahan) }}
        </p>
        <p class="mt-1 text-xs text-slate-500">Wilayah terdaftar dalam sistem</p>
    </div>

    <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">

        <form method="GET" action="{{ route('admin.kelurahan.index') }}"
              class="mb-5 flex flex-col gap-3 sm:flex-row">

            <input
                type="text"
                name="cari"
                value="{{ request('cari') }}"
                placeholder="Cari nama kelurahan..."
                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:outline-none"
            >

            <button
                type="submit"
                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">
                Cari
            </button>

            <a href="{{ route('admin.kelurahan.index') }}"
               class="rounded-lg border border-slate-700 px-5 py-2.5 text-center text-sm text-slate-300 hover:bg-slate-800">
                Reset
            </a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-700 text-xs uppercase text-slate-400">
                    <th class="px-4 py-3">Aksi</th>
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Kode Kelurahan</th>
                        <th class="px-4 py-3">Nama Kelurahan</th>
                        <th class="px-4 py-3">Elevasi</th>
                        <th class="px-4 py-3">Jarak Pantai</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @forelse ($kelurahan as $item)
                        <tr class="hover:bg-slate-800/60">
                            <td class="px-4 py-3">
                                {{ $kelurahan->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-3 font-mono text-xs text-blue-400">
                                {{ $item->kode_wilayah }}
                            </td>

                            <td class="px-4 py-3 font-medium text-white">
                                {{ $item->nama_kelurahan }}
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format($item->elevasi, 2, ',', '.') }} m
                            </td>

                            <td class="px-4 py-3">
                                {{ number_format($item->jarak_pantai, 2, ',', '.') }} km
                            </td>

                            <td class="px-4 py-3">
                                <a href="{{ route('admin.kelurahan.show', $item->id) }}"
                                class="rounded-lg bg-blue-600/20 px-3 py-2 text-xs font-semibold text-blue-400 hover:bg-blue-600/30">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                Data kelurahan tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $kelurahan->links() }}
        </div>

    </div>
</div>
@endsection