<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->string('color_primary')->default('#321E04')->after('template');
            $table->string('color_accent')->default('#C9A96E')->after('color_primary');
            $table->string('color_mid')->default('#7A5C3A')->after('color_accent');
            $table->string('color_bg')->default('#f5f1eb')->after('color_mid');
        });
    }

    public function down(): void
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->dropColumn(['color_primary', 'color_accent', 'color_mid', 'color_bg']);
        });
    }
};
