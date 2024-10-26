<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWisataKulinersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wisata_kuliners', function (Blueprint $table) {
            $table->id();
            $table->string('foto_wisata_kuliner')->nullable();
            $table->string('nama_wisata_kuliner');
            $table->text('alamat');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('is_verified', ['0', '1'])->default('0');
            $table->foreignId('id_kategori')->constrained('kategoris')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wisata_kuliners');
    }
}
