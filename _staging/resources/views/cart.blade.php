@extends('layouts.customer')

@section('title', 'Keranjang')

@section('content')
    <h1 class="animate-rise mb-5 font-display text-3xl font-normal text-ink-900">Keranjang</h1>

    <div id="cart-empty" class="hidden">
        <div class="reveal py-16 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="mx-auto mb-4 h-16 w-16 text-ink-300"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
            <h2 class="font-display text-2xl font-normal text-ink-900">Keranjang kosong</h2>
            <p class="mb-6 mt-1 text-sm text-ink-400">Yuk pilih menu favoritmu dulu!</p>
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 rounded-xl border border-ink-200 bg-honey-400 text-ink-900 px-6 py-3 text-sm font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">Lihat Menu</a>
        </div>
    </div>

    <div id="cart-items" class="flex flex-col gap-4"></div>

    <div id="cart-summary-section" class="reveal mt-6 hidden rounded-3xl border-2 border-ink-200 bg-cream-100 p-5 card-brutal-hover sm:block">
        <div class="flex justify-between py-1 text-sm text-ink-500">
            <span>Subtotal</span><span id="cart-subtotal"></span>
        </div>
        <div class="flex justify-between py-1 text-sm text-ink-500">
            <span>Ongkos Kirim</span><span id="cart-shipping">Rp0</span>
        </div>
        <div class="mt-2 flex justify-between border-t border-dashed border-ink-300 pt-3 text-base font-extrabold text-ink-900">
            <span>Total</span><span id="cart-grand-total" class="text-honey-600"></span>
        </div>
        <a href="{{ route('checkout') }}" id="cart-checkout-btn"
            class="mt-4 hidden items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-400 px-6 py-4 text-base font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 active:translate-x-1 active:translate-y-1 active:shadow-none sm:flex">
            Lanjut Checkout
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        </a>
    </div>

    {{-- Sticky checkout bar (mobile): total + CTA selalu terlihat --}}
    <div id="cart-sticky-bar" class="fixed bottom-16 left-0 z-40 hidden w-full border-t border-ink-100 bg-white/95 px-4 pb-[max(0.75rem,env(safe-area-inset-bottom))] pt-3 backdrop-blur sm:hidden">
        <div class="mx-auto flex w-full max-w-2xl items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs text-ink-500">Total</p>
                <p id="cart-sticky-total" class="truncate text-lg font-extrabold text-ink-900">Rp0</p>
            </div>
            <a href="{{ route('checkout') }}"
                class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-honey-400 px-6 py-3.5 text-base font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 active:scale-[0.98]">
                Lanjut Checkout
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            </a>
        </div>
    </div>
@endsection
