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
        Schema::table('landings', function (Blueprint $table) {
            $table->string('ayah_pria')->nullable();
            $table->string('ibu_pria')->nullable();
            $table->string('ayah_wanita')->nullable();
            $table->string('ibu_wanita')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->dropColumn(['ayah_pria', 'ibu_pria', 'ayah_wanita', 'ibu_wanita']);
        });
    }
};
