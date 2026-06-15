<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    use HasFactory;

    public const STATUS_OUVERT = 'ouvert';

    public const STATUS_EN_COURS = 'en_cours';

    public const STATUS_RESOLU = 'resolu';

    public const STATUS_FERME = 'ferme';

    public const STATUS_LABELS = [
        self::STATUS_OUVERT => 'Ouvert',
        self::STATUS_EN_COURS => 'En cours',
        self::STATUS_RESOLU => 'Résolu',
        self::STATUS_FERME => 'Fermé',
    ];

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
    ];

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status)),
        );
    }

    public static function statusOptions(): array
    {
        return self::STATUS_LABELS;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
