<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nama')->unique();
        });

        $rows = DB::table('tamu')->get();
        foreach ($rows as $row) {
            $slug = Str::slug($row->nama) ?: 'tamu';
            $baseSlug = $slug;
            $counter = 1;

            while (DB::table('tamu')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            DB::table('tamu')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
