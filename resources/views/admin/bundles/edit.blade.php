@extends('layouts.admin')

@section('title', 'Edit Paket')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.bundles.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-ink-500 hover:text-tomato-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
            Kembali
        </a>
        <h1 class="mt-1 font-display text-3xl font-normal text-ink-900">Edit Paket</h1>
    </div>

    <div class="max-w-xl rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
        <form action="{{ route('admin.bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Nama Paket</span>
                <input type="text" name="name" value="{{ old('name', $bundle->name) }}" required
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
                @error('name') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </label>

            <div>
                <span class="text-sm font-bold text-ink-800">Isi Paket</span>
                <p class="mb-2 text-xs text-ink-400">Pilih produk dan jumlah untuk paket ini.</p>
                <div id="bundle-items" class="flex flex-col gap-2">
                    @forelse ($bundle->items as $index => $item)
                        <div class="bundle-item flex items-center gap-2">
                            <select name="items[{{ $index }}][product_id]" class="bundle-product flex-1 rounded-xl border-2 border-ink-200 bg-cream-50 px-3 py-2.5 text-sm outline-none transition focus:border-tomato-500">
                                <option value="">— Pilih produk —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" @selected($item->product_id == $product->id)>{{ $product->name }} ({{ 'Rp' . number_format($product->price, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            <input type="number" name="items[{{ $index }}][qty]" value="{{ $item->qty }}" min="1" class="bundle-qty w-16 rounded-xl border-2 border-ink-200 bg-cream-50 px-2 py-2.5 text-center text-sm outline-none transition focus:border-tomato-500">
                            <button type="button" class="bundle-remove grid h-9 w-9 flex-shrink-0 place-items-center rounded-lg border-2 border-tomato-500/40 bg-tomato-500/10 text-tomato-600 transition hover:bg-tomato-500/20" title="Hapus baris">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    @empty
                        <div class="bundle-item flex items-center gap-2">
                            <select name="items[0][product_id]" class="bundle-product flex-1 rounded-xl border-2 border-ink-200 bg-cream-50 px-3 py-2.5 text-sm outline-none transition focus:border-tomato-500">
                                <option value="">— Pilih produk —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} ({{ 'Rp' . number_format($product->price, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            <input type="number" name="items[0][qty]" value="1" min="1" class="bundle-qty w-16 rounded-xl border-2 border-ink-200 bg-cream-50 px-2 py-2.5 text-center text-sm outline-none transition focus:border-tomato-500">
                            <button type="button" class="bundle-remove grid h-9 w-9 flex-shrink-0 place-items-center rounded-lg border-2 border-tomato-500/40 bg-tomato-500/10 text-tomato-600 transition hover:bg-tomato-500/20" title="Hapus baris">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    @endforelse
                </div>
                <button type="button" id="bundle-add-item" class="mt-2 inline-flex items-center gap-1.5 rounded-xl border-2 border-ink-200 bg-cream-50 px-3 py-2 text-sm font-bold text-ink-700 transition hover:bg-cream-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Produk
                </button>
                <p id="bundle-regular-total" class="mt-2 text-xs font-semibold text-ink-500"></p>
                @error('items') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </div>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Harga Paket (Rp)</span>
                <input type="number" name="price" value="{{ old('price', $bundle->price) }}" min="0" required
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">
                @error('price') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Deskripsi</span>
                <textarea name="description" rows="3"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-tomato-500 focus:ring-2 focus:ring-tomato-500/20">{{ old('description', $bundle->description) }}</textarea>
            </label>

            @if ($bundle->image)
                <div class="flex items-center gap-3">
                    <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" class="h-16 w-16 rounded-xl object-cover bg-cream-200">
                    <span class="text-xs text-ink-400">Gambar saat ini. Upload gambar baru untuk mengganti.</span>
                </div>
            @endif

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Gambar</span>
                <input type="file" name="image" accept="image/*"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm">
                <span class="text-xs text-ink-400">JPG / PNG / WebP / SVG, maks 5MB. Kosongkan untuk mempertahankan gambar.</span>
                @error('image') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </label>

            <div>
                <span class="text-sm font-bold text-ink-800">Status</span>
                <div class="mt-2 flex gap-4">
                    <label class="flex items-center gap-2 text-sm font-bold text-ink-800">
                        <input type="radio" name="status" value="ready" @checked($bundle->isReady()) class="h-4 w-4 accent-tomato-500">
                        Ready
                    </label>
                    <label class="flex items-center gap-2 text-sm font-bold text-ink-800">
                        <input type="radio" name="status" value="sold_out" @checked(!$bundle->isReady()) class="h-4 w-4 accent-tomato-500">
                        Sold Out
                    </label>
                </div>
                @error('status') <span class="text-xs font-bold text-tomato-600">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                class="mt-2 rounded-2xl border-2 border-ink-900 bg-tomato-500 px-6 py-3.5 text-sm font-extrabold text-white shadow-[4px_4px_0_0_var(--color-ink-900)] transition hover:bg-tomato-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('bundle-items');
            const addBtn = document.getElementById('bundle-add-item');
            const regularTotal = document.getElementById('bundle-regular-total');

            const productOptions = @json($products->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price]));

            const fmt = (n) => 'Rp' + Number(n || 0).toLocaleString('id-ID');

            const updateTotal = () => {
                let total = 0;
                container.querySelectorAll('.bundle-item').forEach((row) => {
                    const sel = row.querySelector('.bundle-product');
                    const qty = Number(row.querySelector('.bundle-qty').value || 0);
                    const opt = sel.selectedOptions[0];
                    if (opt && opt.value) {
                        total += Number(opt.dataset.price || 0) * qty;
                    }
                });
                regularTotal.textContent = total > 0 ? 'Total harga normal: ' + fmt(total) : '';
            };

            const addRow = (productId = '', qty = 1) => {
                const index = container.children.length;
                const row = document.createElement('div');
                row.className = 'bundle-item flex items-center gap-2';
                row.innerHTML = `
                    <select name="items[${index}][product_id]" class="bundle-product flex-1 rounded-xl border-2 border-ink-200 bg-cream-50 px-3 py-2.5 text-sm outline-none transition focus:border-tomato-500">
                        <option value="">— Pilih produk —</option>
                        ${productOptions.map((p) => `<option value="${p.id}" data-price="${p.price}" ${String(p.id) === String(productId) ? 'selected' : ''}>${p.name} (${fmt(p.price)})</option>`).join('')}
                    </select>
                    <input type="number" name="items[${index}][qty]" value="${qty}" min="1" class="bundle-qty w-16 rounded-xl border-2 border-ink-200 bg-cream-50 px-2 py-2.5 text-center text-sm outline-none transition focus:border-tomato-500">
                    <button type="button" class="bundle-remove grid h-9 w-9 flex-shrink-0 place-items-center rounded-lg border-2 border-tomato-500/40 bg-tomato-500/10 text-tomato-600 transition hover:bg-tomato-500/20" title="Hapus baris">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                `;
                container.appendChild(row);
                row.querySelector('.bundle-remove').addEventListener('click', () => { row.remove(); updateTotal(); });
                row.querySelector('.bundle-product').addEventListener('change', updateTotal);
                row.querySelector('.bundle-qty').addEventListener('input', updateTotal);
                updateTotal();
            };

            addBtn.addEventListener('click', () => addRow());
            container.querySelectorAll('.bundle-item').forEach((row) => {
                row.querySelector('.bundle-remove').addEventListener('click', () => { row.remove(); updateTotal(); });
                row.querySelector('.bundle-product').addEventListener('change', updateTotal);
                row.querySelector('.bundle-qty').addEventListener('input', updateTotal);
            });
            updateTotal();
        });
    </script>
@endsection
