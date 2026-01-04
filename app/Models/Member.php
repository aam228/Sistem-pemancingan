<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
    'kode_member', 'nama', 'telepon', 'poin', 'diskon_persen', 'expired_at', 'status'
    ];

    protected $casts = [
    'expired_at' => 'date',
    ];

    public function transaksis() {
        return $this->hasMany(Transaksi::class);
    }
}