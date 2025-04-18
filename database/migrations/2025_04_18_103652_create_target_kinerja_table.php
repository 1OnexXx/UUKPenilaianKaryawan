<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetKinerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_kinerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id')->nullable(); // Bisa NULL jika untuk semua karyawan
            $table->unsignedBigInteger('divisi_id')->nullable(); // Bisa NULL jika tidak spesifik ke divisi
            $table->string('periode'); // Misal: 'April 2025'
            $table->string('judul_target'); // Misal: 'Laporan Keuangan'
            $table->integer('target_laporan'); // Jumlah lampiran/jurnal yang harus dilaporkan
            $table->date('deadline'); // Deadline target
            $table->unsignedBigInteger('dibuat_oleh'); // Admin/Atasan yang membuat
            $table->timestamps();

            // Menambahkan foreign key
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('set null');
            $table->foreign('divisi_id')->references('id')->on('divisi')->onDelete('set null');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('target_kinerja');
    }
}
