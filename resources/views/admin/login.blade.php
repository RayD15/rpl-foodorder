@extends('layouts.customer')

@section('title', 'Login Admin')

@section('content')
    <div class="mx-auto mt-10 max-w-md rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
        <div class="mb-6 text-center">
            <span class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-tomato-500 text-2xl font-bold text-white shadow-[4px_4px_0_0_var(--color-ink-900)]">R</span>
            <h1 class="font-display text-3xl font-normal text-ink-900">Login Admin</h1>
            <p class="mt-1 text-sm text-ink-500">RPL2 FoodOrder</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="flex flex-col gap-3">
            @csrf
            @error('email')
                <p class="rounded-xl border border-tomato-500/30 bg-tomato-500/10 px-3 py-2 text-sm font-bold text-tomato-600">⚠️ {{ $message }}</p>
            @enderror
            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
            </label>
            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Password</span>
                <input type="password" name="password" required autocomplete="current-password"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
            </label>
            <button type="submit"
                class="mt-2 rounded-2xl border-2 border-ink-900 bg-tomato-500 px-6 py-4 text-base font-extrabold text-white shadow-[5px_5px_0_0_var(--color-ink-900)] transition hover:bg-tomato-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-ink-400">Bukan admin? <a href="{{ route('home') }}" class="font-bold text-tomato-600 hover:text-tomato-700">Kembali ke beranda</a></p>
    </div>
@endsection
