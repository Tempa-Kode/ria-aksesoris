<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('riwayat_stok_produk', function (Blueprint $table) {
            $table->renameColumn('stok_masuk', 'produk_masuk');
            $table->renameColumn('stok_keluar', 'produk_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('riwayat_stok_produk', function (Blueprint $table) {
            $table->renameColumn('produk_masuk', 'stok_masuk');
            $table->renameColumn('produk_keluar', 'stok_keluar');
        });
    }
};
