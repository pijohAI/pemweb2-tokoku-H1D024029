<div>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Manajemen Donasi</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Verifikasi dan kelola semua donasi yang masuk.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 rounded-lg border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input wire:model.live.debounce.300ms="search" id="search-donation" type="text"
                   placeholder="Cari nama donatur atau kampanye..."
                   class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 py-2.5 pl-9 pr-4 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" />
        </div>
        <select wire:model.live="filterStatus" id="filter-donation-status"
                class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 py-2.5 px-3 text-sm text-zinc-900 dark:text-zinc-100 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu</option>
            <option value="success">Berhasil</option>
            <option value="failed">Ditolak</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50 text-left">
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">#</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Donatur</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden md:table-cell">Kampanye</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Jumlah</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden sm:table-cell">Metode</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Status</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-700/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-400 text-xs">{{ $donation->id }}</td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">
                                        {{ $donation->is_anonymous ? '🫥 Hamba Allah' : ($donation->donor_name ?? $donation->user?->name ?? '—') }}
                                    </p>
                                    <p class="text-xs text-zinc-400">{{ $donation->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <p class="text-sm text-zinc-700 dark:text-zinc-300 line-clamp-1 max-w-[200px]">{{ $donation->campaign?->title ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-zinc-900 dark:text-white">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-700 px-2.5 py-0.5 text-xs font-medium text-zinc-600 dark:text-zinc-300 uppercase">
                                    {{ $donation->payment_method }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $donation->status === 'success' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                    {{ $donation->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                    {{ $donation->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                ">
                                    {{ $donation->status === 'success' ? 'Berhasil' : ($donation->status === 'pending' ? 'Menunggu' : 'Ditolak') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    @if($donation->payment_proof)
                                        <a href="{{ Storage::url($donation->payment_proof) }}" target="_blank"
                                           class="rounded-md p-1.5 text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 hover:text-zinc-600 transition-colors" title="Lihat Bukti">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    @endif
                                    @if($donation->status === 'pending')
                                        <button wire:click="verify({{ $donation->id }})"
                                                wire:confirm="Verifikasi donasi ini sebagai berhasil?"
                                                class="rounded-md p-1.5 text-zinc-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-600 transition-colors" title="Verifikasi">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                        <button wire:click="reject({{ $donation->id }})"
                                                wire:confirm="Tolak donasi ini?"
                                                class="rounded-md p-1.5 text-zinc-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 transition-colors" title="Tolak">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-zinc-400">
                                <svg class="mx-auto mb-3 h-10 w-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p>Tidak ada donasi ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-100 dark:border-zinc-700 px-4 py-3">
            {{ $donations->links() }}
        </div>
    </div>
</div>
