<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndCatatanToReportTable extends Migration
{
    public function up()
    {
        Schema::table('report', function (Blueprint $table) {
            $table->string('status')->default('terdaftar')->after('deskripsi');
            $table->text('catatan')->nullable()->after('status')->nullable();
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
            $table->dropColumn('status');
            $table->dropColumn('catatan');
        });
    }
}
