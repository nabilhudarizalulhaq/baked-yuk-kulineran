<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_referensi_transaksi',
        'tgl_transaksi',
        'total_harga',
        'status_transaksi',
        'id_wisata_kuliner',
        'id_user'
    ];
}
