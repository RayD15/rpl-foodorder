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
        foreach ([
            ['name' => 'Varo', 'email' => 'varo@admin.com'],
            ['name' => 'Rayhand', 'email' => 'rayhand@admin.com'],
            ['name' => 'Zaky', 'email' => 'zaky@admin.com'],
        ] as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make('admin1510'),
                ]
            );
        }

        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => '6281234567890']);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            BundleSeeder::class,
        ]);
    }
}
