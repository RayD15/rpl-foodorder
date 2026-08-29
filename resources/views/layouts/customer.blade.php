<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RPL2 FoodOrder') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.APP = {
            products: {!! $catalogJson !!},
            bundles: {!! $bundlesJson !!},
            whatsapp: @json($whatsappNumber),
        };
    </script>
</head>
<body class="min-h-screen overflow-x-hidden bg-cream-50 text-ink-900 antialiased">

    <header class="sticky top-0 z-40 border-b border-ink-200/70 bg-cream-50/90 backdrop-blur">
        <div class="mx-auto flex max-w-2xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-lg font-extrabold tracking-tight">RPL2 <span class="font-display font-normal text-tomato-600">FoodOrder</span></span>
            </a>
            <nav class="flex items-center gap-1">
                <a href="{{ route('home') }}" class="grid h-10 w-10 place-items-center rounded-xl text-ink-600 transition hover:bg-cream-100 hover:text-tomato-600" aria-label="Beranda">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" /></svg>
                </a>
                <a href="{{ route('menu') }}" class="grid h-10 w-10 place-items-center rounded-xl text-ink-600 transition hover:bg-cream-100 hover:text-tomato-600" aria-label="Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-7.5 7.5v-6a2.25 2.25 0 0 1 2.25-2.25h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 0-.75.75v2.25m-6 0h6m-6 0V9a2.25 2.25 0 0 1 2.25-2.25h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 0-.75.75v2.25" /></svg>
                </a>
                <a href="{{ route('paket') }}" class="grid h-10 w-10 place-items-center rounded-xl text-ink-600 transition hover:bg-cream-100 hover:text-tomato-600" aria-label="Paket">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-2xl px-4 pb-24 pt-6">
        @yield('content')
    </main>

    {{-- Bottom Navbar (Mobile Only) --}}
    <nav class="fixed bottom-0 left-0 z-40 grid w-full grid-cols-4 border-t-2 border-ink-200 bg-cream-100/95 pb-safe pb-2 shadow-[0_-2px_8px_-2px_rgba(0,0,0,0.05)] backdrop-blur sm:hidden">
        <a href="{{ route('home') }}"
            class="flex flex-col items-center justify-center gap-1 py-2 text-ink-600 hover:text-tomato-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" /></svg>
            <span class="text-xs font-bold">Beranda</span>
        </a>
        <a href="{{ route('menu') }}"
            class="flex flex-col items-center justify-center gap-1 py-2 text-ink-600 hover:text-tomato-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-7.5 7.5v-6a2.25 2.25 0 0 1 2.25-2.25h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 0-.75.75v2.25m-6 0h6m-6 0V9a2.25 2.25 0 0 1 2.25-2.25h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 0-.75.75v2.25" /></svg>
            <span class="text-xs font-bold">Menu</span>
        </a>
        <a href="{{ route('paket') }}"
            class="flex flex-col items-center justify-center gap-1 py-2 text-ink-600 hover:text-tomato-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
            <span class="text-xs font-bold">Paket</span>
        </a>
        <a href="{{ route('cart') }}"
            class="relative flex flex-col items-center justify-center gap-1 py-2 text-ink-600 hover:text-tomato-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
            <span class="text-xs font-bold">Keranjang</span>
            <span id="bottom-cart-count" class="absolute -top-1 -right-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-tomato-500 px-1 text-xs font-extrabold text-white">0</span>
        </a>
    </nav>

    <div id="toast" class="fixed left-1/2 top-4 z-50 hidden -translate-x-1/2 rounded-2xl border-2 border-ink-900 bg-honey-300 px-4 py-2 text-sm font-bold text-ink-900 shadow-[4px_4px_0_0_var(--color-ink-900)]"></div>
</body>
</html>