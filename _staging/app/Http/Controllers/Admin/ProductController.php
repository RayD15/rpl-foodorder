<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request, $product->image);

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function validateData(Request $request, ?string $existingImage = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'category_id' => ['required', 'exists:categories,id'],
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
}
