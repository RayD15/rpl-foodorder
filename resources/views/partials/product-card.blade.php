<a href="{{ route('product.show', $product) }}" class="group flex flex-col overflow-hidden card-brutal card-brutal-hover">
    <div class="relative aspect-[4/3] overflow-hidden bg-cream-200">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        @if (! $product->isReady())
            <div class="absolute inset-0 grid place-items-center bg-ink-900/50">
                <span class="rounded-full bg-ink-900 px-3 py-1 text-xs font-extrabold uppercase tracking-wide text-white">Sold Out</span>
            </div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-3.5">
        <h3 class="text-sm font-bold leading-tight text-ink-900">{{ $product->name }}</h3>
        <p class="mt-0.5 text-xs text-ink-400">{{ $product->category->name ?? '' }}</p>
        <div class="mt-2 flex items-center justify-between gap-2">
            <span class="text-sm font-extrabold text-tomato-600">{{ 'Rp' . number_format($product->price, 0, ',', '.') }}</span>
            @if (! $product->isReady())
                <span class="grid h-8 w-8 flex-shrink-0 place-items-center rounded-lg bg-cream-200 text-ink-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                </span>
            @endif
        </div>
    </div>
</a>
