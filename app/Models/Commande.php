<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_ht',
        'tva',
        'total_ttc',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_ht' => 'decimal:2',
            'tva' => 'decimal:2',
            'total_ttc' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CommandeItem::class);
    }

    public function livraison(): HasOne
    {
        return $this->hasOne(Livraison::class);
    }
}
