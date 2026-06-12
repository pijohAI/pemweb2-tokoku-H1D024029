<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel – {{ config('app.name', 'PeduliBareng') }}</title>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 font-sans antialiased">

    {{-- TOP NAV --}}
    <nav class="sticky top-0 z-50 w-full border-b border-zinc-200 dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-sm">
        <div class="mx-auto flex h-14 max-w-screen-xl items-center justify-between px-4 lg:px-6">
            {{-- Logo --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 font-bold text-zinc-900 dark:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 text-white text-sm font-bold shadow">P</span>
                <span class="hidden sm:inline">PeduliBareng <span class="text-xs font-medium text-emerald-500 ml-1">Admin</span></span>
            </a>

            {{-- Mobile menu toggle --}}
            <button id="admin-sidebar-toggle" class="lg:hidden rounded-md p-2 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>

            {{-- User menu --}}
            <div class="hidden lg:flex items-center gap-3">
                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-md px-3 py-1.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="flex min-h-[calc(100vh-3.5rem)]">
        {{-- SIDEBAR --}}
        <aside id="admin-sidebar" class="fixed inset-y-14 left-0 z-40 w-64 -translate-x-full transform border-r border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
            <nav class="flex flex-col gap-1 p-3 pt-4">
                <p class="mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
                    Dashboard
                </a>

                <p class="mt-3 mb-1 px-3 text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Kelola Data</p>

                <a href="{{ route('admin.categories') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.categories') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z" /></svg>
                    Kategori
                </a>

                <a href="{{ route('admin.campaigns') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.campaigns') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    Kampanye
                </a>

                <a href="{{ route('admin.donations') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.donations') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Donasi
                </a>

                <div class="mt-auto pt-6">
                    <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 overflow-x-hidden p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>

    {{-- Overlay for mobile sidebar --}}
    <div id="admin-overlay" class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm hidden lg:hidden"></div>

    <script>
        const toggle = document.getElementById('admin-sidebar-toggle');
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-overlay');

        toggle?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

    @fluxScripts
</body>
</html>
