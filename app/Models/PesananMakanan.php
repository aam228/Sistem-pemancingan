<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananMakanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan_makanan';
    // Tambahkan 'user_id' ke dalam $fillable
    protected $fillable = ['user_id', 'transaksi_id', 'meja_id', 'produk_id', 'jumlah', 'subtotal'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Dapatkan user pemilik pesanan makanan ini.
     */
    public function user() // <-- TAMBAHKAN FUNGSI INI
    {
        return $this->belongsTo(User::class);
    }
}