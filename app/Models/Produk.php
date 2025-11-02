<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'nama',
        'harga',
        'keterangan',
        'jumlah_produk',
    ];

    public function kategori() : BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function riwayatStokProduk() : HasMany
    {
        return $this->hasMany(RiwayatStokProduk::class, 'produk_id', 'id');
    }

    public function jenisProduk() : HasMany
    {
        return $this->hasMany(JenisProduk::class, 'produk_id', 'id');
    }

    public function gambarProduk() : HasMany
    {
        return $this->hasMany(GambarProduk::class, 'produk_id', 'id');
    }

    public function invoice() : HasMany
    {
        return $this->hasMany(Invoice::class, 'produk_id', 'id');
    }

    public function itemTransaksi() : HasMany
    {
        return $this->hasMany(ItemTransaksi::class, 'produk_id', 'id');
    }
}
