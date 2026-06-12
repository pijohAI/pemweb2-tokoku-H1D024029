<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan aktivitas platform.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-8">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Total Pengguna</p>
            <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['total_users']) }}</p>
            <p class="mt-1 text-xs text-zinc-500">pengguna terdaftar</p>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Kampanye Aktif</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['active_campaigns']) }}</p>
            <p class="mt-1 text-xs text-zinc-500">dari {{ $stats['total_campaigns'] }} total kampanye</p>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Total Donasi</p>
            <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['total_donations']) }}</p>
            @if($stats['pending_donations'] > 0)
                <p class="mt-1 text-xs text-amber-500">{{ $stats['pending_donations'] }} menunggu verifikasi</p>
            @else
                <p class="mt-1 text-xs text-zinc-500">donasi berhasil</p>
            @endif
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Dana Terkumpul</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($stats['total_funds'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-zinc-500">dari semua kampanye</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Recent Donations --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 px-5 py-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Donasi Terbaru</h2>
                <a href="{{ route('admin.donations') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse($recentDonations as $donation)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $donation->is_anonymous ? 'Hamba Allah' : ($donation->donor_name ?? $donation->user?->name ?? 'Anonim') }}
                            </p>
                            <p class="truncate text-xs text-zinc-400">{{ $donation->campaign?->title }}</p>
                        </div>
                        <div class="text-right ml-4 shrink-0">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-white">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $donation->status === 'success' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                {{ $donation->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                {{ $donation->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                            ">{{ ucfirst($donation->status) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-center text-zinc-400">Belum ada donasi.</p>
                @endforelse
            </div>
        </div>

        {{-- Top Campaigns --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 px-5 py-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">Kampanye Terbaik</h2>
                <a href="{{ route('admin.campaigns') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse($topCampaigns as $campaign)
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between mb-1">
                            <p class="truncate text-sm font-medium text-zinc-900 dark:text-white max-w-[60%]">{{ $campaign->title }}</p>
                            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($campaign->collected ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-full bg-zinc-100 dark:bg-zinc-700 rounded-full h-1.5">
                            @php
                                $pct = $campaign->target_amount > 0
                                    ? min(100, ($campaign->collected / $campaign->target_amount) * 100)
                                    : 0;
                            @endphp
                            <div class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-zinc-400">{{ number_format($pct, 1) }}% dari target Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-center text-zinc-400">Belum ada kampanye.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
