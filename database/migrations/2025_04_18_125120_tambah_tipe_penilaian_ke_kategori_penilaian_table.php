<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->enum('tipe_penilaian', ['objektif', 'subjektif'])->default('objektif')->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->dropColumn('tipe_penilaian');
        });
    }
};
