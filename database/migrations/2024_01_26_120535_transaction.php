<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Transaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('alamat');
            $table->string('longitude');
            $table->string('latitude');
            $table->integer('jumlah_antena');
            $table->string('frekuensi');
            $table->string('teknologi_jaringan');
            $table->string('luas_jaringan');
            $table->integer('kapasitas_user');
            $table->longText('informasi_lain')->nullable();
            $table->timestamps();
        });

        Schema::create('report', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bts');
            $table->string('tingkat_kepentingan');
            $table->string('kategori');
            $table->longText('deskripsi');
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
        Schema::dropIfExists('bts');
        Schema::dropIfExists('report');
    }
}
