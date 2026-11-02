@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-10">

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-white">Dashboard</h2>
        <p class="mt-2 text-sm text-slate-400">
            Ringkasan informasi sistem prediksi banjir rob.
        </p>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Total Data Harian</p>
            <h3 class="mt-3 text-3xl font-bold text-white">
                {{ number_format($totalData, 0, ',', '.') }}
            </h3>
            <p class="mt-2 text-xs text-slate-500">Data tersimpan dalam database</p>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Total Kelurahan</p>
            <h3 class="mt-3 text-3xl font-bold text-blue-400">
                {{ number_format($totalKelurahan, 0, ',', '.') }}
            </h3>
            <p class="mt-2 text-xs text-slate-500">Wilayah terdaftar</p>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Total Proses Impor</p>
            <h3 class="mt-3 text-3xl font-bold text-emerald-400">
                {{ number_format($totalImpor, 0, ',', '.') }}
            </h3>
            <p class="mt-2 text-xs text-slate-500">Riwayat pengunggahan dataset</p>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-sm text-slate-400">Periode Dataset</p>

            <h3 class="mt-3 text-lg font-bold text-white">
                @if ($tanggalAwal && $tanggalAkhir)
                    {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
                @else
                    Belum tersedia
                @endif
            </h3>

            <p class="mt-2 text-xs text-slate-500">Rentang tanggal data harian</p>
        </div>

    </div>

    <!-- Informasi Sistem -->
    <div class="grid grid-cols-1 gap-8 xl:grid-cols-2">

        <!-- Impor Terakhir -->
        <div class="rounded-xl border border-slate-800 bg-slate-900 p-7">

            <div class="border-b border-slate-800 px-6 py-5">
                <h3 class="font-semibold text-white">Impor Terakhir</h3>
                <p class="mt-1 text-xs text-slate-400">
                    Informasi pengunggahan dataset terbaru.
                </p>
            </div>

            <div class="p-6">

                @if ($imporTerakhir)

                    <div class="space-y-5">

                        <div>
                            <p class="text-xs text-slate-500">Nama File</p>
                            <p class="mt-1 font-medium text-white">
                                {{ $imporTerakhir->nama_file }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Waktu Impor</p>
                            <p class="mt-1 text-sm text-slate-300">
                                {{ $imporTerakhir->created_at?->format('d M Y, H:i') ?? '-' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-3 gap-3">

                            <div class="rounded-lg bg-slate-950 p-4">
                                <p class="text-xs text-slate-500">Total</p>
                                <p class="mt-2 font-bold text-white">
                                    {{ number_format($imporTerakhir->total_data) }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-emerald-500/10 p-4">
                                <p class="text-xs text-emerald-400">Berhasil</p>
                                <p class="mt-2 font-bold text-emerald-400">
                                    {{ number_format($imporTerakhir->data_berhasil) }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-red-500/10 p-4">
                                <p class="text-xs text-red-400">Gagal</p>
                                <p class="mt-2 font-bold text-red-400">
                                    {{ number_format($imporTerakhir->data_gagal) }}
                                </p>
                            </div>

                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Status</p>

                            @if ($imporTerakhir->status === 'berhasil')
                                <span class="mt-2 inline-block rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400">
                                    Berhasil
                                </span>
                            @elseif ($imporTerakhir->status === 'gagal')
                                <span class="mt-2 inline-block rounded-full bg-red-500/10 px-3 py-1 text-xs font-medium text-red-400">
                                    Gagal
                                </span>
                            @else
                                <span class="mt-2 inline-block rounded-full bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-400">
                                    Diproses
                                </span>
                            @endif
                        </div>

                    </div>

                @else

                    <p class="py-8 text-center text-sm text-slate-400">
                        Belum ada proses impor.
                    </p>

                @endif

            </div>
        </div>

        <!-- Akses Cepat -->
        <div class="rounded-xl border border-slate-800 bg-slate-900 p-7">

            <div class="border-b border-slate-800 px-6 py-5">
                <h3 class="font-semibold text-white">Akses Cepat</h3>
                <p class="mt-1 text-xs text-slate-400">
                    Navigasi menuju pengelolaan sistem.
                </p>
            </div>

            <div class="space-y-4 p-6">

                <a href="{{ route('admin.data.index') }}"
                   class="flex items-center justify-between rounded-lg border border-slate-800 bg-slate-950 p-4 transition hover:border-blue-500">
                    <div>
                        <p class="font-medium text-white">Data Harian</p>
                        <p class="mt-1 text-xs text-slate-400">
                            Lihat data lingkungan seluruh kelurahan.
                        </p>
                    </div>
                    <span class="text-blue-400">→</span>
                </a>

                <a href="{{ route('admin.kelurahan.index') }}"
                   class="flex items-center justify-between rounded-lg border border-slate-800 bg-slate-950 p-4 transition hover:border-blue-500">
                    <div>
                        <p class="font-medium text-white">Master Kelurahan</p>
                        <p class="mt-1 text-xs text-slate-400">
                            Lihat daftar wilayah dan karakteristiknya.
                        </p>
                    </div>
                    <span class="text-blue-400">→</span>
                </a>

                <a href="{{ route('admin.impor.index') }}"
                   class="flex items-center justify-between rounded-lg border border-slate-800 bg-slate-950 p-4 transition hover:border-blue-500">
                    <div>
                        <p class="font-medium text-white">Upload Dataset</p>
                        <p class="mt-1 text-xs text-slate-400">
                            Tambahkan data implementasi melalui Excel.
                        </p>
                    </div>
                    <span class="text-blue-400">→</span>
                </a>

                <a href="{{ route('admin.impor.riwayat') }}"
                   class="flex items-center justify-between rounded-lg border border-slate-800 bg-slate-950 p-4 transition hover:border-blue-500">
                    <div>
                        <p class="font-medium text-white">Riwayat Impor</p>
                        <p class="mt-1 text-xs text-slate-400">
                            Periksa catatan proses impor sebelumnya.
                        </p>
                    </div>
                    <span class="text-blue-400">→</span>
                </a>

            </div>
        </div>

    </div>

    <!-- Grafik Curah Hujan -->
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white">
                Tren Curah Hujan
            </h3>
            <p class="mt-1 text-sm text-slate-400">
                Rata-rata curah hujan harian dari seluruh kelurahan.
            </p>
        </div>

        <div class="relative h-80">
            <canvas id="grafikCurahHujan"></canvas>
        </div>

    </div>

    <!-- Grafik Kondisi Laut -->
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white">
                Tren Kondisi Laut
            </h3>
            <p class="mt-1 text-sm text-slate-400">
                Rata-rata pasang tertinggi dan gelombang tertinggi harian.
            </p>
        </div>

        <div class="relative h-80">
            <canvas id="grafikKondisiLaut"></canvas>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async function () {

    try {
        const response = await fetch(
            @json(route('admin.dashboard.grafik'))
        );

        if (!response.ok) {
            throw new Error('Gagal mengambil data grafik.');
        }

        const data = await response.json();

        const labels = data.map(item => {
            const tanggal = new Date(item.tanggal + 'T00:00:00');

            return tanggal.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short'
            });
        });

        const curahHujan = data.map(item =>
            Number(item.rata_curah_hujan)
        );

        const pasang = data.map(item =>
            Number(item.rata_pasang)
        );

        const gelombang = data.map(item =>
            Number(item.rata_gelombang)
        );

        const opsiUmum = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#cbd5e1',
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#94a3b8',
                        maxTicksLimit: 12,
                        autoSkip: true,
                        maxRotation: 0,
                        minRotation: 0
                    },
                    grid: {
                        color: '#1e293b'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#94a3b8'
                    },
                    grid: {
                        color: '#1e293b'
                    }
                }
            }
        };

        // Grafik Curah Hujan
        new Chart(document.getElementById('grafikCurahHujan'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Curah Hujan (mm)',
                    data: curahHujan,
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.12)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                    pointHoverRadius: 5
                }]
            },
            options: opsiUmum
        });

        // Grafik Pasang dan Gelombang
        new Chart(document.getElementById('grafikKondisiLaut'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pasang Tertinggi (m)',
                        data: pasang,
                        borderColor: '#a78bfa',
                        backgroundColor: 'rgba(167, 139, 250, 0.10)',
                        tension: 0.3,
                        pointRadius: 2,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Gelombang Tertinggi (m)',
                        data: gelombang,
                        borderColor: '#34d399',
                        backgroundColor: 'rgba(52, 211, 153, 0.10)',
                        tension: 0.3,
                        pointRadius: 2,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: opsiUmum
        });

    } catch (error) {
        console.error(error);
    }

});
</script>

@endsection