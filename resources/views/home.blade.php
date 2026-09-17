@extends('layouts.customer')

@section('title', 'Home')

@push('head')
    @if ($heroProducts->count())
        <link rel="preload" as="image" href="{{ $heroProducts->first()->image_url }}" fetchpriority="high">
    @endif
@endpush

@section('content')
    {{-- ==================== SECTION 1: HERO ==================== --}}
    @if ($heroProducts->count())
        @php
            $slidesData = $heroProducts->map(function ($p) {
                return [
                    'img' => $p->image_url,
                    'name' => $p->name,
                    'url' => route('product.show', $p),
                ];
            })->values();
        @endphp
        <section
            id="hero"
            data-section="hero"
            data-hero-slideshow
            data-slide-interval="4200"
            data-slides='@json($slidesData)'
            class="relative flex h-[78vh] min-h-[460px] items-end overflow-hidden bg-ink-900 text-white sm:items-center"
            style="width: 100vw; margin-left: calc(50% - 50vw);">
            @foreach ($heroProducts as $product)
                <div data-hero-slide="{{ $loop->index }}"
                    class="absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $loop->index === 0 ? 'opacity-100' : 'opacity-0' }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                        @if ($loop->first) fetchpriority="high" decoding="async" @else loading="lazy" decoding="async" @endif
                        class="h-full w-full object-cover animate-kenBurns">
                </div>
            @endforeach

            <div class="absolute inset-0 bg-gradient-to-t from-ink-950/95 via-ink-950/40 to-ink-950/10 sm:bg-gradient-to-r"></div>

            <div class="relative z-10 w-full max-w-2xl px-6 pb-12 pt-16 sm:pt-0 sm:pb-0">
                <h1 style="--reveal-delay:80ms; font-size: clamp(2.5rem, 6vw, 4rem);" class="animate-rise mt-4 max-w-lg font-display font-bold leading-tight">
                    Mau pesan apa<br class="sm:hidden"> hari ini?
                </h1>
                <p style="--reveal-delay:160ms" class="animate-rise mt-3 max-w-sm text-sm text-cream-100/90 sm:text-base">
                    Pilih menu favoritmu, langsung pesan lewat WhatsApp Admin.
                </p>
                <div style="--reveal-delay:240ms" class="animate-rise mt-6 flex flex-wrap items-center gap-3">
                    <a href="#menu" data-scroll-to="#menu"
                        class="inline-flex items-center gap-2 rounded-xl bg-honey-400 px-6 py-3 text-sm font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 active:scale-[0.97]">
                        Lihat Menu
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    </a>
                    <a href="{{ route('product.show', $heroProducts->first()) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">
                        <span class="underline decoration-honey-400 decoration-2 underline-offset-4">{{ $heroProducts->first()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                    </a>
                </div>
                <div data-hero-dots class="mt-5 flex items-center justify-center gap-0">
                    @foreach ($heroProducts as $product)
                        <button type="button" data-hero-dot="{{ $loop->index }}" aria-label="Slide {{ $loop->index + 1 }}"
                            class="grid h-7 w-7 place-items-center" tabindex="0">
                            <span class="block h-2 rounded-full transition-all duration-300 {{ $loop->index === 0 ? 'w-5 bg-honey-400' : 'w-2 bg-white/50' }}"></span>
                        </button>
                    @endforeach
                </div>
            </div>
            @include('partials.wave-divider')
        </section>

    @else
        <section id="hero" data-section="hero" class="text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-honey-200 px-3 py-1 text-xs font-bold text-ink-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
                Jajan di sekolah, makin gampang!
            </span>
            <h1 class="mt-4 font-display text-4xl font-normal leading-tight text-ink-900 sm:text-5xl">
                Mau pesan apa<br class="sm:hidden"> hari ini?
            </h1>
            <p class="mx-auto mt-3 max-w-xs text-sm text-ink-600">
                Pilih menu, checkout, langsung pesan via WhatsApp Admin.
            </p>
            @include('partials.wave-divider')
        </section>
    @endif

    {{-- ==================== SECTION 2: MENU ==================== --}}
    <section id="menu" data-section="menu" class="section-anchor mt-10">
        <div class="reveal mb-5 flex items-end justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-honey-500">Jelajahi</p>
                <h2 class="font-display text-3xl font-normal text-ink-900">Menu</h2><div class="underline-grow mt-1 h-1 rounded-full bg-honey-400"></div>
            </div>
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-1 text-sm font-bold text-ink-500 transition hover:text-honey-500">
                Semua Menu
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            </a>
        </div>

        {{-- Search (client-side, tanpa reload) --}}
        <div class="reveal relative mb-4">
            <input id="home-menu-search" type="search" placeholder="Cari produk..." autocomplete="off"
                class="w-full rounded-2xl border border-honey-400 bg-transparent px-5 py-3.5 pr-12 text-sm outline-none transition placeholder:text-ink-400 focus:border-honey-400 focus:ring-1 focus:ring-honey-400/30">
            <span class="pointer-events-none absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-xl text-honey-400">
                <i data-lucide="search" class="h-5 w-5"></i>
            </span>
        </div>

        {{-- Filter kategori (client-side) --}}
        <div class="reveal mb-6 flex gap-2 overflow-x-auto pb-1 scrollbar-hide" role="tablist" aria-label="Filter kategori">
            <button type="button" data-home-category=""
                class="whitespace-nowrap rounded-xl border px-4 py-2.5 text-sm font-bold transition duration-200 ease-out active:scale-[0.97]">
                Semua
            </button>
            @foreach ($categories as $category)
                <button type="button" data-home-category="{{ $category->slug }}"
                    class="whitespace-nowrap rounded-xl border px-4 py-2.5 text-sm font-bold transition duration-200 ease-out active:scale-[0.97]">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <div id="home-menu-loading" class="hidden py-16">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                @for ($i = 0; $i < 6; $i++)
                    <div class="reveal">
                        <div class="group flex h-full flex-col overflow-hidden card-brutal card-brutal-hover">
                            <a href="#" class="relative block aspect-[4/3] overflow-hidden bg-cream-200 pointer-events-none" aria-label="Produk">
                                <div class="skeleton h-full w-full"></div>
                                <div class="absolute inset-0 grid place-items-center bg-ink-900/50">
                                    <span class="skeleton-text w-16 h-2 rounded-full"></span>
                                </div>
                            </a>
                            <div class="flex flex-1 flex-col p-3.5">
                                <a href="#" class="pointer-events-none">
                                    <h3 class="skeleton-title text-sm font-bold leading-tight text-ink-900"></h3>
                                </a>
                                <p class="mt-0.5 skeleton-text w-24 text-xs text-ink-400"></p>
                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <span class="skeleton-text w-20 text-sm font-extrabold text-honey-600"></span>
                                    @if ($i % 2 === 0)
                                        <button type="button" disabled aria-label="Tambah ke keranjang"
                                            class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-honey-400 text-ink-900 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5 pointer-events-none"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                        </button>
                                    @else
                                        <span class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-cream-200 text-ink-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <div id="home-menu-grid" class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            @foreach ($homeProducts as $product)
                <div style="--reveal-delay:{{ min($loop->index, 8) * 70 }}ms" class="reveal"
                    data-product-card
                    data-name="{{ strtolower($product->name) }}"
                    data-category="{{ $product->category?->slug }}">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

        <div id="home-menu-empty" class="hidden py-16 text-center">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto mb-6 h-20 w-20 text-ink-300"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75c-4.5 0-6.75 4.44-6.75 4.44s2.25 4.44 6.75 4.44 6.75-4.44 6.75-4.44S16.5 6.75 12 6.75Zm0 4.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5Z" /></svg>
                <h3 class="font-display text-2xl font-normal text-ink-900">Produk tidak ditemukan</h3>
                <p class="mt-2 text-sm text-ink-400 max-w-xl">
                    Coba kata kunci atau kategori lain, atau pastikan akses internet Anda stabil.
                </p>
                <a href="{{ route('menu') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl border-2 border-honey-400 bg-honey-400 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-honey-300 active:scale-[0.97]">
                    Lihat Semua Menu
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>
        </div>
        @include('partials.wave-divider')
    </section>

    {{-- ==================== SECTION 3: PAKET ==================== --}}
    <section id="paket" data-section="paket" class="section-anchor mt-12">
        <div class="reveal mb-5 flex items-end justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-honey-500">Lebih hemat</p>
                <h2 class="font-display text-3xl font-normal text-ink-900">Paket Hemat</h2>
            </div>
            <a href="{{ route('paket') }}" class="inline-flex items-center gap-1 text-sm font-bold text-ink-500 transition hover:text-honey-500">
                Semua Paket
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            </a>
        </div>

        @php
    $bundleCount = $homeBundles->count();
@endphp

        @if ($bundleCount > 0)
            <div id="paket-loading" class="hidden py-16">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="reveal">
                            <div class="group flex h-full flex-col overflow-hidden card-brutal card-brutal-hover">
                                <a href="#" class="relative block aspect-[4/3] overflow-hidden bg-cream-200 pointer-events-none" aria-label="Paket">
                                    <div class="skeleton h-full w-full"></div>
                                    @if ($i % 3 === 0)
                                        <span class="skeleton-text w-24 h-2 absolute left-2 top-2 rounded-full bg-honey-400 px-2 py-0.5 text-xs font-extrabold text-ink-900"></span>
                                    @endif
                                </a>
                                <div class="flex flex-1 flex-col p-3.5">
                                    <a href="#" class="pointer-events-none">
                                        <h3 class="skeleton-title text-sm font-bold leading-tight text-ink-900"></h3>
                                    </a>
                                    <p class="mt-0.5 skeleton-text w-32 text-xs text-ink-400 line-clamp-2"></p>
                                    <div class="mt-2 flex items-end justify-between gap-2">
                                        <div>
                                            @if ($i % 2 === 0)
                                                <p class="skeleton-text w-20 text-xs text-ink-400 line-through"></p>
                                            @endif
                                            <span class="skeleton-text w-16 text-sm font-extrabold text-honey-600"></span>
                                        </div>
                                        @if ($i % 2 === 0)
                                            <button type="button" disabled aria-label="Tambah ke keranjang"
                                                class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-honey-400 text-ink-900 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5 pointer-events-none"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div id="paket-grid" class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                @foreach ($homeBundles as $bundle)
                    <div style="--reveal-delay:{{ min($loop->index, 8) * 70 }}ms" class="reveal">
                        <div class="group flex h-full flex-col overflow-hidden card-brutal card-brutal-hover">
                            <a href="{{ route('bundle.show', $bundle) }}" class="relative block aspect-[4/3] overflow-hidden bg-cream-200" aria-label="{{ $bundle->name }}">
                                <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @if ($bundle->savings > 0)
                                    <span class="absolute left-2 top-2 max-w-[calc(100%-1rem)] truncate rounded-full bg-honey-400 px-2.5 py-1 text-xs font-extrabold text-ink-900 shadow-xs">
                                        Hemat {{ 'Rp' . number_format($bundle->savings, 0, ',', '.') }}
                                    </span>
                                @endif
                            </a>
                            <div class="flex flex-1 flex-col p-3.5">
                                <a href="{{ route('bundle.show', $bundle) }}" class="hover:text-honey-500">
                                    <h3 class="text-sm font-bold leading-tight text-ink-900">{{ $bundle->name }}</h3>
                                </a>
                                <p class="mt-0.5 line-clamp-2 text-xs text-ink-400">
                                    {{ $bundle->items->map(fn ($i) => $i->product?->name . ($i->qty > 1 ? ' ×' . $i->qty : ''))->join(' + ') }}
                                </p>
                                <div class="mt-2 flex items-end justify-between gap-2">
                                    <div>
                                        @if ($bundle->regular_total > $bundle->price)
                                            <p class="text-xs text-ink-400 line-through">{{ 'Rp' . number_format($bundle->regular_total, 0, ',', '.') }}</p>
                                        @endif
                                        <span class="text-sm font-extrabold text-honey-600">{{ 'Rp' . number_format($bundle->price, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($bundle->isReady())
                                        <button type="button" data-add-bundle="{{ $bundle->id }}" aria-label="Tambah {{ $bundle->name }} ke keranjang"
                                            class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-honey-400 text-ink-900 shadow-sm transition hover:bg-honey-300 active:scale-90">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5 pointer-events-none"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div id="paket-empty" class="hidden py-16 text-center">
                <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto mb-6 h-20 w-20 text-ink-300"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                    <h3 class="font-display text-2xl font-normal text-ink-900">Belum ada paket</h3>
                    <p class="mt-2 text-sm text-ink-400 max-w-xl">
                        Coba lagi nanti, atau hubungi admin untuk menambahkan paket baru.
                    </p>
                    <a href="{{ route('paket') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl border-2 border-honey-400 bg-honey-400 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-honey-300 active:scale-[0.97]">
                        Lihat Semua Paket
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>
            </div>
        @endif

        @include('partials.wave-divider')
    </section>

    {{-- CTA penutup --}}
    <section class="reveal group mt-12 rounded-2xl bg-honey-400 relative overflow-hidden p-6 text-ink-900 shadow-lg">
        <h2 class="font-display text-2xl font-normal leading-tight">Lapar? Yuk pesan sekarang!</h2>
        <p class="mt-1 text-sm text-ink-700">Pesanan langsung dikirim ke WhatsApp Admin untuk konfirmasi cepat.</p>
        <a href="{{ route('cart') }}"
            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-extrabold text-ink-900 shadow-sm transition hover:bg-cream-100">
            Lihat Keranjang
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        </a>
    </section>
@endsection
