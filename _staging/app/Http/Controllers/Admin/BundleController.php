<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::with('items.product')->latest()->get();

        return view('admin.bundles.index', compact('bundles'));
    }

    public function create()
    {
        $products = Product::where('status', 'ready')->get();

        return view('admin.bundles.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $bundle = Bundle::create($data);

        $this->syncItems($bundle, $request->input('items', []));

        return redirect()
            ->route('admin.bundles.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Bundle $bundle)
    {
        $products = Product::where('status', 'ready')->get();
        $bundle->load('items');

        return view('admin.bundles.edit', compact('bundle', 'products'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $data = $this->validateData($request, $bundle->image);

        $bundle->update($data);

        $this->syncItems($bundle, $request->input('items', []));

        return redirect()
            ->route('admin.bundles.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Bundle $bundle)
    {
        $bundle->delete();

        return redirect()
            ->route('admin.bundles.index')
            ->with('success', 'Paket berhasil dihapus.');
    }

    private function validateData(Request $request, ?string $existingImage = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'status' => ['required', 'in:ready,sold_out'],
        ]);

        $data['status'] = $request->input('status', 'ready');

        if ($request->hasFile('image')) {
            // Hapus gambar lama di public/images/ (cukup bila bukan path storage)
            if (
                $existingImage
                && ! str_contains($existingImage, '/')
                && file_exists(public_path('images/'.$existingImage))
            ) {
                @unlink(public_path('images/'.$existingImage));
            }

            $filename = Str::uuid()->toString().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images'), $filename);
            $data['image'] = $filename;
        }

        return $data;
    }

    private function syncItems(Bundle $bundle, array $items): void
    {
        $bundle->items()->delete();

        $validItems = collect($items)
            ->filter(fn ($item) => ! empty($item['product_id']))
            ->map(fn ($item) => [
                'product_id' => $item['product_id'],
                'qty' => max(1, (int) ($item['qty'] ?? 1)),
            ]);

        foreach ($validItems as $item) {
            $bundle->items()->create($item);
        }
    }
}
