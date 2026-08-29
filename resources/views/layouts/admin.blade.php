<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin RPL2 FoodOrder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream-50 text-ink-900 antialiased">

    <div class="flex min-h-screen">
        <aside class="hidden w-64 flex-shrink-0 flex-col border-r border-ink-200 bg-cream-100 p-5 md:flex">
            <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2">
                <span class="text-base font-extrabold text-ink-900">Admin <span class="font-display font-normal text-tomato-600">Panel</span></span>
            </a>

            <nav class="flex flex-1 flex-col gap-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-tomato-500 text-white shadow-[2px_2px_0_0_var(--color-ink-900)]' : 'text-ink-600 hover:bg-cream-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 inline h-4 w-4 -mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('admin.products.*') ? 'bg-tomato-500 text-white shadow-[2px_2px_0_0_var(--color-ink-900)]' : 'text-ink-600 hover:bg-cream-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 inline h-4 w-4 -mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" /></svg>
                    Produk
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('admin.categories.*') ? 'bg-tomato-500 text-white shadow-[2px_2px_0_0_var(--color-ink-900)]' : 'text-ink-600 hover:bg-cream-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 inline h-4 w-4 -mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                    Kategori
                </a>
                <a href="{{ route('admin.bundles.index') }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('admin.bundles.*') ? 'bg-tomato-500 text-white shadow-[2px_2px_0_0_var(--color-ink-900)]' : 'text-ink-600 hover:bg-cream-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 inline h-4 w-4 -mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                    Paket Hemat
                </a>
                <a href="{{ route('admin.settings.edit') }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('admin.settings.*') ? 'bg-tomato-500 text-white shadow-[2px_2px_0_0_var(--color-ink-900)]' : 'text-ink-600 hover:bg-cream-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 inline h-4 w-4 -mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>
                    Pengaturan
                </a>
            </nav>

            <div class="border-t border-ink-200 pt-4">
                <p class="px-4 text-sm font-bold text-ink-900">{{ Auth::guard('admin')->user()->name }}</p>
                <p class="px-4 text-xs text-ink-400">{{ Auth::guard('admin')->user()->email }}</p>
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-xl px-4 py-2.5 text-left text-sm font-semibold text-tomato-600 hover:bg-tomato-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="mr-2 inline h-4 w-4 -mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 border-b border-ink-200 bg-cream-50/90 backdrop-blur md:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <span class="font-extrabold text-ink-900">Admin Panel</span>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-lg border border-tomato-500/30 bg-tomato-500/10 px-3 py-1.5 text-xs font-bold text-tomato-600 transition hover:bg-tomato-500/20">Logout</button>
                    </form>
                </div>
                <nav class="grid grid-cols-5 gap-1.5 px-4 pb-3">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-2 py-2 text-center text-xs font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-tomato-500 text-white' : 'bg-cream-200 text-ink-700' }}">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="rounded-lg px-2 py-2 text-center text-xs font-semibold {{ request()->routeIs('admin.products.*') ? 'bg-tomato-500 text-white' : 'bg-cream-200 text-ink-700' }}">Produk</a>
                    <a href="{{ route('admin.categories.index') }}" class="rounded-lg px-2 py-2 text-center text-xs font-semibold {{ request()->routeIs('admin.categories.*') ? 'bg-tomato-500 text-white' : 'bg-cream-200 text-ink-700' }}">Kategori</a>
                    <a href="{{ route('admin.bundles.index') }}" class="rounded-lg px-2 py-2 text-center text-xs font-semibold {{ request()->routeIs('admin.bundles.*') ? 'bg-tomato-500 text-white' : 'bg-cream-200 text-ink-700' }}">Paket</a>
                    <a href="{{ route('admin.settings.edit') }}" class="rounded-lg px-2 py-2 text-center text-xs font-semibold {{ request()->routeIs('admin.settings.*') ? 'bg-tomato-500 text-white' : 'bg-cream-200 text-ink-700' }}">Pengaturan</a>
                </nav>
            </header>

            <main class="flex-1 px-4 py-6 md:px-8">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border-2 border-leaf-500 bg-leaf-400/10 px-4 py-3 text-sm font-semibold text-leaf-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="mr-1.5 inline h-4 w-4 -mt-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border-2 border-tomato-500 bg-tomato-500/10 px-4 py-3 text-sm font-semibold text-tomato-600">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="mx-auto max-w-5xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>