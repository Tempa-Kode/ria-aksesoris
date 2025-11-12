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
        // Hapus tabel gambar_produk
        Schema::dropIfExists('gambar_produk');

        // Tambahkan 3 kolom gambar ke tabel produk
        Schema::table('produk', function (Blueprint $table) {
            $table->string('gambar_1', 100)->nullable()->after('keterangan');
            $table->string('gambar_2', 100)->nullable()->after('gambar_1');
            $table->string('gambar_3', 100)->nullable()->after('gambar_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan tabel gambar_produk
        Schema::create('gambar_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->string('path_gambar', 100);
            $table->timestamps();
        });

        // Hapus kolom gambar dari tabel produk
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['gambar_1', 'gambar_2', 'gambar_3']);
        });
    }
};
