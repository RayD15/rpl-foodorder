<div class="group flex h-full flex-col overflow-hidden card-brutal card-brutal-hover">
    <a href="{{ route('product.show', $product) }}" class="relative block aspect-[4/3] overflow-hidden bg-cream-200" aria-label="{{ $product->name }}">
        <div class="absolute inset-0 animate-pulse bg-stone-200 dark:bg-stone-700"></div>
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async" width="400" height="300"
            class="relative h-full w-full object-cover transition duration-300 group-hover:scale-105 opacity-0"
            onload="this.classList.remove('opacity-0');this.previousElementSibling.remove()">
        @if (! $product->isReady())
            <div class="absolute inset-0 grid place-items-center bg-ink-900/50">
                <span class="rounded-full bg-ink-900 px-3 py-1 text-xs font-extrabold uppercase tracking-wide text-white">Sold Out</span>
            </div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-3.5">
        <a href="{{ route('product.show', $product) }}" class="hover:text-honey-500">
            <h3 class="text-sm font-bold leading-tight text-ink-900">{{ $product->name }}</h3>
        </a>
        <p class="mt-0.5 text-xs text-ink-400">{{ $product->category->name ?? '' }}</p>
        <div class="mt-2 flex items-center justify-between gap-2">
            <span class="text-sm font-extrabold text-honey-600">{{ 'Rp' . number_format($product->price, 0, ',', '.') }}</span>
            @if ($product->isReady())
                <button type="button" data-add-to-cart="{{ $product->id }}" aria-label="Tambah {{ $product->name }} ke keranjang"
                    class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-honey-400 text-ink-900 shadow-sm transition hover:bg-honey-300 active:scale-90">
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
