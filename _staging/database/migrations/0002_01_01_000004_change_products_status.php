<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ganti kolom boolean `is_active` menjadi kolom string `status`
     * dengan nilai 'ready' (tersedia) atau 'sold_out' (habis).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('status')->default('ready')->after('image');
        });

        // Salin data lama: is_active = true -> ready, false -> sold_out
        DB::table('products')->update(['status' => DB::raw("CASE WHEN is_active = 1 THEN 'ready' ELSE 'sold_out' END")]);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('image');
        });

        DB::table('products')->update(['is_active' => DB::raw("CASE WHEN status = 'ready' THEN 1 ELSE 0 END")]);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
