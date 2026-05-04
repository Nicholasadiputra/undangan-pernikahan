<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    protected $table = 'landings';
    protected $fillable = [
    'template',
    'hero_image',
    'custom_html',
    'show_animation',
    'play_music',
    'show_guest_name',
    'is_private',
    'groom_name',
    'bride_name',
    'wedding_date'
];
}