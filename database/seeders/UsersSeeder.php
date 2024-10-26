<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'    => 'admin',
            'email'    => 'admin@gmail.com',
            'email_verified_at'    => date('Y-m-d H:i:s'),
            'password'    => Hash::make('12345678'),
            'role'    => 'admin'
        ]);
        User::create([
            'name'    => 'owner',
            'email'    => 'owner@gmail.com',
            'email_verified_at'    => date('Y-m-d H:i:s'),
            'password'    => Hash::make('12345678'),
            'role'    => 'owner'
        ]);
        Kategori::create([
            'jenis_wisata_kuliner'    => 'Makanan Ringan'
        ]);
        Kategori::create([
            'jenis_wisata_kuliner'    => 'Makanan Berat'
        ]);
        Kategori::create([
            'jenis_wisata_kuliner'    => 'Makanan Kering'
        ]);
        Kategori::create([
            'jenis_wisata_kuliner'    => 'Makanan Basah'
        ]);
    }
}
