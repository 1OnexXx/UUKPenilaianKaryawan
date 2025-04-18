<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // Di file migrasi
    public function up()
    {
        Schema::table('pelaporan_kinerja', function (Blueprint $table) {
            // Drop foreign key dulu
            $table->dropForeign(['target_id']);
            
            // Baru drop kolomnya
            $table->dropColumn('target_id');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaporan_kinerja', function (Blueprint $table) {
            $table->unsignedBigInteger('target_id')->nullable(); // atau sesuaikan tipenya
        });
    }
    
};
