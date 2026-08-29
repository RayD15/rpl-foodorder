@extends('layouts.customer')

@section('title', 'Menu')

@section('content')
    <div class="animate-rise mb-5 flex items-center justify-between">
        <h1 class="font-display text-3xl font-normal text-ink-900">Menu</h1>
    </div>

    <form action="{{ route('menu') }}" method="GET" class="animate-rise relative mb-4" style="--reveal-delay:60ms">
        @if ($categorySlug)
            <input type="hidden" name="category" value="{{ $categorySlug }}">
        @endif
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari produk..." autocomplete="off"
            class="w-full rounded-2xl border-2 border-ink-200 bg-cream-100 px-5 py-3.5 pr-12 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
        <button type="submit" aria-label="Cari"
            class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-lg bg-tomato-500 text-white shadow-[2px_2px_0_0_var(--color-ink-900)] transition hover:bg-tomato-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
        </button>
    </form>

    <div class="animate-rise mb-6 flex gap-2 overflow-x-auto pb-1 scrollbar-hide" style="--reveal-delay:120ms">
        <a href="{{ route('menu', ['q' => $search]) }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 text-sm font-bold transition {{ !$categorySlug ? 'bg-tomato-500 text-white shadow-[3px_3px_0_0_var(--color-ink-900)]' : 'border-2 border-ink-200 bg-cream-100 text-ink-600 hover:border-tomato-400 hover:text-tomato-600' }}">
            Semua
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('menu', ['category' => $category->slug, 'q' => $search]) }}"
                class="whitespace-nowrap rounded-xl px-4 py-2 text-sm font-bold transition {{ $categorySlug === $category->slug ? 'bg-tomato-500 text-white shadow-[3px_3px_0_0_var(--color-ink-900)]' : 'border-2 border-ink-200 bg-cream-100 text-ink-600 hover:border-tomato-400 hover:text-tomato-600' }}">
                {{ $category->name }}
                <span class="opacity-60 font-semibold">({{ $category->products_count }})</span>
            </a>
        @endforeach
    </div>

    @if ($products->count())
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            @foreach ($products as $product)
                <div style="--reveal-delay:{{ $loop->index * 70 }}ms" class="reveal">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    @else
        <div class="reveal py-16 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="mx-auto mb-4 h-16 w-16 text-ink-300"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75c-4.5 0-6.75 4.44-6.75 4.44s2.25 4.44 6.75 4.44 6.75-4.44 6.75-4.44S16.5 6.75 12 6.75Zm0 4.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5Z" /></svg>
            <h2 class="font-display text-2xl font-normal text-ink-900">Produk tidak ditemukan</h2>
            <p class="mt-1 text-sm text-ink-400">Coba kata kunci atau kategori lain.</p>
        </div>
    @endif
@endsection
