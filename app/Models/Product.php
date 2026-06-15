<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'artisan_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'weight',
        'dimensions',
        'material',
        'is_featured',
        'is_active',
        'main_image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function commandeItems(): HasMany
    {
        return $this->hasMany(CommandeItem::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getEmojiFallback(): string
    {
        $material = strtolower($this->material ?? '');
        $category = strtolower($this->category?->name ?? '');

        if (str_contains($material, 'tapis') || str_contains($category, 'tapis')) {
            return '🧵';
        }
        if (str_contains($material, 'brod') || str_contains($category, 'broder')) {
            return '🪡';
        }
        if (str_contains($material, 'teint') || str_contains($category, 'teintur')) {
            return '🌿';
        }
        if (str_contains($material, 'djellaba') || str_contains($category, 'djellaba')) {
            return '✂️';
        }
        if (str_contains($category, 'kilim')) {
            return '🎨';
        }
        if (str_contains($category, 'céramique') || str_contains($category, 'ceramique')) {
            return '🏺';
        }

        return '🧵';
    }

    public function scopeFromVerifiedArtisan(Builder $query): Builder
    {
        return $query->whereHas('artisan', fn (Builder $q) => $q->where('is_verified', true));
    }

    public function scopePublicCatalogue(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->fromVerifiedArtisan();
    }
}
