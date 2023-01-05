<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooker extends Model
{  
    protected $fillable = [
        'user_id', 'firstname', 'lastname', 'slug', 'img',
    ];
}