<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{  
    protected $fillable = [
        'user_id', 'cooker_id', 'title', 'description', 'difficulty', 'price', 'time', 'ingredient', 'step', 'person', 'slug', 'img', 'visibility',
    ];

    // récupérer les comments associé à un post
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'desc');
    }

    // accéder à la table cooker depuis post
    public function cooker(): BelongsTo
    {
        return $this->belongsTo(Cooker::class);
    }
}