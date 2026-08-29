@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="font-display text-3xl font-normal text-ink-900">Dashboard</h1>
        <p class="text-sm text-ink-500">Selamat datang kembali, {{ Auth::guard('admin')->user()->name }} 👋</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
            <p class="text-sm font-semibold text-ink-500">Total Produk</p>
            <p class="mt-1 font-display text-4xl font-normal text-ink-900">{{ $stats['products'] }}</p>
        </div>
        <div class="rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
            <p class="text-sm font-semibold text-ink-500">Produk Aktif</p>
            <p class="mt-1 font-display text-4xl font-normal text-leaf-600">{{ $stats['active'] }}</p>
        </div>
        <div class="rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
            <p class="text-sm font-semibold text-ink-500">Kategori</p>
            <p class="mt-1 font-display text-4xl font-normal text-ink-900">{{ $stats['categories'] }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
        <h2 class="font-display text-2xl font-normal text-ink-900">Aksi Cepat</h2>
        <p class="mb-4 mt-1 text-sm text-ink-500">Pesanan dikelola langsung melalui WhatsApp, bukan di dashboard ini.</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 rounded-xl border-2 border-ink-900 bg-tomato-500 px-5 py-2.5 text-sm font-extrabold text-white shadow-[3px_3px_0_0_var(--color-ink-900)] transition hover:bg-tomato-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Produk
            </a>
            <a href="{{ route('admin.settings.edit') }}" class="inline-flex items-center gap-1.5 rounded-xl border-2 border-ink-900 bg-cream-100 px-5 py-2.5 text-sm font-extrabold text-ink-800 shadow-[3px_3px_0_0_var(--color-ink-900)] transition hover:bg-cream-200 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>
                Atur Nomor WA
            </a>
        </div>
    </div>
@endsection
