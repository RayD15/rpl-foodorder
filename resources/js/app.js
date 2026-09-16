import { createIcons, House, Utensils, Package, ShoppingCart, Search, ChevronRight, ExternalLink } from 'lucide';
window.APP = window.APP || { products: [], bundles: [], whatsapp: '' };
document.addEventListener('DOMContentLoaded', () => createIcons({ icons: { House, Utensils, Package, ShoppingCart, Search, ChevronRight, ExternalLink } }));

const formatRupiah = (n) => 'Rp' + Number(n || 0).toLocaleString('id-ID');

// Escape teks (nama produk dari admin) sebelum disuntik ke innerHTML.
const escapeHtml = (s) => String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');

const Cart = {
    KEY: 'rpl_cart',
    get() {
        try { return JSON.parse(localStorage.getItem(this.KEY) || '{}'); }
        catch { return {}; }
    },
    set(cart) {
        localStorage.setItem(this.KEY, JSON.stringify(cart));
        window.dispatchEvent(new CustomEvent('cart:update', { detail: cart }));
    },
    // Bersihkan item yang produk/bundlenya tidak ada / tidak ready di katalog
    clean() {
        const cart = this.get();
        let changed = false;
        Object.keys(cart).forEach((key) => {
            const { type, id } = this.parseKey(key);
            if (type === 'product') {
                const product = window.APP.products.find((p) => p.id === String(id));
                if (!product) { delete cart[key]; changed = true; }
            } else if (type === 'bundle') {
                const bundle = window.APP.bundles.find((b) => b.id === String(id));
                if (!bundle) { delete cart[key]; changed = true; }
            }
        });
        if (changed) this.set(cart);
        return cart;
    },
    parseKey(key) {
        if (key.startsWith('b:')) return { type: 'bundle', id: key.slice(2) };
        if (key.startsWith('p:')) return { type: 'product', id: key.slice(2) };
        // Backward compat: key lama tanpa prefix dianggap produk
        return { type: 'product', id: key };
    },
    makeKey(type, id) {
        return type === 'bundle' ? `b:${id}` : `p:${id}`;
    },
    items() {
        this.clean();
        return Object.entries(this.get())
            .map(([key, qty]) => {
                const { type, id } = this.parseKey(key);
                if (type === 'bundle') {
                    const bundle = window.APP.bundles.find((b) => b.id === String(id));
                    return bundle ? { ...bundle, type: 'bundle', id: bundle.id, qty } : null;
                }
                const product = window.APP.products.find((p) => p.id === String(id));
                return product ? { ...product, type: 'product', id: product.id, qty } : null;
            })
            .filter(Boolean);
    },
    count() { return this.items().reduce((s, i) => s + i.qty, 0); },
    total() { return this.items().reduce((s, i) => s + i.qty * i.price, 0); },
    add(id, qty = 1) {
        // Validasi: produk harus ada & ready di katalog
        const product = window.APP.products.find((p) => p.id === String(id));
        if (!product) return false;
        const key = this.makeKey('product', id);
        const cart = this.get();
        cart[key] = (cart[key] || 0) + qty;
        this.set(cart);
        return true;
    },
    // Tambahkan bundle sebagai satu item dengan harga paket
    addBundle(bundleId, qty = 1) {
        const bundle = window.APP.bundles.find((b) => b.id === String(bundleId));
        if (!bundle) return false;
        const key = this.makeKey('bundle', bundleId);
        const cart = this.get();
        cart[key] = (cart[key] || 0) + qty;
        this.set(cart);
        return true;
    },
    setQty(key, qty) {
        const cart = this.get();
        if (qty <= 0) { delete cart[key]; } else { cart[key] = qty; }
        this.set(cart);
    },
    remove(key) {
        const cart = this.get();
        delete cart[key];
        this.set(cart);
    },
    clear() {
        localStorage.removeItem(this.KEY);
        window.dispatchEvent(new CustomEvent('cart:update', { detail: {} }));
    },
};

let toastTimer = null;
function showToast(message, timeout = 1800) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.remove('hidden');
    // restart animation
    toast.classList.remove('animate-toast-in', 'animate-toast-out');
    void toast.offsetWidth;
    toast.classList.add('animate-toast-in');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        toast.classList.remove('animate-toast-in');
        toast.classList.add('animate-toast-out');
        setTimeout(() => toast.classList.add('hidden'), 260);
    }, timeout);
}

function updateFab() {
    const count = Cart.count();
    const total = Cart.total();

    // Update bottom navbar cart count
    const bottomCount = document.getElementById('bottom-cart-count');
    if (bottomCount) {
        bottomCount.textContent = count;
        bottomCount.classList.toggle('hidden', count === 0);
        bottomCount.classList.toggle('grid', count > 0);
    }

    // Update desktop navbar cart count
    const desktopCount = document.getElementById('desktop-cart-count');
    if (desktopCount) {
        desktopCount.textContent = count;
        desktopCount.classList.toggle('hidden', count === 0);
        desktopCount.classList.toggle('grid', count > 0);
    }


}

// Link menu hardcode aman untuk subfolder hosting (pakai path relatif,
// bukan origin + path absolut).
function menuUrl() {
    const link = document.querySelector('a[href$="/menu"]');
    return link ? link.getAttribute('href') : 'menu';
}

window.addEventListener('cart:update', updateFab);

// Scroll-spy: navbar (desktop + bottom) otomatis highlight section yang
// sedang terlihat saat user scroll. Navbar sendiri tidak pernah hilang.
const NAV_ACTIVE = ['text-honey-500'];
const NAV_INACTIVE_DESKTOP = ['text-ink-600', 'hover:bg-cream-100'];
const NAV_INACTIVE_MOBILE = ['text-ink-400', 'hover:text-honey-500'];
const NAV_BG = ['bg-honey-50'];

function paintNavLink(link, active) {
    const isMobile = link.closest('[data-section-nav]')?.classList.contains('sm:hidden');
    link.classList.remove(...NAV_ACTIVE, ...NAV_BG, ...NAV_INACTIVE_DESKTOP, ...NAV_INACTIVE_MOBILE);
    if (active) {
        link.classList.add(...NAV_ACTIVE);
        if (!isMobile) link.classList.add(...NAV_BG);
        const icon = link.querySelector('svg, i');
        if (icon) icon.classList.add('fill-honey-500/15');
        const label = link.querySelector('[data-nav-label]');
        if (label) label.classList.add('font-bold', 'text-honey-600');
    } else {
        link.classList.add(...(isMobile ? NAV_INACTIVE_MOBILE : NAV_INACTIVE_DESKTOP));
        const icon = link.querySelector('svg, i');
        if (icon) icon.classList.remove('fill-honey-500/15');
        const label = link.querySelector('[data-nav-label]');
        if (label) label.classList.remove('font-bold', 'text-honey-600');
    }
}

function initScrollSpy() {
    const sections = document.querySelectorAll('[data-section]');
    const navLinks = document.querySelectorAll('[data-section-nav] [data-nav]');
    if (!sections.length || !navLinks.length) return;

    let current = sections[0]?.dataset.section || 'hero';
    const setActive = (name) => {
        current = name;
        navLinks.forEach((link) => paintNavLink(link, link.dataset.nav === name));
    };

    if (!('IntersectionObserver' in window)) {
        setActive(current);
        return;
    }

    // Section dianggap aktif saat garis tengah viewport memotongnya.
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) setActive(entry.target.dataset.section);
        });
    }, { rootMargin: '-45% 0px -45% 0px', threshold: 0 });

    sections.forEach((s) => io.observe(s));
    setActive(current);

    // Dukung deep-link /#menu atau /#paket saat halaman dimuat.
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) setTimeout(() => target.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
    }
}

// Smooth scroll untuk link anchor internal (tanpa pindah halaman).
function initAnchorScroll() {
    document.addEventListener('click', (e) => {
        const link = e.target.closest('[data-scroll-to]');
        if (!link) return;
        const target = document.querySelector(link.dataset.scrollTo);
        if (!target) return;
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        history.replaceState(null, '', link.dataset.scrollTo);
    });
}

// Filter menu di home (client-side, tanpa reload halaman).
function initHomeMenuFilter() {
    const grid = document.getElementById('home-menu-grid');
    const loadingState = document.getElementById('home-menu-loading');
    const emptyState = document.getElementById('home-menu-empty');
    if (!grid) return;

    const searchInput = document.getElementById('home-menu-search');
    const categoryBtns = Array.from(document.querySelectorAll('[data-home-category]'));
    const cards = Array.from(grid.querySelectorAll('[data-product-card]'));

    let query = '';
    let category = '';

    const ACTIVE = ['border-honey-400', 'bg-honey-400', 'text-white', 'shadow-sm'];
    const INACTIVE = ['border-honey-400', 'bg-transparent', 'text-ink-700', 'hover:bg-honey-400', 'hover:text-white'];

    const paintCategoryBtns = () => {
        categoryBtns.forEach((btn) => {
            const isActive = (btn.dataset.homeCategory || '') === category;
            btn.classList.remove(...ACTIVE, ...INACTIVE);
            btn.classList.add(...(isActive ? ACTIVE : INACTIVE));
            if (isActive) btn.classList.add('hover:shadow-sm');
        });
    };

    const apply = () => {
        // Show loading state
        if (loadingState) loadingState.classList.remove('hidden');
        if (grid) grid.classList.add('hidden');
        if (emptyState) emptyState.classList.add('hidden');

        // Simulate network delay for better UX
        setTimeout(() => {
            const q = query.trim().toLowerCase();
            let visible = 0;
            cards.forEach((card) => {
                const matchQuery = !q || (card.dataset.name || '').includes(q);
                const matchCategory = !category || (card.dataset.category || '') === category;
                const show = matchQuery && matchCategory;
                card.classList.toggle('hidden', !show);
                if (show) visible += 1;
            });

            // Hide loading state, show results or empty state
            if (loadingState) loadingState.classList.add('hidden');
            if (grid) grid.classList.remove('hidden');
            if (emptyState) emptyState.classList.toggle('hidden', visible > 0);
        }, 300); // 300ms delay to show loading effect
    };

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            query = searchInput.value;
            apply();
        });
        // Enter: scroll grid ke atas agar hasil terlihat.
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    categoryBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            category = btn.dataset.homeCategory || '';
            paintCategoryBtns();
            apply();
        });
    });

    paintCategoryBtns();
    apply();
}

// Simulate loading delay for paket section on initial load
document.addEventListener('DOMContentLoaded', () => {
    // Show loading states for paket section initially
    const paketLoading = document.getElementById('paket-loading');
    const paketGrid = document.getElementById('paket-grid');
    const paketEmpty = document.getElementById('paket-empty');

    if (paketLoading && paketGrid && paketEmpty) {
        // Show loading state
        paketLoading.classList.remove('hidden');
        paketGrid.classList.add('hidden');
        paketEmpty.classList.add('hidden');

        // Hide loading after a short delay to show actual content
        setTimeout(() => {
            paketLoading.classList.add('hidden');
            // Determine whether to show grid or empty state based on actual content
            const bundleCount = paketGrid ? paketGrid.children.length : 0;
            if (paketGrid) paketGrid.classList.remove('hidden');
            if (paketEmpty) paketEmpty.classList.add('hidden');

            // If no bundles, show empty state
            if (bundleCount === 0) {
                if (paketGrid) paketGrid.classList.add('hidden');
                if (paketEmpty) paketEmpty.classList.remove('hidden');
            }
        }, 500); // 500ms delay to show loading effect
    }
});

// Reveal-on-scroll
function initReveal() {
    const els = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('is-visible'));
        return;
    }
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    els.forEach((el) => io.observe(el));
}

// Hero fullscreen background slideshow (crossfade)
function initHeroSlideshow() {
    const section = document.querySelector('[data-hero-slideshow]');
    if (!section) return;

    const slides = Array.from(section.querySelectorAll('[data-hero-slide]'));
    const dots = Array.from(section.querySelectorAll('[data-hero-dot]'));
    const currentName = document.getElementById('hero-current-name');
    const currentLink = document.getElementById('hero-current-product');
    if (!slides.length) return;

    let slideData = [];
    try { slideData = JSON.parse(section.dataset.slides || '[]'); } catch { slideData = []; }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const interval = Number(section.dataset.slideInterval || 4200);

    let current = 0;
    let timer = null;

    const go = (index) => {
        const next = (index + slides.length) % slides.length;
        slides.forEach((s, i) => {
            s.classList.toggle('opacity-100', i === next);
            s.classList.toggle('opacity-0', i !== next);
        });
        dots.forEach((d, i) => {
            const dot = d.querySelector('span') || d;
            dot.classList.toggle('w-6', i === next);
            dot.classList.toggle('bg-honey-400', i === next);
            dot.classList.toggle('w-2', i !== next);
            dot.classList.toggle('bg-white/50', i !== next);
        });
        const data = slideData[next];
        if (data && currentName && currentLink) {
            currentName.textContent = data.name;
            currentLink.href = data.url;
        }
        current = next;
    };

    const start = () => {
        if (reduceMotion) return;
        stop();
        timer = setInterval(() => go(current + 1), interval);
    };
    const stop = () => { if (timer) clearInterval(timer); timer = null; };

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => { go(i); start(); });
    });

    section.addEventListener('mouseenter', stop);
    section.addEventListener('mouseleave', start);
    section.addEventListener('touchstart', stop, { passive: true });
    section.addEventListener('touchend', start, { passive: true });

    // --- Swipe (HP) & drag (PC) ---
    let startX = null;
    let startY = null;
    let dragging = false;
    const SWIPE_THRESHOLD = 50; // px

    const onPointerDown = (e) => {
        startX = e.clientX ?? e.touches?.[0]?.clientX;
        startY = e.clientY ?? e.touches?.[0]?.clientY;
        dragging = true;
        stop();
    };
    const onPointerMove = (e) => {
        if (!dragging || startX === null) return;
        const x = e.clientX ?? e.touches?.[0]?.clientX;
        const y = e.clientY ?? e.touches?.[0]?.clientY;
        if (x === undefined || y === undefined) return;
        const dx = x - startX;
        const dy = y - startY;
        // Hanya swipe horizontal yang jelas (abaikan scroll vertikal)
        if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > SWIPE_THRESHOLD) {
            if (dx < 0) go(current + 1); // swipe kiri -> next
            else go(current - 1);        // swipe kanan -> prev
            startX = null; // reset agar tidak trigger berulang
        }
    };
    const onPointerUp = () => {
        dragging = false;
        startX = null;
        startY = null;
        start();
    };

    // Touch (HP)
    section.addEventListener('touchstart', onPointerDown, { passive: true });
    section.addEventListener('touchmove', onPointerMove, { passive: true });
    section.addEventListener('touchend', onPointerUp, { passive: true });

    // Mouse drag (PC)
    section.addEventListener('mousedown', onPointerDown);
    section.addEventListener('mousemove', onPointerMove);
    section.addEventListener('mouseup', onPointerUp);
    section.addEventListener('mouseleave', onPointerUp);

    // start paused on first paint so entrance doesn't jump
    requestAnimationFrame(start);
}

document.addEventListener('DOMContentLoaded', () => {
    Cart.clean();
    renderCartPage();
    bindCartControls();
    renderCheckoutPage();
    initCheckoutForm();
    initReveal();
    initHeroSlideshow();
    initScrollSpy();
    initAnchorScroll();
    initHomeMenuFilter();

    // Add-to-cart / add-bundle (delegasi: stay di halaman, tanpa redirect).
    // Beri animasi pada tombol yang diklik + toast konfirmasi.
    const bumpButton = (btn) => {
        btn.classList.remove('animate-bump');
        void btn.offsetWidth;
        btn.classList.add('animate-bump');
    };

    document.addEventListener('click', (e) => {
        const productBtn = e.target.closest('[data-add-to-cart]');
        if (productBtn) {
            e.preventDefault();
            bumpButton(productBtn);
            const id = productBtn.dataset.addToCart;
            const qty = Number(productBtn.dataset.qty || 1);
            const ok = Cart.add(id, qty);
            if (!ok) {
                showToast('Produk tidak tersedia');
                return;
            }
            const name = window.APP.products.find((p) => p.id === String(id))?.name || 'Produk';
            showToast(`${name} ditambahkan ke keranjang`);
            return;
        }

        const bundleBtn = e.target.closest('[data-add-bundle]');
        if (bundleBtn) {
            e.preventDefault();
            bumpButton(bundleBtn);
            const id = bundleBtn.dataset.addBundle;
            const qty = Number(bundleBtn.dataset.qty || 1);
            const ok = Cart.addBundle(id, qty);
            if (!ok) {
                showToast('Paket tidak tersedia');
                return;
            }
            const name = window.APP.bundles.find((b) => b.id === String(id))?.name || 'Paket';
            showToast(`${name} ditambahkan ke keranjang`);
        }
    });

    // Product / bundle detail quantity stepper
    const qtyInc = document.getElementById('qty-inc');
    const qtyDec = document.getElementById('qty-dec');
    const qtyVal = document.getElementById('qty-val');
    const addBtn = document.getElementById('product-add-btn') || document.getElementById('bundle-add-btn');
    if (qtyInc && qtyVal && addBtn) {
        const updateQty = (v) => {
            addBtn.dataset.qty = String(v);
            qtyVal.textContent = v;
            qtyDec.disabled = v <= 1;
        };
        qtyInc.addEventListener('click', () => updateQty(Number(addBtn.dataset.qty || 1) + 1));
        qtyDec.addEventListener('click', () => {
            const v = Math.max(1, Number(addBtn.dataset.qty || 1) - 1);
            updateQty(v);
        });
        updateQty(1);
    }

    updateFab();
});

// Cart page
function renderCartPage() {
    const list = document.getElementById('cart-items');
    if (!list) return;

    const items = Cart.items();

    document.getElementById('cart-empty').classList.toggle('hidden', items.length > 0);

    const summary = document.getElementById('cart-summary-section');
    if (summary) summary.classList.toggle('hidden', items.length === 0);

    const stickyBar = document.getElementById('cart-sticky-bar');
    if (stickyBar) stickyBar.classList.toggle('hidden', items.length === 0);

    if (!items.length) {
        list.innerHTML = '';
        return;
    }

    list.innerHTML = items.map((item) => {
        const key = Cart.makeKey(item.type, item.id);
        const isBundle = item.type === 'bundle';
        const safeName = escapeHtml(item.name);
        const detail = isBundle
            ? `<p class="line-clamp-2 break-words text-xs text-ink-500">${escapeHtml(item.items.map((i) => i.name + (i.qty > 1 ? ' ×' + i.qty : '')).join(' + '))}</p>`
            : `<p class="text-sm text-ink-500">${formatRupiah(item.price)}</p>`;
        return `
        <div class="animate-rise flex items-center gap-4 rounded-2xl border-2 border-ink-200 bg-cream-100 p-3 card-brutal-hover" data-line="${key}" style="--reveal-delay:${items.indexOf(item) * 60}ms">
            <img src="${item.image}" alt="${safeName}" loading="lazy" decoding="async" class="h-16 w-16 flex-shrink-0 rounded-xl object-cover bg-cream-200">
            <div class="min-w-0 flex-1">
                <p class="truncate font-bold text-ink-900">${safeName}</p>
                ${detail}
                <div class="mt-2 inline-flex items-center gap-2 rounded-xl border-2 border-ink-200 bg-cream-50 p-1.5">
                    <button type="button" data-cart-dec="${key}" aria-label="Kurangi" class="grid h-11 w-11 place-items-center rounded-lg bg-cream-200 text-xl font-bold text-ink-700 transition hover:bg-ink-200 active:scale-90">−</button>
                    <span data-cart-qty="${key}" class="w-6 text-center font-bold text-ink-900">${item.qty}</span>
                    <button type="button" data-cart-inc="${key}" aria-label="Tambah" class="grid h-11 w-11 place-items-center rounded-lg bg-honey-400 text-xl font-bold text-white transition hover:bg-honey-300 active:scale-90">+</button>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                <p class="font-extrabold text-ink-900">${formatRupiah(item.price * item.qty)}</p>
                <button type="button" data-cart-remove="${key}" aria-label="Hapus ${safeName}" class="grid h-11 w-11 place-items-center text-tomato-500 transition hover:text-tomato-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                </button>
            </div>
        </div>`;
    }).join('');

    const fmt = formatRupiah(Cart.total());
    const subtotal = document.getElementById('cart-subtotal');
    if (subtotal) subtotal.textContent = fmt;
    const shipping = document.getElementById('cart-shipping');
    if (shipping) shipping.textContent = 'Rp0';
    const grandTotal = document.getElementById('cart-grand-total');
    if (grandTotal) grandTotal.textContent = fmt;
    const stickyTotal = document.getElementById('cart-sticky-total');
    if (stickyTotal) stickyTotal.textContent = fmt;
}

function bindCartControls() {
    document.querySelectorAll('[data-cart-inc]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.cartInc;
            Cart.setQty(id, Cart.get()[id] + 1);
            renderCartPage();
            bindCartControls();
        });
    });
    document.querySelectorAll('[data-cart-dec]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.cartDec;
            const next = Cart.get()[id] - 1;
            Cart.setQty(id, next <= 0 ? 0 : next);
            renderCartPage();
            bindCartControls();
        });
    });
    document.querySelectorAll('[data-cart-remove]').forEach((btn) => {
        btn.addEventListener('click', () => {
            Cart.remove(btn.dataset.cartRemove);
            renderCartPage();
            bindCartControls();
        });
    });
}

// Checkout page
function renderCheckoutPage() {
    const list = document.getElementById('checkout-items');
    if (!list) return;

    const items = Cart.items();

    if (!items.length) {
        list.innerHTML = `
            <div class="py-16 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="mx-auto mb-4 h-16 w-16 text-ink-300"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                <h2 class="font-display mb-6 text-2xl font-normal text-ink-900">Keranjang kosong</h2>
                <a href="${menuUrl()}" class="inline-block rounded-xl border border-ink-200 bg-tomato-500 px-6 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-honey-300 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">Lihat Menu</a>
            </div>`;
        document.getElementById('checkout-form').classList.add('hidden');
        return;
    }

    list.innerHTML = items.map((item) => {
        const detail = item.type === 'bundle'
            ? `<span class="block break-words text-xs text-ink-400">${escapeHtml(item.items.map((i) => i.name + (i.qty > 1 ? ' ×' + i.qty : '')).join(' + '))}</span>`
            : '';
        return `
        <div class="animate-rise flex items-center justify-between gap-3 py-2 text-sm text-ink-800" style="--reveal-delay:${items.indexOf(item) * 60}ms">
            <span class="min-w-0 break-words"><strong>${escapeHtml(item.name)}</strong> × ${item.qty}${detail}</span>
            <span class="flex-shrink-0 font-bold text-ink-900">${formatRupiah(item.price * item.qty)}</span>
        </div>`;
    }).join('');

    document.getElementById('checkout-total').textContent = formatRupiah(Cart.total());
}

const buildMessage = (name, kelas, notes) => {
    const items = Cart.items();
    let msg = 'Halo Admin TamsisFood!\n\n';
    msg += 'Saya ingin memesan:\n\n';
    items.forEach((i) => {
        msg += `${i.name} × ${i.qty} = ${formatRupiah(i.price * i.qty)}\n`;
        if (i.type === 'bundle') {
            i.items.forEach((bi) => {
                msg += `   • ${bi.name} × ${bi.qty}\n`;
            });
        }
    });
    msg += `\n💰 Total: ${formatRupiah(Cart.total())}\n\n`;
    msg += `👤 Nama: ${name}\n`;
    msg += `🏫 Kelas: ${kelas}\n`;
    if (notes) msg += `\n📝 Catatan:\n${notes}\n`;
    msg += '\nMohon dikonfirmasi pesanannya.\nTerima kasih 🙏';
    return msg;
};

function initCheckoutForm() {
    const form = document.getElementById('checkout-form');
    if (!form) return;
    if (Cart.count() === 0) { form.classList.add('hidden'); return; }

    const modal = document.getElementById('checkout-confirm-modal');
    const confirmName = document.getElementById('confirm-name');
    const confirmClass = document.getElementById('confirm-class');
    const confirmNotes = document.getElementById('confirm-notes');
    const confirmItems = document.getElementById('confirm-items');
    const confirmTotal = document.getElementById('confirm-total');
    const confirmCancel = document.getElementById('confirm-cancel');
    const confirmSubmit = document.getElementById('confirm-submit');

    let pendingData = null;

    const openModal = (name, kelas, notes) => {
        pendingData = { name, kelas, notes };
        confirmName.textContent = name;
        confirmClass.textContent = kelas;
        confirmNotes.textContent = notes || '(tidak ada)';

        const items = Cart.items();
        confirmItems.innerHTML = items.map((i) => {
            const detail = i.type === 'bundle'
                ? `<div class="break-words text-xs text-ink-400">${escapeHtml(i.items.map((bi) => bi.name + (bi.qty > 1 ? ' ×' + bi.qty : '')).join(' + '))}</div>`
                : '';
            return `
            <div class="flex justify-between gap-3">
                <span class="min-w-0 break-words">${escapeHtml(i.name)} × ${i.qty}${detail}</span>
                <span class="flex-shrink-0">${formatRupiah(i.price * i.qty)}</span>
            </div>`;
        }).join('');
        confirmTotal.textContent = formatRupiah(Cart.total());

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        pendingData = null;
    };

    const sendToWhatsApp = () => {
        if (!pendingData) return;
        const { name, kelas, notes } = pendingData;
        const number = window.APP.whatsapp || '6281234567890';
        const url = `https://wa.me/${number}?text=${encodeURIComponent(buildMessage(name, kelas, notes))}`;
        window.open(url, '_blank');
        Cart.clear();
        renderCartPage();
        renderCheckoutPage();
        showToast('Pesanan dikirim ke WhatsApp');
        setTimeout(() => window.location.href = '/', 1600);
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = document.getElementById('customer-name').value.trim();
        const kelas = document.getElementById('customer-class').value.trim();
        const notes = document.getElementById('customer-notes').value.trim();

        if (!name || !kelas) {
            showToast('Nama dan Kelas wajib diisi');
            return;
        }

        openModal(name, kelas, notes);
    });

    confirmCancel.addEventListener('click', closeModal);
    confirmSubmit.addEventListener('click', () => {
        sendToWhatsApp();
        closeModal();
    });

    // Tutup modal saat klik di luar konten
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
}