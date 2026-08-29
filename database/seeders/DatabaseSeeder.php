<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin1@kdi.com'],
            [
                'name' => 'Admin RPL2 FoodOrder',
                'password' => Hash::make('rplkdi1510'),
            ]
        );

        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => '6281234567890']);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            BundleSeeder::class,
        ]);
    }
}
