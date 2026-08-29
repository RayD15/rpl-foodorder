window.APP = window.APP || { products: [], bundles: [], whatsapp: '' };

const formatRupiah = (n) => 'Rp' + Number(n || 0).toLocaleString('id-ID');

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
};

let toastTimer = null;
function showToast(message, timeout = 1800) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.remove('hidden');
    // restart animation
    toast.classList.remove('animate-toast-in');
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
    }
}

window.addEventListener('cart:update', updateFab);

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
            d.classList.toggle('w-6', i === next);
            d.classList.toggle('bg-honey-400', i === next);
            d.classList.toggle('w-2', i !== next);
            d.classList.toggle('bg-white/50', i !== next);
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

    // Add-to-cart buttons (menu / home / product detail)
    document.querySelectorAll('[data-add-to-cart]').forEach((btn) => {
        btn.addEventListener('click', () => {
            btn.classList.remove('animate-bump');
            void btn.offsetWidth;
            btn.classList.add('animate-bump');
            const id = btn.dataset.addToCart;
            const qty = Number(btn.dataset.qty || 1);
            const ok = Cart.add(id, qty);
            if (!ok) {
                showToast('Produk tidak tersedia');
                return;
            }
            const name = window.APP.products.find((p) => p.id === String(id))?.name || 'Produk';
            showToast(`${name} ditambahkan ke keranjang`);
        });
    });

    // Add-bundle buttons (menu / bundle detail)
    document.querySelectorAll('[data-add-bundle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            btn.classList.remove('animate-bump');
            void btn.offsetWidth;
            btn.classList.add('animate-bump');
            const id = btn.dataset.addBundle;
            const qty = Number(btn.dataset.qty || 1);
            const ok = Cart.addBundle(id, qty);
            if (!ok) {
                showToast('Paket tidak tersedia');
                return;
            }
            const name = window.APP.bundles.find((b) => b.id === String(id))?.name || 'Paket';
            showToast(`${name} ditambahkan ke keranjang`);
        });
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
    document.getElementById('cart-summary-section').classList.toggle('hidden', items.length === 0);

    if (!items.length) {
        list.innerHTML = '';
        return;
    }

    list.innerHTML = items.map((item) => {
        const key = Cart.makeKey(item.type, item.id);
        const isBundle = item.type === 'bundle';
        const detail = isBundle
            ? `<p class="text-xs text-ink-500">${item.items.map((i) => i.name + (i.qty > 1 ? ' ×' + i.qty : '')).join(' + ')}</p>`
            : `<p class="text-sm text-ink-500">${formatRupiah(item.price)}</p>`;
        return `
        <div class="animate-rise flex items-center gap-4 rounded-2xl border-2 border-ink-200 bg-cream-100 p-3 card-brutal-hover" data-line="${key}" style="--reveal-delay:${items.indexOf(item) * 60}ms">
            <img src="${item.image}" alt="${item.name}" class="h-16 w-16 flex-shrink-0 rounded-xl object-cover bg-cream-200">
            <div class="min-w-0 flex-1">
                <p class="truncate font-bold text-ink-900">${item.name}</p>
                ${detail}
                <div class="mt-2 inline-flex items-center gap-3 rounded-xl border-2 border-ink-200 bg-cream-50 px-2 py-1">
                    <button data-cart-dec="${key}" aria-label="Kurangi" class="grid h-7 w-7 place-items-center rounded-lg bg-cream-200 text-lg font-bold text-ink-700 transition hover:bg-ink-200">−</button>
                    <span data-cart-qty="${key}" class="w-6 text-center font-bold text-ink-900">${item.qty}</span>
                    <button data-cart-inc="${key}" aria-label="Tambah" class="grid h-7 w-7 place-items-center rounded-lg bg-tomato-500 text-lg font-bold text-white transition hover:bg-tomato-600">+</button>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                <p class="font-extrabold text-ink-900">${formatRupiah(item.price * item.qty)}</p>
                <button data-cart-remove="${key}" aria-label="Hapus" class="text-tomato-500 transition hover:text-tomato-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                </button>
            </div>
        </div>`;
    }).join('');

    document.getElementById('cart-subtotal').textContent = formatRupiah(Cart.total());
    document.getElementById('cart-shipping').textContent = 'Rp0';
    document.getElementById('cart-grand-total').textContent = formatRupiah(Cart.total());
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
                <a href="${window.location.origin + '/menu'}" class="inline-block rounded-xl border-2 border-ink-900 bg-tomato-500 px-6 py-3 text-sm font-extrabold text-white shadow-[4px_4px_0_0_var(--color-ink-900)] transition hover:bg-tomato-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">Lihat Menu</a>
            </div>`;
        document.getElementById('checkout-form').classList.add('hidden');
        return;
    }

    list.innerHTML = items.map((item) => {
        const detail = item.type === 'bundle'
            ? `<span class="block text-xs text-ink-400">${item.items.map((i) => i.name + (i.qty > 1 ? ' ×' + i.qty : '')).join(' + ')}</span>`
            : '';
        return `
        <div class="animate-rise flex items-center justify-between py-2 text-sm text-ink-800" style="--reveal-delay:${items.indexOf(item) * 60}ms">
            <span><strong>${item.name}</strong> × ${item.qty}${detail}</span>
            <span class="font-bold text-ink-900">${formatRupiah(item.price * item.qty)}</span>
        </div>`;
    }).join('');

    document.getElementById('checkout-total').textContent = formatRupiah(Cart.total());
}

const buildMessage = (name, kelas, notes) => {
    const items = Cart.items();
    let msg = 'Halo Admin RPL2 FoodOrder 👋\n\n';
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
                ? `<div class="text-xs text-ink-400">${i.items.map((bi) => bi.name + (bi.qty > 1 ? ' ×' + bi.qty : '')).join(' + ')}</div>`
                : '';
            return `
            <div class="flex justify-between">
                <span>${i.name} × ${i.qty}${detail}</span>
                <span>${formatRupiah(i.price * i.qty)}</span>
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
        localStorage.removeItem(Cart.KEY);
        window.dispatchEvent(new CustomEvent('cart:update'));
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