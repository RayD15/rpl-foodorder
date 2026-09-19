@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <h1 class="font-display text-xl font-normal text-ink-900 sm:text-2xl lg:text-3xl">Dashboard</h1>
        <p class="mt-1 text-sm text-ink-500">Selamat datang kembali, TamsisFood Admin 👋</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-2xl border border-ink-100 bg-white p-4 sm:p-5 shadow-sm">
            <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-honey-400/15">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-honey-500"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247-2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504 1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
            </div>
            <p class="mb-1 text-xs text-ink-400 sm:text-sm">Total Produk</p>
            <p class="font-display text-xl font-normal text-ink-900 sm:text-2xl lg:text-3xl">{{ $stats['products'] }}</p>
        </div>
        <div class="rounded-2xl border border-ink-100 bg-white p-4 sm:p-5 shadow-sm">
            <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-leaf-500/15">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-leaf-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="mb-1 text-xs text-ink-400 sm:text-sm">Produk Aktif</p>
            <p class="font-display text-xl font-normal text-leaf-600 sm:text-2xl lg:text-3xl">{{ $stats['active'] }}</p>
        </div>
        <div class="rounded-2xl border border-ink-100 bg-white p-4 sm:p-5 shadow-sm">
            <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-xl bg-ink-900/10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-ink-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
            </div>
            <p class="mb-1 text-xs text-ink-400 sm:text-sm">Kategori</p>
            <p class="font-display text-xl font-normal text-ink-900 sm:text-2xl lg:text-3xl">{{ $stats['categories'] }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-ink-100 bg-white p-5 sm:p-6 shadow-sm">
        <h2 class="font-display text-lg font-normal text-ink-900 sm:text-xl lg:text-2xl">Aksi Cepat</h2>
        <p class="mt-2 mb-4 text-xs text-ink-400 sm:text-sm">Pesanan dikelola langsung melalui WhatsApp.</p>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-honey-500 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-honey-600 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Produk
            </a>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-ink-200 bg-cream-100 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-bold text-ink-800 transition hover:bg-cream-200 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                Lihat Semua Produk
            </a>
            <a href="{{ route('admin.settings.edit') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-ink-200 bg-cream-100 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-bold text-ink-800 transition hover:bg-cream-200 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                Pengaturan WA
            </a>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-ink-100 bg-white p-5 sm:p-6 shadow-sm">
        <h2 class="font-display text-lg font-normal text-ink-900 sm:text-xl lg:text-2xl">Info</h2>
        <div class="mt-4 space-y-4">
            <div class="flex items-start gap-3 rounded-xl bg-cream-50 p-3 sm:p-4">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-honey-400/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-honey-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-semibold text-ink-800">Cara Kerja</p>
                    <p class="text-xs text-ink-500 sm:text-sm">Customer memilih produk, keranjang disimpan di browser, lalu pesan via WhatsApp Admin.</p>
                </div>
            </div>
            <div class="flex items-start gap-3 rounded-xl bg-cream-50 p-3 sm:p-4">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-leaf-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-leaf-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-semibold text-ink-800">Tanpa Login</p>
                    <p class="text-xs text-ink-500 sm:text-sm">Customer tidak perlu login. Pesanan langsung masuk ke WhatsApp admin.</p>
                </div>
            </div>
        </div>
    </div>
@endsection