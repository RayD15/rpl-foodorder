@extends('layouts.customer')

@section('title', 'Home')

@section('content')
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
            data-hero-slideshow
            data-slide-interval="4200"
            data-slides='@json($slidesData)'
            class="relative flex h-[78vh] min-h-[460px] items-end overflow-hidden bg-ink-900 text-white sm:items-center"
            style="width: 100vw; margin-left: calc(50% - 50vw);">
            @foreach ($heroProducts as $product)
                <div data-hero-slide="{{ $loop->index }}"
                    class="absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $loop->index === 0 ? 'opacity-100' : 'opacity-0' }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                </div>
            @endforeach

            <div class="absolute inset-0 bg-gradient-to-t from-ink-950/95 via-ink-950/40 to-ink-950/10 sm:bg-gradient-to-r"></div>

            <div class="relative z-10 w-full max-w-2xl px-6 pb-20 pt-16 sm:pt-0 sm:pb-0">
                <h1 style="--reveal-delay:80ms" class="animate-rise mt-4 max-w-lg font-display text-4xl font-normal leading-tight sm:text-6xl">
                    Mau pesan apa hari ini?
                </h1>
                <p style="--reveal-delay:160ms" class="animate-rise mt-3 max-w-sm text-sm text-cream-100/90 sm:text-base">
                    Pilih menu favoritmu, langsung pesan lewat WhatsApp Admin.
                </p>
                <div style="--reveal-delay:240ms" class="animate-rise mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ route('menu') }}"
                        class="inline-flex items-center gap-2 rounded-xl border-2 border-ink-900 bg-honey-400 px-6 py-3 text-sm font-extrabold text-ink-900 shadow-[4px_4px_0_0_rgba(0,0,0,0.5)] transition hover:bg-honey-300 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                        Lihat Menu
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    </a>
                    <a id="hero-current-product" href="{{ route('product.show', $heroProducts->first()) }}"
                        class="inline-flex items-center gap-2 rounded-xl border-2 border-cream-100/40 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">
                        <span id="hero-current-name" class="underline decoration-honey-400 decoration-2 underline-offset-4">{{ $heroProducts->first()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                    </a>
                </div>
            </div>

            <div data-hero-dots class="absolute bottom-6 left-1/2 z-10 flex -translate-x-1/2 items-center gap-2">
                @foreach ($heroProducts as $product)
                    <button type="button" data-hero-dot="{{ $loop->index }}" aria-label="Slide {{ $loop->index + 1 }}"
                        class="h-2 rounded-full transition-all duration-300 {{ $loop->index === 0 ? 'w-6 bg-honey-400' : 'w-2 bg-white/50 hover:bg-white/80' }}"></button>
                @endforeach
            </div>
        </section>
    @else
        <section class="text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-honey-200 px-3 py-1 text-xs font-bold text-ink-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
                Jajan di sekolah, makin gampang!
            </span>
            <h1 class="mt-4 font-display text-4xl font-normal leading-tight text-ink-900 sm:text-5xl">
                Mau pesan apa hari ini?
            </h1>
            <p class="mx-auto mt-3 max-w-xs text-sm text-ink-600">
                Pilih menu, checkout, langsung pesan via WhatsApp Admin.
            </p>

            <a href="{{ route('menu') }}"
                class="mt-6 inline-flex w-full items-center gap-3 rounded-2xl border-2 border-ink-200 bg-cream-100 px-5 py-4 text-left text-ink-500 transition hover:border-tomato-500 hover:text-tomato-600 active:scale-[0.99] sm:max-w-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 flex-shrink-0 text-ink-400"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <span>Cari makanan atau minuman...</span>
            </a>
        </section>
    @endif

    <section class="reveal mt-9 rounded-3xl border-2 border-ink-900 bg-tomato-500 p-6 text-white shadow-[6px_6px_0_0_var(--color-ink-900)]">
        <h2 class="font-display text-2xl font-normal leading-tight">Lapar? Yuk pesan sekarang!</h2>
        <p class="mt-1 text-sm text-tomato-100">Pesanan langsung dikirim ke WhatsApp Admin untuk konfirmasi cepat.</p>
        <a href="{{ route('menu') }}"
            class="mt-4 inline-flex items-center gap-2 rounded-xl border-2 border-ink-900 bg-honey-400 px-6 py-3 text-sm font-extrabold text-ink-900 shadow-[4px_4px_0_0_var(--color-ink-900)] transition hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none">
            Lihat Menu
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        </a>
    </section>
@endsection
