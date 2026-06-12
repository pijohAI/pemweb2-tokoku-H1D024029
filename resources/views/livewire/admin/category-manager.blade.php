<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Manajemen Kategori</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Kelola kategori kampanye yang tersedia di platform.</p>
        </div>
        <button wire:click="openCreate" id="btn-add-category"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 active:scale-95 transition-all">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Kategori
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

    {{-- Search --}}
    <div class="mb-4">
        <div class="relative">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input wire:model.live.debounce.300ms="search" id="search-category" type="text"
                   placeholder="Cari nama kategori..."
                   class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 py-2.5 pl-9 pr-4 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" />
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50 text-left">
                    <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">#</th>
                    <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Nama Kategori</th>
                    <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden md:table-cell">Slug</th>
                    <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400 hidden sm:table-cell">Kampanye</th>
                    <th class="px-4 py-3 font-semibold text-zinc-600 dark:text-zinc-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-700/30 transition-colors">
                        <td class="px-4 py-3 text-zinc-400">{{ $category->id }}</td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $category->name }}</p>
                                @if($category->description)
                                    <p class="text-xs text-zinc-400 mt-0.5 line-clamp-1">{{ $category->description }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <code class="rounded bg-zinc-100 dark:bg-zinc-700 px-1.5 py-0.5 text-xs text-zinc-600 dark:text-zinc-300">{{ $category->slug }}</code>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400">
                                {{ $category->campaigns_count }} kampanye
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEdit({{ $category->id }})"
                                        class="rounded-md p-1.5 text-zinc-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                        title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <button wire:click="delete({{ $category->id }})"
                                        wire:confirm="Yakin ingin menghapus kategori '{{ $category->name }}'?"
                                        class="rounded-md p-1.5 text-zinc-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                        title="Hapus">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-zinc-400">
                            <svg class="mx-auto mb-3 h-10 w-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z" /></svg>
                            <p>Tidak ada kategori ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-zinc-100 dark:border-zinc-700 px-4 py-3">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:click.self="closeModal">
            <div class="w-full max-w-lg rounded-2xl bg-white dark:bg-zinc-900 shadow-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden"
                 x-data x-trap="true">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        {{ $editingId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                    </h2>
                    <button wire:click="closeModal" class="rounded-md p-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit="save" class="px-6 py-5 space-y-5">
                    <div>
                        <label for="cat-name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                        <input wire:model="name" id="cat-name" type="text" placeholder="contoh: Bencana Alam"
                               class="w-full rounded-lg border @error('name') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 @error('name') focus:ring-red-400/30 @else focus:ring-emerald-400/30 focus:border-emerald-400 @enderror" />
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cat-desc" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Deskripsi <span class="text-zinc-400 font-normal">(opsional)</span></label>
                        <textarea wire:model="description" id="cat-desc" rows="3" placeholder="Deskripsi singkat tentang kategori ini..."
                                  class="w-full resize-none rounded-lg border @error('description') border-red-400 @else border-zinc-200 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 px-3 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 @error('description') focus:ring-red-400/30 @else focus:ring-emerald-400/30 focus:border-emerald-400 @enderror"></textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
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
