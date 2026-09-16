@extends('layouts.customer')

@section('title', 'Paket')
@section('meta_description', 'Paket hemat TamsisFood Jakarta — kombinasi makanan dan minuman harga spesial. Pesan langsung via WhatsApp.')

@section('content')
    <div class="animate-rise mb-5 flex items-center justify-between">
        <h1 class="font-display text-3xl font-normal text-ink-900">Paket Hemat</h1>
        <span class="rounded-full bg-honey-300 px-3 py-1 text-xs font-extrabold text-ink-900">Hemat!</span>
    </div>

    @if ($bundles->count())
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            @foreach ($bundles as $bundle)
                <div class="group flex flex-col overflow-hidden card-brutal card-brutal-hover">
                    <a href="{{ route('bundle.show', $bundle->id) }}" class="relative block aspect-[4/3] overflow-hidden bg-cream-200" aria-label="{{ $bundle->name }}">
                        <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        @if ($bundle->savings > 0)
                            <span class="absolute left-2 top-2 max-w-[calc(100%-1rem)] truncate rounded-full bg-honey-400 px-2.5 py-1 text-xs font-extrabold text-ink-900 shadow-xs">
                                Hemat {{ 'Rp' . number_format($bundle->savings, 0, ',', '.') }}
                            </span>
                        @endif
                    </a>
                    <div class="flex flex-1 flex-col p-3.5">
                        <a href="{{ route('bundle.show', $bundle->id) }}" class="hover:text-honey-500">
                            <h3 class="text-sm font-bold leading-tight text-ink-900">{{ $bundle->name }}</h3>
                        </a>
                        <p class="mt-0.5 line-clamp-2 text-xs text-ink-400">
                            {{ collect($bundle->items->map(fn ($i) => ['name' => $i->product?->name ?? 'Produk dihapus', 'qty' => $i->qty]))->map(fn ($i) => $i['name'] . ($i['qty'] > 1 ? ' ×' . $i['qty'] : ''))->join(' + ') }}
                        </p>
                        <div class="mt-2 flex items-end justify-between gap-2">
                            <div>
                                @if ($bundle->regular_total > $bundle->price)
                                    <p class="text-xs text-ink-400 line-through">{{ 'Rp' . number_format($bundle->regular_total, 0, ',', '.') }}</p>
                                @endif
                                <span class="text-sm font-extrabold text-honey-600">{{ 'Rp' . number_format($bundle->price, 0, ',', '.') }}</span>
                            </div>
                            <button type="button" data-add-bundle="{{ $bundle->id }}" aria-label="Tambah {{ $bundle->name }} ke keranjang"
                                class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-honey-400 text-ink-900 shadow-sm transition hover:bg-honey-300 active:scale-90">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5 pointer-events-none"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="reveal py-16 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="mx-auto mb-4 h-16 w-16 text-ink-300"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
            <h2 class="font-display text-2xl font-normal text-ink-900">Belum ada paket</h2>
            <p class="mt-1 text-sm text-ink-400">Coba lagi nanti.</p>
        </div>
    @endif
@endsection
