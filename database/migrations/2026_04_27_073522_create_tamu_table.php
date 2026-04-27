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
        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['Keluarga', 'Teman', 'Rekan'])->default('Teman');
            $table->integer('pax')->nullable();
            $table->enum('status', ['Hadir', 'Tidak Hadir', 'Menunggu'])->default('Menunggu');
            $table->text('ucapan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamu');
    }
};
