@extends('layouts.customer')

@section('title', $product->name)
@section('meta_description', $product->description ?: 'Pesan '.$product->name.' di TamsisFood Jakarta — langsung via WhatsApp.')
@section('og_image', $product->image_url)

@section('content')
    <nav class="animate-rise mb-4">
        <a href="{{ route('menu') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-ink-500 hover:text-honey-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
            Menu
        </a>
    </nav>

    <div class="animate-rise overflow-hidden rounded-3xl border-2 border-ink-200 bg-cream-100 card-brutal-hover" style="--reveal-delay:60ms">
        <div class="aspect-[4/3] bg-cream-200">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" fetchpriority="high" decoding="async" class="h-full w-full object-cover">
        </div>
        <div class="p-6">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-honey-200 px-3 py-1 text-xs font-bold text-ink-800">
                    {{ $product->category->name }}
                </span>
                @if (! $product->isReady())
                    <span class="inline-flex items-center rounded-full bg-ink-900 px-3 py-1 text-xs font-bold text-white">Sold Out</span>
                @endif
            </div>
            <h1 class="mt-3 font-display text-3xl font-normal leading-tight text-ink-900">{{ $product->name }}</h1>
            <p class="mt-1 text-2xl font-extrabold text-honey-600">{{ 'Rp' . number_format($product->price, 0, ',', '.') }}</p>
            <p class="mt-3 text-sm leading-relaxed text-ink-600">{{ $product->description }}</p>

            @if ($product->isReady())
            <div class="mt-6">
                <p class="mb-2 text-sm font-bold text-ink-600">Jumlah</p>
                <div class="inline-flex items-center gap-4 rounded-2xl border-2 border-ink-200 bg-cream-50 p-2">
                    <button type="button" id="qty-dec" class="grid h-10 w-10 place-items-center rounded-xl bg-cream-200 text-lg font-bold text-ink-700 transition hover:bg-ink-200">−</button>
                    <span id="qty-val" class="w-8 text-center text-lg font-extrabold text-ink-900">1</span>
                    <button type="button" id="qty-inc" class="grid h-10 w-10 place-items-center rounded-xl bg-honey-400 text-lg font-bold text-white shadow-xs transition hover:bg-honey-300 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">+</button>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" id="product-add-btn" data-add-to-cart="{{ $product->id }}" data-qty="1"
                    class="col-span-2 inline-flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-400 text-ink-900 px-6 py-4 text-base font-extrabold text-white shadow-sm transition hover:bg-honey-300 hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-sm active:translate-x-1 active:translate-y-1 active:shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                    Tambah ke Keranjang
                </button>
                <a href="{{ route('menu') }}"
                    class="col-span-2 flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-cream-100 px-6 py-3.5 text-sm font-extrabold text-ink-800 shadow-sm transition hover:bg-cream-200 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                    Lanjut ke Menu
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>
            @else
            <div class="mt-6 rounded-2xl border-2 border-ink-200 bg-cream-50 p-4 text-center">
                <p class="text-sm font-extrabold text-ink-500">Maaf, produk ini sedang <span class="text-ink-900">Sold Out</span>.</p>
                <a href="{{ route('menu') }}"
                    class="mt-3 inline-flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-400 text-ink-900 px-6 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-honey-300 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                    Lihat Menu Lain
                </a>
            </div>
            @endif
        </div>
    </div>

    @if ($related->count())
        <section class="reveal mt-9">
            <h2 class="mb-3 font-display text-xl font-normal text-ink-900">Coba juga</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach ($related as $product)
                    <div style="--reveal-delay:{{ $loop->index * 70 }}ms" class="reveal">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
