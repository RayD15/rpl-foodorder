<?php

namespace App\Providers;

use App\Models\Bundle;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS saat diakses lewat tunnel/HTTPS (mis. Cloudflare Tunnel)
        // agar aset (CSS/JS/gambar) tidak diblokir sebagai mixed content.
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        View::composer([
            'layouts.customer',
            'home',
            'menu',
            'paket',
            'product-detail',
            'bundle-detail',
            'cart',
            'checkout',
        ], function ($view) {
            $catalog = Product::where('status', 'ready')->get(['id', 'name', 'price', 'image'])
                ->map(fn ($p) => ['id' => (string) $p->id, 'name' => $p->name, 'price' => (int) $p->price, 'image' => $p->image_url])
                ->values();

            $bundles = Bundle::with('items.product')
                ->where('status', 'ready')
                ->get()
                ->map(fn ($b) => [
                    'id' => (string) $b->id,
                    'name' => $b->name,
                    'description' => $b->description,
                    'price' => (int) $b->price,
                    'image' => $b->image_url,
                    'regular_total' => (int) $b->regular_total,
                    'savings' => (int) $b->savings,
                    'items' => $b->items->map(fn ($i) => [
                        'product_id' => (string) $i->product_id,
                        'name' => $i->product?->name,
                        'qty' => (int) $i->qty,
                        'price' => (int) ($i->product?->price ?? 0),
                    ])->values(),
                ])
                ->values();

            $view->with('catalog', $catalog);
            $view->with('catalogJson', $catalog->toJson());
            $view->with('bundles', $bundles);
            $view->with('bundlesJson', $bundles->toJson());
            $view->with('whatsappNumber', Setting::get('whatsapp_number'));
        });
    }
}
