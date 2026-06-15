<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artisan extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
        'city',
        'bio',
        'is_verified',
        'rating',
        'total_reviews',
        'avatar',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'rating' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }
}
