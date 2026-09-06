<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus kolom `is_popular` karena fitur "Populer" dihapus.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_popular');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_popular')->default(false)->after('status');
        });
    }
};
