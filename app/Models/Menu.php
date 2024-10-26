<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto_kuliner',
        'nama_kuliner',
        'harga',
        'deskripsi',
        'id_wisata_kuliner'
    ];
}
