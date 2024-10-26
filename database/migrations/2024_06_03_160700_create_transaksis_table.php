<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_referensi_transaksi');
            $table->date('tgl_transaksi');
            $table->string('total_harga');
            $table->enum(
                'status_transaksi',
                [
                    '0', # In Progress
                    '1', # Completed
                    '2' # Cancelled
                ]
            )->default('0');
            $table->foreignId('id_wisata_kuliner')->constrained('wisata_kuliners')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('transaksis');
    }
}
