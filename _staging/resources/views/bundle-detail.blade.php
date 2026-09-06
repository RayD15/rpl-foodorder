@extends('layouts.customer')

@section('title', $bundle->name)

@section('content')
    <nav class="animate-rise mb-4">
        <a href="{{ route('paket') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-ink-500 hover:text-honey-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
            Paket
        </a>
    </nav>

    <div class="animate-rise overflow-hidden rounded-3xl border-2 border-ink-200 bg-cream-100 card-brutal-hover" style="--reveal-delay:60ms">
        <div class="relative aspect-[4/3] bg-cream-200">
            <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" fetchpriority="high" decoding="async" class="h-full w-full object-cover">
            @if ($bundle->savings > 0)
                <span class="absolute left-3 top-3 rounded-full bg-honey-400 text-ink-900 px-3 py-1 text-xs font-extrabold text-ink-900 shadow-xs">
                    Hemat {{ 'Rp' . number_format($bundle->savings, 0, ',', '.') }}
                </span>
            @endif
        </div>
        <div class="p-6">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-honey-200 px-3 py-1 text-xs font-bold text-ink-800">Paket Hemat</span>
                @if (! $bundle->isReady())
                    <span class="inline-flex items-center rounded-full bg-ink-900 px-3 py-1 text-xs font-bold text-ink-900">Sold Out</span>
                @endif
            </div>
            <h1 class="mt-3 font-display text-3xl font-normal leading-tight text-ink-900">{{ $bundle->name }}</h1>
            <div class="mt-1 flex items-baseline gap-2">
                @if ($bundle->regular_total > $bundle->price)
                    <span class="text-lg text-ink-400 line-through">{{ 'Rp' . number_format($bundle->regular_total, 0, ',', '.') }}</span>
                @endif
                <span class="text-2xl font-extrabold text-honey-600">{{ 'Rp' . number_format($bundle->price, 0, ',', '.') }}</span>
            </div>
            @if ($bundle->description)
                <p class="mt-3 text-sm leading-relaxed text-ink-600">{{ $bundle->description }}</p>
            @endif

            {{-- Isi paket --}}
            <div class="mt-6">
                <p class="mb-2 text-sm font-bold text-ink-600">Isi Paket</p>
                <div class="flex flex-col gap-2">
                    @foreach ($bundle->items as $item)
                        <div class="flex items-center gap-3 rounded-2xl border-2 border-ink-200 bg-cream-50 p-3">
                            <img src="{{ $item->product?->image_url }}" alt="{{ $item->product?->name }}" loading="lazy" decoding="async" width="48" height="48" class="h-12 w-12 flex-shrink-0 rounded-xl object-cover bg-cream-200">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-ink-900">{{ $item->product?->name }}</p>
                                <p class="text-xs text-ink-500">× {{ $item->qty }}</p>
                            </div>
                            <span class="text-sm font-bold text-ink-700">{{ 'Rp' . number_format(($item->product?->price ?? 0) * $item->qty, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($bundle->isReady())
            <div class="mt-6">
                <p class="mb-2 text-sm font-bold text-ink-600">Jumlah Paket</p>
                <div class="inline-flex items-center gap-4 rounded-2xl border-2 border-ink-200 bg-cream-50 p-2">
                    <button type="button" id="qty-dec" class="grid h-10 w-10 place-items-center rounded-xl bg-cream-200 text-lg font-bold text-ink-700 transition hover:bg-ink-200">−</button>
                    <span id="qty-val" class="w-8 text-center text-lg font-extrabold text-ink-900">1</span>
                    <button type="button" id="qty-inc" class="grid h-10 w-10 place-items-center rounded-xl bg-honey-400 text-lg font-bold text-ink-900 shadow-xs transition hover:bg-honey-300 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">+</button>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" id="bundle-add-btn" data-add-bundle="{{ $bundle->id }}" data-qty="1"
                    class="col-span-2 inline-flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-400 text-ink-900 px-6 py-4 text-base font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-sm active:translate-x-1 active:translate-y-1 active:shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                    Tambah ke Keranjang
                </button>
                <a href="{{ route('paket') }}"
                    class="col-span-2 flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-cream-100 px-6 py-3.5 text-sm font-extrabold text-ink-800 shadow-sm transition hover:bg-cream-200 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                    Lanjut ke Menu
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>
            @else
            <div class="mt-6 rounded-2xl border-2 border-ink-200 bg-cream-50 p-4 text-center">
                <p class="text-sm font-extrabold text-ink-500">Maaf, paket ini sedang <span class="text-ink-900">Sold Out</span>.</p>
                <a href="{{ route('paket') }}"
                    class="mt-3 inline-flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-400 text-ink-900 px-6 py-3 text-sm font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                    Lihat Paket Lain
                </a>
            </div>
            @endif
        </div>
    </div>

    @if ($related->count())
        <section class="reveal mt-9">
            <h2 class="mb-3 font-display text-xl font-normal text-ink-900">Paket lainnya</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach ($related as $b)
                    <div class="group flex flex-col overflow-hidden card-brutal card-brutal-hover">
                        <a href="{{ route('bundle.show', $b) }}" class="relative block aspect-[4/3] overflow-hidden bg-cream-200" aria-label="{{ $b->name }}">
                            <img src="{{ $b->image_url }}" alt="{{ $b->name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @if ($b->savings > 0)
                                <span class="absolute left-2 top-2 max-w-[calc(100%-1rem)] truncate rounded-full bg-honey-400 px-2.5 py-1 text-xs font-extrabold text-ink-900 shadow-xs">
                                    Hemat {{ 'Rp' . number_format($b->savings, 0, ',', '.') }}
                                </span>
                            @endif
                        </a>
                        <div class="flex flex-1 flex-col p-3.5">
                            <a href="{{ route('bundle.show', $b) }}" class="hover:text-honey-500">
                                <h3 class="text-sm font-bold leading-tight text-ink-900">{{ $b->name }}</h3>
                            </a>
                            <p class="mt-0.5 line-clamp-2 text-xs text-ink-400">
                                {{ $b->items->map(fn ($i) => $i->product?->name . ($i->qty > 1 ? ' ×' . $i->qty : ''))->join(' + ') }}
                            </p>
                            <div class="mt-2 flex items-end justify-between gap-2">
                                <div>
                                    @if ($b->regular_total > $b->price)
                                        <p class="text-xs text-ink-400 line-through">{{ 'Rp' . number_format($b->regular_total, 0, ',', '.') }}</p>
                                    @endif
                                    <span class="text-sm font-extrabold text-honey-600">{{ 'Rp' . number_format($b->price, 0, ',', '.') }}</span>
                                </div>
                                @if ($b->isReady())
                                    <button type="button" data-add-bundle="{{ $b->id }}" aria-label="Tambah {{ $b->name }} ke keranjang"
                                        class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-honey-400 text-ink-900 shadow-sm transition hover:bg-honey-300 active:scale-90">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5 pointer-events-none"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
