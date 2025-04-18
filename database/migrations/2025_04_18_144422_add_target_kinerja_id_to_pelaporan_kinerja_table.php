<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pelaporan_kinerja', function (Blueprint $table) {
            $table->unsignedBigInteger('target_kinerja_id')->after('karyawan_id');

            $table->foreign('target_kinerja_id')
                  ->references('id')
                  ->on('target_kinerja')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaporan_kinerja', function (Blueprint $table) {
            $table->dropForeign(['target_kinerja_id']);
            $table->dropColumn('target_kinerja_id');
        });
    }
};
