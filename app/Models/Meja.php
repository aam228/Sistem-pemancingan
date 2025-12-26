<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';

    protected $fillable = [
        'user_id',
        'nama_meja',
        'status',
        'tarif_pagi',
        'tarif_siang',
        'tarif_sore',
        'tarif_malam',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'meja_id');
    }

    /**
     * Transaksi yang masih aktif berdasarkan waktu sesi
     */
    public function getTransaksiAktifAttribute()
    {
        return $this->transaksis()
            ->where(function ($q) {
                $q->whereNull('waktu_selesai')
                  ->orWhere('waktu_selesai', '>', now());
            })
            ->orderBy('waktu_mulai', 'desc')
            ->first();
    }
}
