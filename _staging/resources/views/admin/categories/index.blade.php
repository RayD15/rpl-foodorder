@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
    <div class="mb-6">
        <h1 class="font-display text-3xl font-normal text-ink-900">Kategori</h1>
        <p class="text-sm text-ink-500">Kelola kategori menu.</p>
    </div>

    <div class="max-w-xl rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:gap-3">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kategori baru (mis. Makanan)"
                class="flex-1 rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-honey-500 focus:ring-2 focus:ring-honey-500/20">
            <button type="submit" class="rounded-xl border border-ink-200 bg-honey-500 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-honey-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none sm:w-auto">+ Tambah</button>
        </form>
        @error('name') <p class="mt-2 text-xs font-bold text-honey-600">{{ $message }}</p> @enderror
    </div>

    @forelse ($categories as $category)
        {{-- Tampilan mobile: card list --}}
        <div class="mt-3 flex items-center justify-between gap-3 rounded-2xl border-2 border-ink-200 bg-cream-100 p-4 card-brutal-hover md:hidden">
            <div class="min-w-0">
                <p class="truncate font-bold text-ink-900">{{ $category->name }}</p>
                <p class="text-xs text-ink-400">{{ $category->products_count }} produk</p>
            </div>
            <div class="flex flex-shrink-0 gap-2">
                <button type="button" onclick="editCategory({{ $category->id }}, '{{ $category->name }}')"
                    class="rounded-lg border border-ink-200 bg-cream-50 px-3 py-1.5 text-xs font-bold text-ink-700 transition hover:bg-cream-200">Edit</button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Produk di dalamnya ikut terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-honey-500/30 bg-honey-500/10 px-3 py-1.5 text-xs font-bold text-honey-600 transition hover:bg-honey-500/20">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="mt-3 rounded-2xl border-2 border-ink-200 bg-cream-100 p-10 text-center text-ink-400 md:hidden">Belum ada kategori.</div>
    @endforelse

    {{-- Tampilan desktop: tabel --}}
    <div class="mt-6 hidden max-w-xl overflow-hidden rounded-2xl border-2 border-ink-200 bg-cream-100 card-brutal-hover md:block">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink-200 text-xs uppercase tracking-wide text-ink-500">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Jumlah Produk</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="font-bold text-ink-900">{{ $category->name }}</span>
                            <span class="text-xs text-ink-400">({{ $category->slug }})</span>
                        </td>
                        <td class="px-4 py-3 text-ink-700">{{ $category->products_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="editCategory({{ $category->id }}, '{{ $category->name }}')"
                                    class="rounded-lg border border-ink-200 bg-cream-50 px-3 py-1.5 text-xs font-bold text-ink-700 transition hover:bg-cream-200">Edit</button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Produk di dalamnya ikut terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-honey-500/30 bg-honey-500/10 px-3 py-1.5 text-xs font-bold text-honey-600 transition hover:bg-honey-500/20">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-10 text-center text-ink-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="edit-category-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-sm rounded-2xl border border-ink-200 bg-cream-100 p-6 shadow-sm">
            <h2 class="mb-4 font-display text-2xl font-normal text-ink-900">Edit Kategori</h2>
            <form id="edit-category-form" method="POST" class="flex flex-col gap-3">
                @csrf
                @method('PUT')
                <input type="text" id="edit-category-name" name="name" required
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-honey-500 focus:ring-2 focus:ring-honey-500/20">
                <div class="flex gap-2">
                    <button type="button" id="edit-category-cancel" class="flex-1 rounded-xl border border-ink-200 bg-cream-100 py-3 text-sm font-extrabold text-ink-800 shadow-xs transition hover:bg-cream-200 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl border border-ink-200 bg-honey-500 py-3 text-sm font-extrabold text-white shadow-xs transition hover:bg-honey-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let editCategoryForm = document.getElementById('edit-category-form');
        let editCategoryModal = document.getElementById('edit-category-modal');
        let editCategoryName = document.getElementById('edit-category-name');
        function editCategory(id, name) {
            editCategoryForm.action = `/admin/categories/${id}`;
            editCategoryName.value = name;
            editCategoryModal.classList.remove('hidden');
            editCategoryModal.classList.add('flex');
        }
        document.getElementById('edit-category-cancel').addEventListener('click', () => {
            editCategoryModal.classList.add('hidden');
            editCategoryModal.classList.remove('flex');
        });
        editCategoryModal.addEventListener('click', (e) => {
            if (e.target === editCategoryModal) {
                editCategoryModal.classList.add('hidden');
                editCategoryModal.classList.remove('flex');
            }
        });
    </script>
@endsection
