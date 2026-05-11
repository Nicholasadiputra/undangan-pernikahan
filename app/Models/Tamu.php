<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tamu extends Model
{
    protected $table = 'tamu';
    protected $fillable = ['nama', 'kategori', 'pax', 'status', 'ucapan', 'slug'];

    public static function slugify(string $nama): string
    {
        $slug = Str::slug($nama);
        return $slug ?: 'tamu';
    }

    public static function makeUniqueSlug(string $nama, ?int $excludeId = null): string
    {
        $slug = static::slugify($nama);
        $baseSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
