<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    // Tambahkan 'user_id' ke dalam $fillable
    protected $fillable = ['user_id', 'nama_produk', 'harga', 'gambar'];

    /**
     * Dapatkan user pemilik produk ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}