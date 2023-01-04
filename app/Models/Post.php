<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{  
    protected $fillable = [
        'user_id', 'cooker_id', 'title', 'description', 'difficulty', 'price', 'time', 'ingredient', 'step', 'person', 'slug', 'img', 'visibility',
    ];
}