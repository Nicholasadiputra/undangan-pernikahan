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
        Schema::table('landings', function (Blueprint $table) {
            $table->string('dresscode_text')->nullable();
            $table->text('cerita_bertemu')->nullable();
            $table->text('cerita_melamar')->nullable();
            $table->json('kegiatan')->nullable();
            $table->json('palette_colors')->nullable();
            $table->string('custom_thumbnail')->nullable();
            $table->json('gallery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->dropColumn([
                'dresscode_text',
                'cerita_bertemu',
                'cerita_melamar',
                'kegiatan',
                'palette_colors',
                'custom_thumbnail',
                'gallery',
            ]);
        });
    }
};
