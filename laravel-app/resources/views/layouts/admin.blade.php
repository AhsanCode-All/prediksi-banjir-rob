<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | ROB Prediction</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">

    <!-- Header -->
    <header class="sticky top-0 z-50 border-b border-slate-800 bg-slate-900/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

            <!-- Logo -->
            <a href="{{ route('admin.impor.index') }}" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 font-bold text-white">
                    ROB
                </div>

                <div>
                    <h1 class="text-base font-bold tracking-wide text-white">
                        ROB Prediction
                    </h1>
                    <p class="text-xs text-slate-400">
                        Admin Portal
                    </p>
                </div>
            </a>

            <!-- Navigation -->
            <nav class="hidden items-center gap-2 md:flex">
                
                <a href="{{ route('admin.dashboard') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    Dashboard
                </a>

                <a href="{{ route('admin.kelurahan.index') }}"
                    class="px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    Master Kelurahan
                </a>

                <a href="{{ route('admin.impor.index') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition
                   {{ request()->routeIs('admin.impor.index') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    Upload Data
                </a>

                <a href="{{ route('admin.data.index') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition
                   {{ request()->routeIs('admin.data.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    Data Harian
                </a>

                <a href="{{ route('admin.impor.riwayat') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition
                   {{ request()->routeIs('admin.impor.riwayat') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    Riwayat Impor
                </a>

                <a href="{{ route('admin.kualitas-data.index') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition
                    {{ request()->routeIs('admin.kualitas-data.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Kualitas Data
                </a>
            </nav>

            <!-- Admin Info & Logout -->
            <div class="flex items-center gap-4">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-medium text-white">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </p>
                    <p class="text-xs text-slate-400">
                        Administrator
                    </p>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="rounded-lg border border-slate-700 px-3 py-2 text-sm text-slate-300 transition hover:border-red-500 hover:bg-red-500/10 hover:text-red-400">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- Main Content -->
    <main class="mx-auto w-full max-w-screen-2xl px-6 py-8 sm:px-8 lg:px-10">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-6 rounded-xl border border-amber-500/30 bg-amber-500/10 px-5 py-4 text-sm text-amber-400">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-4 text-sm text-red-400">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-5 text-center text-xs text-slate-500">
        ROB Prediction System &copy; {{ date('Y') }}
    </footer>

</body>
</html>