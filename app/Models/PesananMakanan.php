<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananMakanan extends Model
{
    protected $table = 'pesanan_makanan';

    protected $fillable = [
        'user_id',
        'transaksi_id',
        'spot_id',
        'produk_id',
        'jumlah',
        'subtotal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}