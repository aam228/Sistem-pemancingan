<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $table = 'spots';

    protected $fillable = [
        'user_id',
        'nama_spot',
        'status',
        'tarif_pagi',
        'tarif_siang',
        'tarif_sore',
        'tarif_malam'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'spot_id');
    }
}