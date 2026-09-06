<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- PWA / mobile chrome --}}
    <meta name="theme-color" content="#faf8f5" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#17110c" media="(prefers-color-scheme: dark)">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="TamsisFood">
    <link rel="icon" href="{{ asset('logo/image-removebg-preview.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('logo/image-removebg-preview.png') }}" sizes="64x64" type="image/png">
    <link rel="icon" href="{{ asset('logo/image-removebg-preview.png') }}" sizes="128x128" type="image/png">
    <link rel="icon" href="{{ asset('logo/image-removebg-preview.png') }}" sizes="256x256" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logo/image-removebg-preview.png') }}" sizes="192x192">
    @stack('head')

    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'TamsisFood') — Pesan Makanan Online di Jakarta</title>
    <meta name="description" content="@yield('meta_description', 'TamsisFood — Pesan makanan dan minuman online di Jakarta dengan mudah. Pilih menu favoritmu, pesan langsung via WhatsApp.')">
    <meta name="keywords" content="TamsisFood, pesan makanan online Jakarta, pesan minuman online Jakarta, food order Jakarta, makanan, minuman">
    <meta name="author" content="TamsisFood">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TamsisFood">
    <meta property="og:title" content="@yield('title', 'TamsisFood') — Pesan Makanan Online di Jakarta">
    <meta property="og:description" content="@yield('meta_description', 'TamsisFood — Pesan makanan dan minuman online di Jakarta dengan mudah.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/placeholder.svg'))">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'TamsisFood') — Pesan Makanan Online di Jakarta">
    <meta name="twitter:description" content="@yield('meta_description', 'TamsisFood — Pesan makanan dan minuman online di Jakarta dengan mudah.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/placeholder.svg'))">

    {{-- Structured Data --}}
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'TamsisFood',
            'url' => url('/'),
            'description' => 'TamsisFood — Pesan makanan dan minuman online di Jakarta dengan mudah.',
            'inLanguage' => 'id-ID',
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@400;500;600;700;800&family=Merriweather:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@400;500;600;700;800&family=Merriweather:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@400;500;600;700;800&family=Merriweather:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>
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

    @php
        $currentRoute = request()->route()->getName();
        $isHome = $currentRoute === 'home';
        // Di halaman home: link section pakai anchor (#menu, #paket) + smooth scroll.
        // Di halaman lain: kembali ke home lalu ke section yang dituju.
        $homeBase = $isHome ? '' : route('home');
    @endphp

    {{-- Top Navbar (sticky, tidak pernah hilang saat scroll) --}}
    <header class="sticky top-0 z-40 border-b border-ink-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-2xl items-center justify-between px-4 py-3">
            <a href="{{ $isHome ? '#hero' : route('home') }}" @if ($isHome) data-scroll-to="#hero" @endif class="flex items-center gap-2">
                <img src="{{ asset('logo/image-removebg-preview.png') }}" alt="TamsisFood Logo" class="h-10 w-10">
                <span class="text-lg font-extrabold tracking-tight flex items-center gap-1">
    <span class="font-display" style="color:#000000;">TAMSIS</span><span class="font-soft-serif font-normal text-honey-600">FOOD</span>
</span>
            </a>
            <nav class="hidden md:flex items-center gap-1" data-section-nav>
                <a href="{{ $homeBase }}#hero" @if ($isHome) data-scroll-to="#hero" @endif data-nav="hero" class="grid h-11 w-11 place-items-center rounded-xl transition {{ $isHome ? 'bg-honey-50/10 text-honey-800 hover:bg-honey-50/20 hover:text-honey-900' : 'text-ink-400/50 hover:text-ink-600 hover:bg-cream-50/5' }}">
                    <i data-lucide="house" class="h-5 w-5 pointer-events-none"></i>
                    <span class="text-[10px] pt-1" data-nav-label>Beranda</span>
                </a>
                <a href="{{ $homeBase }}#menu" @if ($isHome) data-scroll-to="#menu" @endif data-nav="menu" class="grid h-11 w-11 place-items-center rounded-xl transition {{ $currentRoute === 'menu' || $currentRoute === 'product.show' ? 'bg-honey-50/10 text-honey-800 hover:bg-honey-50/20 hover:text-honey-900' : ($isHome ? 'text-ink-400/50 hover:text-ink-600 hover:bg-cream-50/5' : 'text-ink-400/50 hover:text-ink-600 hover:bg-cream-50/5') }}">
                    <i data-lucide="utensils" class="h-5 w-5 pointer-events-none"></i>
                    <span class="text-[10px] pt-1" data-nav-label>Menu</span>
                </a>
                <a href="{{ $homeBase }}#paket" @if ($isHome) data-scroll-to="#paket" @endif data-nav="paket" class="grid h-11 w-11 place-items-center rounded-xl transition {{ $currentRoute === 'paket' || $currentRoute === 'bundle.show' ? 'bg-honey-50/10 text-honey-800 hover:bg-honey-50/20 hover:text-honey-900' : ($isHome ? 'text-ink-400/50 hover:text-ink-600 hover:bg-cream-50/5' : 'text-ink-400/50 hover:text-ink-600 hover:bg-cream-50/5') }}">
                    <i data-lucide="package" class="h-5 w-5 pointer-events-none"></i>
                    <span class="text-[10px] pt-1" data-nav-label>Paket</span>
                </a>
                <a href="{{ route('cart') }}" class="relative grid h-11 w-11 place-items-center rounded-xl transition {{ $currentRoute === 'cart' || $currentRoute === 'checkout' ? 'bg-honey-50/10 text-honey-800 hover:bg-honey-50/20 hover:text-honey-900' : 'text-ink-400/50 hover:text-ink-600 hover:bg-cream-50/5' }}" aria-label="Keranjang">
                    <i data-lucide="shopping-cart" class="h-5 w-5 pointer-events-none"></i>
                    <span id="desktop-cart-count" class="absolute -top-1 -right-1 hidden h-[18px] min-w-[18px] items-center justify-center rounded-full bg-honey-500 px-1 text-[11px] font-extrabold text-white">0</span>
                    <span class="text-[10px] pt-1" data-nav-label>Keranjang</span>
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-2xl px-4 pb-24 pt-4 sm:pb-6 sm:pt-6">
        @yield('content')
    </main>

    {{-- Bottom Navbar (Mobile Only, fixed, tidak pernah hilang saat scroll) --}}
    <nav class="fixed bottom-0 left-0 z-40 grid w-full grid-cols-4 border-t border-ink-100 bg-white/95 pb-safe shadow-[0_-1px_3px_-1px_rgba(0,0,0,0.06)] backdrop-blur md:hidden" data-section-nav>
        <a href="{{ $homeBase }}#hero" @if ($isHome) data-scroll-to="#hero" @endif data-nav="hero" class="flex flex-col items-center justify-center gap-1 py-2 transition {{ $isHome ? '' : 'text-ink-400 hover:text-honey-500' }}">
            <i data-lucide="house" class="h-5 w-5 pointer-events-none"></i>
            <span class="text-[10px]" data-nav-label>Beranda</span>
        </a>
        <a href="{{ $homeBase }}#menu" @if ($isHome) data-scroll-to="#menu" @endif data-nav="menu" class="flex flex-col items-center justify-center gap-1 py-2 transition {{ $currentRoute === 'menu' || $currentRoute === 'product.show' ? 'text-honey-500' : ($isHome ? '' : 'text-ink-400 hover:text-honey-500') }}">
            <i data-lucide="utensils" class="h-5 w-5 pointer-events-none"></i>
            <span class="text-[10px]" data-nav-label>Menu</span>
        </a>
        <a href="{{ $homeBase }}#paket" @if ($isHome) data-scroll-to="#paket" @endif data-nav="paket" class="flex flex-col items-center justify-center gap-1 py-2 transition {{ $currentRoute === 'paket' || $currentRoute === 'bundle.show' ? 'text-honey-500' : ($isHome ? '' : 'text-ink-400 hover:text-honey-500') }}">
            <i data-lucide="package" class="h-5 w-5 pointer-events-none"></i>
            <span class="text-[10px]" data-nav-label>Paket</span>
        </a>
        <a href="{{ route('cart') }}" class="relative flex flex-col items-center justify-center gap-1 py-2 transition {{ $currentRoute === 'cart' || $currentRoute === 'checkout' ? 'text-honey-500' : 'text-ink-400 hover:text-honey-500' }}">
            <span class="relative pointer-events-none"><i data-lucide="shopping-cart" class="h-5 w-5 pointer-events-none"></i><span id="bottom-cart-count" class="absolute -top-1.5 -right-1.5 hidden h-[18px] min-w-[18px] items-center justify-center rounded-full bg-tomato-500 px-1 text-[11px] font-extrabold text-white">0</span></span>
            <span class="text-[10px] {{ $currentRoute === 'cart' || $currentRoute === 'checkout' ? 'font-bold text-honey-600' : '' }}">Keranjang</span>
        </a>
    </nav>

    <div id="toast" role="status" aria-live="polite" class="fixed left-1/2 top-4 z-50 hidden max-w-[calc(100vw-2rem)] -translate-x-1/2 break-words rounded-2xl border border-ink-200 bg-white px-4 py-2 text-sm font-bold text-ink-900 shadow-lg"></div>

    {{-- Floating cart bar: muncul saat ada item, sembunyi di cart/checkout/detail --}}
    @if (! in_array($currentRoute, ['cart', 'checkout', 'product.show', 'bundle.show']))
        <a href="{{ route('cart') }}" id="floating-cart"
            class="fixed bottom-20 left-1/2 z-40 hidden w-[calc(100%-2rem)] max-w-2xl -translate-x-1/2 items-center justify-between gap-3 rounded-2xl bg-ink-900 px-5 py-3.5 text-white shadow-xl transition active:scale-[0.98] sm:bottom-6">
            <span class="flex min-w-0 items-center gap-2.5">
                <span class="relative grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl bg-honey-400 text-ink-900">
                    <i data-lucide="shopping-cart" class="h-5 w-5 pointer-events-none"></i>
                    <span id="floating-cart-count" class="absolute -right-1.5 -top-1.5 grid h-5 min-w-5 place-items-center rounded-full bg-tomato-500 px-1 text-[11px] font-extrabold text-white">0</span>
                </span>
                <span class="min-w-0">
                    <span id="floating-cart-label" class="block truncate text-sm font-bold">0 item</span>
                    <span id="floating-cart-total" class="block text-xs text-cream-100/80">Rp0</span>
                </span>
            </span>
            <span class="flex flex-shrink-0 items-center gap-1 rounded-xl bg-honey-400 px-4 py-2.5 text-sm font-extrabold text-ink-900">
                Lihat
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            </span>
        </a>
    @endif
</body>
</html>