<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function cart()
    {
        return view('cart');
    }

    public function index()
    {
        return view('checkout');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'class' => ['required', 'string', 'max:191'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
        ]);

        $validatedItems = collect($request->input('items', []))
            ->map(function ($item) {
                if (($item['type'] ?? null) === 'bundle') {
                    $bundle = Bundle::where('id', $item['id'])
                        ->where('status', 'ready')
                        ->with('items.product')
                        ->first();
                    if (! $bundle) {
                        return ['valid' => false, 'error' => 'Paket tidak ditemukan atau sold out'];
                    }
                    return [
                        'valid' => true,
                        'type' => 'bundle',
                        'id' => $bundle->id,
                        'name' => $bundle->name,
                        'price' => $bundle->price,
                        'qty' => max(1, (int) ($item['qty'] ?? 1)),
                        'items' => $bundle->items->map(fn ($i) => [
                            'name' => $i->product?->name ?? 'Produk dihapus',
                            'qty' => $i->qty,
                        ])->toArray(),
                    ];
                }

                $product = Product::where('id', $item['id'])
                    ->where('status', 'ready')
                    ->first();
                if (! $product) {
                    return ['valid' => false, 'error' => 'Produk tidak ditemukan atau sold out'];
                }
                return [
                    'valid' => true,
                    'type' => 'product',
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => max(1, (int) ($item['qty'] ?? 1)),
                ];
            })
            ->filter(fn ($i) => $i['valid']);

        if ($validatedItems->isEmpty()) {
            return response()->json(['errors' => ['items' => ['Keranjang kosong atau semua item tidak valid']]], 422);
        }

        $total = $validatedItems->sum(fn ($i) => $i['price'] * $i['qty']);
        $name = trim($request->input('name'));
        $kelas = trim($request->input('class'));
        $notes = trim($request->input('notes', ''));

        $msg = "Halo Admin TamsisFood!\n\nSaya ingin memesan:\n\n";
        foreach ($validatedItems as $i) {
            $msg .= "{$i['name']} × {$i['qty']} = Rp" . number_format($i['price'] * $i['qty'], 0, ',', '.') . "\n";
            if (($i['type'] ?? null) === 'bundle' && isset($i['items'])) {
                foreach ($i['items'] as $bi) {
                    $msg .= "   • {$bi['name']} × {$bi['qty']}\n";
                }
            }
        }
        $msg .= "\n💰 Total: Rp" . number_format($total, 0, ',', '.') . "\n\n";
        $msg .= "👤 Nama: {$name}\n🏫 Kelas: {$kelas}\n";
        if ($notes) {
            $msg .= "\n📝 Catatan:\n{$notes}\n";
        }
        $msg .= "\nMohon dikonfirmasi pesanannya.\nTerima kasih 🙏";

        $whatsapp = \Illuminate\Support\Facades\Cache::get('setting_whatsapp_number', '6281234567890');
        $url = "https://wa.me/{$whatsapp}?text=" . urlencode($msg);

        return response()->json(['url' => $url, 'total' => $total]);
    }
}