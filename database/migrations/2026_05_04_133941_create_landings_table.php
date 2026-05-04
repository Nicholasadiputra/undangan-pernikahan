<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('landings', function (Blueprint $table) {
            $table->id();
            $table->string('template')->default('bohemian');
            $table->string('custom_html')->nullable();
            $table->boolean('show_animation')->default(true);
            $table->boolean('play_music')->default(true);
            $table->boolean('show_guest_name')->default(true);
            $table->boolean('is_private')->default(false);
            $table->string('groom_name')->nullable();
            $table->string('bride_name')->nullable();
            $table->date('wedding_date')->nullable();
            $table->string('lokasi_wedding')->nullable();
            $table->string('kota')->nullable();
            $table->text('map_iframe')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('landings');
    }
};