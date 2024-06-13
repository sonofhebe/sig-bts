<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalSelesaiToReportTable extends Migration
{
    public function up()
    {
        Schema::table('report', function (Blueprint $table) {
            $table->datetime('tanggal_selesai')->nullable()->after('catatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai');
        });
    }
}
