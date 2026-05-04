<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    protected $table = 'landings';
    protected $fillable = [
        'title', 
        'description', 
        'hero_image'
    ];
}