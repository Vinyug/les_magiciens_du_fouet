<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cooker extends Model
{  
    protected $fillable = [
        'user_id', 'firstname', 'lastname', 'slug', 'img',
    ];

    // récupérer les posts associé à un cooker
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderBy('id', 'desc');
    }
}