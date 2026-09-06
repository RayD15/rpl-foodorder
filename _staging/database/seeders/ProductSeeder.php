<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $food = Category::where('slug', 'makanan')->first();
        $drink = Category::where('slug', 'minuman')->first();

        $products = [
            ['category_id' => $food->id, 'name' => 'Churros', 'price' => 5000, 'description' => 'Churros crispy dengan rasa manis dan lezat. 1 porsi berisi 3 pcs.', 'image' => 'churros.jpg'],
            ['category_id' => $drink->id, 'name' => 'Matcha', 'price' => 12000, 'description' => 'Minuman matcha premium dengan rasa creamy dan menyegarkan.', 'image' => 'matca.jpg'],
            ['category_id' => $food->id, 'name' => 'Basreng', 'price' => 10000, 'description' => 'Basreng pedas gurih, renyah dan bikin ketagihan.', 'image' => 'basreng.jpg'],
            ['category_id' => $drink->id, 'name' => 'Caramel Latte', 'price' => 15000, 'description' => 'Latte dengan sensasi manis karamel yang lembut.', 'image' => 'caramel.jpg'],
            ['category_id' => $food->id, 'name' => 'Risol Mayo', 'price' => 8000, 'description' => 'Risol dengan isian mayo creamy yang gurih.', 'image' => 'risol.jpg'],
            ['category_id' => $drink->id, 'name' => 'Es Teh Manis', 'price' => 4000, 'description' => 'Teh manis dingin yang menyegarkan.', 'image' => 'es teh.png'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}
