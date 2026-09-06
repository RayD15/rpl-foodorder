{{-- Bottom Navbar (Mobile Only) - Backup 2026-09-02 --}}
<nav class="fixed bottom-0 left-0 z-40 grid w-full grid-cols-4 border-t border-ink-100 bg-white/95 pb-safe shadow-[0_-1px_3px_-1px_rgba(0,0,0,0.06)] backdrop-blur sm:hidden">
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-1 py-2 transition {{ $currentRoute === 'home' ? 'text-honey-500' : 'text-ink-400 hover:text-honey-500' }}">
        <i data-lucide="house" class="h-5 w-5 {{ $currentRoute === 'home' ? 'fill-honey-500/15' : '' }}"></i>
        <span class="text-[10px] {{ $currentRoute === 'home' ? 'font-bold text-honey-600' : '' }}">Beranda</span>
    </a>
    <a href="{{ route('menu') }}" class="flex flex-col items-center justify-center gap-1 py-2 transition {{ $currentRoute === 'menu' || $currentRoute === 'product.show' ? 'text-honey-500' : 'text-ink-400 hover:text-honey-500' }}">
        <i data-lucide="utensils" class="h-5 w-5 {{ $currentRoute === 'menu' || $currentRoute === 'product.show' ? 'fill-honey-500/15' : '' }}"></i>
        <span class="text-[10px] {{ $currentRoute === 'menu' || $currentRoute === 'product.show' ? 'font-bold text-honey-600' : '' }}">Menu</span>
    </a>
    <a href="{{ route('paket') }}" class="flex flex-col items-center justify-center gap-1 py-2 transition {{ $currentRoute === 'paket' || $currentRoute === 'bundle.show' ? 'text-honey-500' : 'text-ink-400 hover:text-honey-500' }}">
        <i data-lucide="package" class="h-5 w-5 {{ $currentRoute === 'paket' || $currentRoute === 'bundle.show' ? 'fill-honey-500/15' : '' }}"></i>
        <span class="text-[10px] {{ $currentRoute === 'paket' || $currentRoute === 'bundle.show' ? 'font-bold text-honey-600' : '' }}">Paket</span>
    </a>
    <a href="{{ route('cart') }}" class="relative flex flex-col items-center justify-center gap-1 py-2 transition {{ $currentRoute === 'cart' ? 'text-honey-500' : 'text-ink-400 hover:text-honey-500' }}">
        <span class="relative"><i data-lucide="shopping-cart" class="h-5 w-5 {{ $currentRoute === 'cart' ? 'fill-honey-500/15' : '' }}"></i><span id="bottom-cart-count" class="absolute -top-1.5 -right-1.5 hidden h-[18px] min-w-[18px] items-center justify-center rounded-full bg-tomato-500 px-1 text-[11px] font-extrabold text-white">0</span></span>
        <span class="text-[10px] {{ $currentRoute === 'cart' ? 'font-bold text-honey-600' : '' }}">Keranjang</span>
    </a>
</nav>
