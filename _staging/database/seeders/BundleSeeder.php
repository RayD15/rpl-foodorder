<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BundleSeeder extends Seeder
{
    public function run(): void
    {
        $churros = Product::where('name', 'Churros')->first();
        $matcha = Product::where('name', 'Matcha')->first();
        $basreng = Product::where('name', 'Basreng')->first();
        $caramel = Product::where('name', 'Caramel Latte')->first();
        $risol = Product::where('name', 'Risol Mayo')->first();
        $esteh = Product::where('name', 'Es Teh Manis')->first();

        $bundles = [
            [
                'name' => 'Paket Ngemil',
                'description' => 'Churros + Basreng untuk teman ngemil.',
                'price' => 13000,
                'image' => 'paket-ngemil.jpg',
                'items' => [
                    ['product' => $churros, 'qty' => 1],
                    ['product' => $basreng, 'qty' => 1],
                ],
            ],
            [
                'name' => 'Paket Minum',
                'description' => 'Matcha + Es Teh Manis, segar-segar.',
                'price' => 14000,
                'image' => 'paket-minum.jpg',
                'items' => [
                    ['product' => $matcha, 'qty' => 1],
                    ['product' => $esteh, 'qty' => 1],
                ],
            ],
            [
                'name' => 'Paket Lengkap',
                'description' => 'Risol + Churros + Es Teh, lengkap untuk kenyang.',
                'price' => 15000,
                'image' => 'paket-lengkap.jpg',
                'items' => [
                    ['product' => $risol, 'qty' => 1],
                    ['product' => $churros, 'qty' => 1],
                    ['product' => $esteh, 'qty' => 1],
                ],
            ],
        ];

        foreach ($bundles as $data) {
            $items = $data['items'];
            unset($data['items']);

            $bundle = Bundle::updateOrCreate(['name' => $data['name']], $data);

            $bundle->items()->delete();
            foreach ($items as $item) {
                if ($item['product']) {
                    $bundle->items()->create([
                        'product_id' => $item['product']->id,
                        'qty' => $item['qty'],
                    ]);
                }
            }
        }
    }
}
