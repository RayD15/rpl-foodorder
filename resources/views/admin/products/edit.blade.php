@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-ink-500 hover:text-tomato-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
            Kembali
        </a>
        <h1 class="mt-1 font-display text-3xl font-normal text-ink-900">Edit Produk</h1>
    </div>

    <div class="max-w-xl rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Nama Produk</span>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
                @error('name') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Kategori</span>
                <select name="category_id" required
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Harga (Rp)</span>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
                @error('price') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Deskripsi</span>
                <textarea name="description" rows="3"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">{{ old('description', $product->description) }}</textarea>
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Gambar</span>
                @if ($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-20 w-20 rounded-xl border-2 border-ink-200 object-cover">
                @endif
                <input type="file" name="image" accept="image/*"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm">
                <span class="text-xs text-ink-400">Kosongkan jika tidak ingin ganti gambar.</span>
                @error('image') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </label>

            <div>
                <span class="text-sm font-bold text-ink-800">Status</span>
                <div class="mt-2 flex gap-4">
                    <label class="flex items-center gap-2 text-sm font-bold text-ink-800">
                        <input type="radio" name="status" value="ready" @checked(old('status', $product->status) === 'ready') class="h-4 w-4 accent-tomato-500">
                        Ready
                    </label>
                    <label class="flex items-center gap-2 text-sm font-bold text-ink-800">
                        <input type="radio" name="status" value="sold_out" @checked(old('status', $product->status) === 'sold_out') class="h-4 w-4 accent-tomato-500">
                        Sold Out
                    </label>
                </div>
                @error('status') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                class="mt-2 rounded-2xl border-2 border-ink-900 bg-tomato-500 px-6 py-3.5 text-sm font-extrabold text-white shadow-[4px_4px_0_0_var(--color-ink-900)] transition hover:bg-tomato-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
