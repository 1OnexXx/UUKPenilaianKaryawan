<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pelaporan_kinerja', function (Blueprint $table) {
            // Menambahkan kolom target_id dengan tipe UNSIGNED BIGINT
            $table->unsignedBigInteger('target_id');
            
            // Menambahkan kolom jumlah_laporan dengan tipe INTEGER
            $table->integer('jumlah_laporan');
            
            // Menambahkan kolom skor_objektif dengan tipe INTEGER
            $table->integer('skor_objektif')->nullable(); // Nullable jika skor tidak selalu ada

            // Menambahkan foreign key untuk target_id yang merujuk ke tabel target_kinerja(id)
            $table->foreign('target_id')->references('id')->on('target_kinerja')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaporan_kinerja', function (Blueprint $table) {
            // Menghapus foreign key dan kolom jika rollback
            $table->dropForeign(['target_id']); // Menghapus foreign key
            $table->dropColumn(['target_id', 'jumlah_laporan', 'skor_objektif']); // Menghapus kolom
        });
    }
};
