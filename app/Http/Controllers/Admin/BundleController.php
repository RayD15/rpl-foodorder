<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\BundleImageGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // Reload bundle with items and products for image generation
        $bundle->load('items.product');

        // Auto-generate image from bundle products
        $this->handleBundleImage($request, $bundle);

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

        // Reload bundle with items and products for image generation
        $bundle->load('items.product');

        // Auto-generate image from bundle products
        $this->handleBundleImage($request, $bundle);

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
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'status' => ['required', 'in:ready,sold_out'],
        ]);

        $data['status'] = $request->input('status', 'ready');

        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage
            if ($existingImage) {
                Storage::disk('public')->delete($existingImage);
            }

            $filename = Str::uuid()->toString().'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('products', $filename, 'public');
            $data['image'] = $path;
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

    /**
     * Handle bundle image generation or manual upload.
     */
    private function handleBundleImage(Request $request, Bundle $bundle): void
    {
        $autoGenerate = $request->has('auto_generate_image');
        $hasManualUpload = $request->hasFile('image');

        // If manual image is uploaded, use it (overrides auto-generate)
        if ($hasManualUpload) {
            // Image already handled in validateData()
            return;
        }

        // If auto-generate is enabled and no manual upload, generate from products
        if ($autoGenerate) {
            $generator = new BundleImageGenerator;
            $generatedImage = $generator->generateFromBundle($bundle);

            if ($generatedImage) {
                $bundle->update(['image' => $generatedImage]);
            }
        } elseif (! $hasManualUpload) {
            // Auto-generate disabled and no manual upload
            // If bundle has no items, set image to null
            if ($bundle->items->isEmpty()) {
                $bundle->update(['image' => null]);
            }
            // Otherwise keep existing image
        }
    }
}
