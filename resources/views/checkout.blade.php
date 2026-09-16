@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
    <h1 class="animate-rise mb-5 font-display text-3xl font-normal text-ink-900">Checkout</h1>

    <div class="animate-rise rounded-3xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover" style="--reveal-delay:80ms">
        <p class="mb-3 text-xs font-extrabold uppercase tracking-widest text-ink-500">Informasi Pemesan</p>

        <form id="checkout-form" class="flex flex-col gap-4">
            @csrf
            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Nama</span>
                <input id="customer-name" name="name" type="text" placeholder="Nama kamu" required autocomplete="name"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
            </label>
            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Kelas</span>
                <input id="customer-class" name="class" type="text" placeholder="Contoh: XI RPL 2" required
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
            </label>
            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Catatan <span class="font-medium text-ink-400">(opsional)</span></span>
                <textarea id="customer-notes" name="notes" rows="2" placeholder="Tambahan perasaan, mis. topping / tidak pedas"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20"></textarea>
            </label>

            <div class="mt-1 rounded-2xl border-2 border-dashed border-ink-300 bg-cream-50 p-4 text-sm">
                <p class="mb-2 text-xs font-extrabold uppercase tracking-widest text-ink-500">Pesanan</p>
                <div id="checkout-items" class="divide-y divide-dashed divide-ink-300"></div>
                <div class="flex justify-between pt-3 text-base font-extrabold text-ink-900">
                    <span>Total</span>
                    <span id="checkout-total" class="text-honey-600"></span>
                </div>
            </div>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-400 text-ink-900 px-6 py-5 text-base font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-sm active:translate-x-1 active:translate-y-1 active:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" /></svg>
                PESAN VIA WHATSAPP
            </button>
            <p class="text-center text-xs text-ink-400">Pesanan akan dikirim otomatis ke WhatsApp Admin untuk dikonfirmasi.</p>
        </form>
    </div>

    {{-- Modal Konfirmasi Checkout --}}
    <div id="checkout-confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirm-title"
        class="fixed inset-0 z-50 hidden items-end justify-center bg-black/50 p-4 pb-[max(1rem,env(safe-area-inset-bottom))] sm:items-center sm:p-6"
        aria-hidden="true">
        <div class="animate-rise flex max-h-[85dvh] w-full max-w-md flex-col rounded-3xl border-2 border-ink-200 bg-cream-100 p-6 shadow-2xl"
            style="--reveal-delay:0ms">
            <h2 id="confirm-title" class="font-display mb-4 flex-shrink-0 text-2xl font-normal text-ink-900">Konfirmasi Pesanan</h2>

            <div class="mb-4 flex-shrink-0 space-y-2 divide-y divide-dashed divide-ink-200">
                <div class="flex justify-between gap-3 text-sm text-ink-800">
                    <span class="flex-shrink-0">Nama</span><span id="confirm-name" class="min-w-0 break-words text-right font-bold"></span>
                </div>
                <div class="flex justify-between gap-3 text-sm text-ink-800">
                    <span class="flex-shrink-0">Kelas</span><span id="confirm-class" class="min-w-0 break-words text-right font-bold"></span>
                </div>
                <div class="flex justify-between gap-3 text-sm text-ink-800">
                    <span class="flex-shrink-0">Catatan</span><span id="confirm-notes" class="min-w-0 break-words text-right font-bold italic"></span>
                </div>
            </div>  

            <div class="mb-4 min-h-0 flex-1 overflow-y-auto text-sm">
                <p class="mb-2 text-xs font-extrabold uppercase tracking-widest text-ink-500">Item Pesanan</p>
                <div id="confirm-items" class="space-y-1.5 text-sm text-ink-800"></div>
                <div class="flex justify-between gap-3 pt-3 text-base font-extrabold text-ink-900">
                    <span>Total</span>
                    <span id="confirm-total" class="text-honey-600"></span>
                </div>
            </div>

            <div class="flex flex-shrink-0 gap-3">
                <button id="confirm-cancel" type="button"
                    class="min-h-11 flex-1 rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm font-bold text-ink-700 transition hover:bg-ink-100 active:scale-[0.98]">
                    Batal
                </button>
                <button id="confirm-submit" type="button"
                    class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-400 px-4 py-3 text-sm font-extrabold text-ink-900 shadow-sm transition hover:bg-honey-300 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M6.99 11.999c0-.55.45-1 1-1h4.59l3.29-3.29c-.64-.32-1.37-.5-2.13-.5-1.93 0-3.51 1.35-3.9 3.16l-.01.13v.01c0 .55.45 1 1 1h4.59l-3.29 3.29c-.64-.32-1.37-.5-2.13-.5-1.93 0-3.51-1.35-3.9-3.16l-.01-.13v-.01z" /></svg>
                    Buka WhatsApp
                </button>
            </div>
        </div>
    </div>
@endsection
