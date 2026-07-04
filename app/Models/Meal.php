<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'photo', 'eaten_at'];

    protected $casts = ['eaten_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MealComment::class)->with('user')->latest();
    }

    public function averageRating(): ?float
    {
        $ratings = $this->comments->whereNotNull('rating');
        return $ratings->count() ? round($ratings->avg('rating'), 1) : null;
    }

    public function userComment(int $userId): ?MealComment
    {
        return $this->comments->firstWhere('user_id', $userId);
    }
}
