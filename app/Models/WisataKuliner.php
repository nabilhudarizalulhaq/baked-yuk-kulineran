<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WisataKuliner extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto_wisata_kuliner',
        'nama_wisata_kuliner',
        'alamat',
        'latitude',
        'longitude',
        'is_verified',
        'id_kategori',
        'id_user'
    ];
}
