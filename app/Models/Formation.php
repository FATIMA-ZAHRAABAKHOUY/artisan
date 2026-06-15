<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formation extends Model
{
    protected $fillable = [
        'artisan_id',
        'title',
        'description',
        'date_debut',
        'city',
        'price',
        'max_participants',
        'current_participants',
        'is_free',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'price' => 'decimal:2',
            'is_free' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(FormationEnrollment::class);
    }

    public function hasAvailableSpots(): bool
    {
        return $this->current_participants < $this->max_participants;
    }

    protected function remainingSeats(): Attribute
    {
        return Attribute::make(
            get: fn (): int => max(0, $this->max_participants - $this->current_participants),
        );
    }

    protected function progressPercent(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->max_participants > 0
                ? (int) round(($this->current_participants / $this->max_participants) * 100)
                : 0,
        );
    }
}
