<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Manajemen Kampanye</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Kelola seluruh kampanye donasi di platform.</p>
        </div>
        <button wire:click="openCreate" id="btn-add-campaign"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 active:scale-95 transition-all">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Kampanye
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 rounded-lg border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 rounded-lg border border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-700 dark:text-red-400">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input wire:model.live.debounce.300ms="search" id="search-campaign" type="text"
                   placeholder="Cari judul kampanye..."
                   class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 py-2.5 pl-9 pr-4 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" />
        </div>
        <select wire:model.live="filterStatus" id="filter-status"
                class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 py-2.5 px-3 text-sm text-zinc-900 dark:text-zinc-100 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50 text-left">
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400 w-12">#</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Kampanye</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden md:table-cell">Kategori</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden lg:table-cell">Progress</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Status</th>
                        <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($campaigns as $campaign)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-700/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-400 text-xs">{{ $campaign->id }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($campaign->image_path)
                                        <img src="{{ Storage::url($campaign->image_path) }}" alt="{{ $campaign->title }}"
                                             class="h-10 w-10 rounded-lg object-cover shrink-0 bg-zinc-100" />
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center shrink-0">
                                            <svg class="h-5 w-5 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-zinc-900 dark:text-white line-clamp-1">{{ $campaign->title }}</p>
                                        <p class="text-xs text-zinc-400">{{ $campaign->end_date?->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2.5 py-0.5 text-xs font-medium text-purple-700 dark:text-purple-300">
                                    {{ $campaign->category?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell">
                                @php
                                    $pct = $campaign->target_amount > 0
                                        ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100)
                                        : 0;
                                @endphp
                                <div class="w-32">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ number_format($pct, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-zinc-100 dark:bg-zinc-700 rounded-full h-1.5">
                                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <p class="mt-1 text-xs text-zinc-400">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $campaign->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                    {{ $campaign->status === 'completed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                    {{ $campaign->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                ">
                                    {{ $campaign->status === 'active' ? 'Aktif' : ($campaign->status === 'completed' ? 'Selesai' : 'Dibatalkan') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button wire:click="openEdit({{ $campaign->id }})"
                                            class="rounded-md p-1.5 text-zinc-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    @if($campaign->status === 'active')
                                        <button wire:click="updateStatus({{ $campaign->id }}, 'completed')"
                                                wire:confirm="Tandai kampanye ini sebagai selesai?"
                                                class="rounded-md p-1.5 text-zinc-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition-colors" title="Selesaikan">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    @endif
                                    <button wire:click="delete({{ $campaign->id }})"
                                            wire:confirm="Yakin ingin menghapus kampanye ini?"
                                            class="rounded-md p-1.5 text-zinc-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 transition-colors" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-zinc-400">
                                <svg class="mx-auto mb-3 h-10 w-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <p>Tidak ada kampanye ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-100 dark:border-zinc-700 px-4 py-3">
            {{ $campaigns->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-10 bg-black/50 backdrop-blur-sm overflow-y-auto" wire:click.self="closeModal">
            <div class="w-full max-w-2xl rounded-2xl bg-white dark:bg-zinc-900 shadow-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden mb-10">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        {{ $editingId ? 'Edit Kampanye' : 'Tambah Kampanye Baru' }}
                    </h2>
                    <button wire:click="closeModal" class="rounded-md p-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit="save" class="px-6 py-5 space-y-5">
                    <div>
                        <label for="camp-title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Judul Kampanye <span class="text-red-500">*</span></label>
                        <input wire:model="title" id="camp-title" type="text" placeholder="Judul kampanye yang menarik..."
                               class="w-full rounded-lg border @error('title') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 @error('title') focus:ring-red-400/30 @else focus:ring-emerald-400/30 focus:border-emerald-400 @enderror" />
                        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="camp-category" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                            <select wire:model="categoryId" id="camp-category"
                                    class="w-full rounded-lg border @error('categoryId') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="camp-status" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select wire:model="status" id="camp-status"
                                    class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-emerald-400/20">
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="camp-target" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Target Dana (Rp) <span class="text-red-500">*</span></label>
                            <input wire:model="targetAmount" id="camp-target" type="number" min="10000" placeholder="50000000"
                                   class="w-full rounded-lg border @error('targetAmount') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 @error('targetAmount') focus:ring-red-400/30 @else focus:ring-emerald-400/30 focus:border-emerald-400 @enderror" />
                            @error('targetAmount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="camp-end" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input wire:model="endDate" id="camp-end" type="date"
                                   class="w-full rounded-lg border @error('endDate') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 @error('endDate') focus:ring-red-400/30 @else focus:ring-emerald-400/30 focus:border-emerald-400 @enderror" />
                            @error('endDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="camp-desc" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea wire:model="description" id="camp-desc" rows="4" placeholder="Jelaskan tujuan kampanye, penerima manfaat, dan penggunaan dana..."
                                  class="w-full resize-none rounded-lg border @error('description') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 @error('description') focus:ring-red-400/30 @else focus:ring-emerald-400/30 focus:border-emerald-400 @enderror"></textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="camp-image" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                            Gambar Kampanye {{ $editingId ? '(kosongkan jika tidak ingin mengubah)' : '' }} <span class="text-red-500">{{ $editingId ? '' : '*' }}</span>
                        </label>
                        @if($existingImage)
                            <div class="mb-2">
                                <img src="{{ Storage::url($existingImage) }}" alt="Gambar saat ini" class="h-24 w-auto rounded-lg object-cover border border-zinc-200 dark:border-zinc-700" />
                                <p class="text-xs text-zinc-400 mt-1">Gambar saat ini</p>
                            </div>
                        @endif
                        <input wire:model="imageFile" id="camp-image" type="file" accept="image/*"
                               class="w-full rounded-lg border @error('imageFile') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 file:mr-3 file:rounded-md file:border-0 file:bg-emerald-50 dark:file:bg-emerald-900/30 file:px-3 file:py-1 file:text-xs file:font-medium file:text-emerald-700 dark:file:text-emerald-400" />
                        @error('imageFile') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="imageFile" class="mt-1 text-xs text-zinc-400">Mengunggah gambar...</div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                        <button type="button" wire:click="closeModal"
                                class="rounded-lg border border-zinc-200 dark:border-zinc-700 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 active:scale-95 transition-all">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Simpan Perubahan' : 'Tambahkan' }}</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
