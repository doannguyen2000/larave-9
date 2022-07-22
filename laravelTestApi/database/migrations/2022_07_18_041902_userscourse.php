<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('userscourse', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
        });

        Schema::table('userscourse', function (Blueprint $table) {
            $table->foreignId('userID')->constrained('user')->onDelete('cascade');
            $table->foreignId('courseID')->constrained('course')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('userscourse');
    }
};
