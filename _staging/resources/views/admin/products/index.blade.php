@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="font-display text-3xl font-normal text-ink-900">Produk</h1>
            <p class="text-sm text-ink-500">Kelola menu makanan & minuman.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-ink-200 bg-honey-500 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-honey-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah
        </a>
    </div>

    @forelse ($products as $product)
        {{-- Tampilan mobile: card list --}}
        <div class="mb-3 rounded-2xl border-2 border-ink-200 bg-cream-100 p-4 card-brutal-hover md:hidden">
            <div class="flex items-start gap-3">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-14 w-14 flex-shrink-0 rounded-xl object-cover bg-cream-200">
                <div class="min-w-0 flex-1">
                    <p class="truncate font-bold text-ink-900">{{ $product->name }}</p>
                    <p class="text-xs text-ink-500">{{ $product->category?->name }}</p>
                    <p class="mt-1 text-sm font-extrabold text-ink-900">{{ 'Rp' . number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <span class="flex-shrink-0 rounded-full px-2.5 py-1 text-xs font-bold {{ $product->isReady() ? 'bg-leaf-400/20 text-leaf-600' : 'bg-cream-200 text-ink-500' }}">
                    {{ $product->isReady() ? 'Ready' : 'Sold Out' }}
                </span>
            </div>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 rounded-xl border border-ink-200 bg-cream-50 px-3 py-2 text-center text-sm font-bold text-ink-700 transition hover:bg-cream-200">Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-xl border-2 border-honey-500/40 bg-honey-500/10 px-3 py-2 text-sm font-bold text-honey-600 transition hover:bg-honey-500/20">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <div class="rounded-2xl border-2 border-ink-200 bg-cream-100 p-10 text-center text-ink-400 md:hidden">Belum ada produk.</div>
    @endforelse

    {{-- Tampilan desktop: tabel --}}
    <div class="hidden overflow-x-auto rounded-2xl border-2 border-ink-200 bg-cream-100 card-brutal-hover md:block">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink-200 text-xs uppercase tracking-wide text-ink-500">
                <tr>
                    <th class="px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover bg-cream-200">
                                <p class="font-bold text-ink-900">{{ $product->name }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-ink-700">{{ $product->category?->name }}</td>
                        <td class="px-4 py-3 font-bold text-ink-900">{{ 'Rp' . number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $product->isReady() ? 'bg-leaf-400/20 text-leaf-600' : 'bg-cream-200 text-ink-500' }}">
                                {{ $product->isReady() ? 'Ready' : 'Sold Out' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg border border-ink-200 bg-cream-50 px-3 py-1.5 text-xs font-bold text-ink-700 transition hover:bg-cream-200">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-honey-500/30 bg-honey-500/10 px-3 py-1.5 text-xs font-bold text-honey-600 transition hover:bg-honey-500/20">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-ink-400">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
