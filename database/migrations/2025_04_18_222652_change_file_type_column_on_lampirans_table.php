<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFileTypeColumnOnLampiransTable extends Migration
{
    public function up()
    {
        Schema::table('lapiran', function (Blueprint $table) {
            $table->string('file_type')->change();
        });
    }

    public function down()
    {
        Schema::table('lapiran', function (Blueprint $table) {
            $table->enum('file_type', ['image', 'pdf', 'doc', 'video'])->change();
        });
    }
}
