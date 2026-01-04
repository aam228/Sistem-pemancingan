<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'user_id',
        'spot_id',
        'member_id',
        'payment_method_id',
        'nama_pelanggan',
        'tipe_sesi',
        'durasi',
        'total_harga',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_ikan_kecil',
        'berat_ikan_babon'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function pesananMakanan()
    {
        return $this->hasMany(PesananMakanan::class, 'transaksi_id');
    }
}