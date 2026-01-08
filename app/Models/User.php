<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'theme',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function spots()
    {
        return $this->hasMany(Spot::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    public function pesananMakanan()
    {
        return $this->hasMany(PesananMakanan::class);
    }
}