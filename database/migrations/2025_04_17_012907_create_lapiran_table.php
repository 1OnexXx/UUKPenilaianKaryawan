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
        Schema::create('lapiran', function (Blueprint $table) {
            $table->id();
            $table->morphs('lampiranable'); // otomatis buat lampiranable_id dan lampiranable_type
            $table->string('file_path');
            $table->enum('file_type', ['image', 'pdf', 'doc', 'video']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapiran');
    }
};
