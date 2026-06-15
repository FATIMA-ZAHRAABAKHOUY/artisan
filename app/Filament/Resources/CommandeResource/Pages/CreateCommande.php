<?php

namespace App\Filament\Resources\CommandeResource\Pages;

use App\Filament\Resources\CommandeResource;
use App\Models\Livraison;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCommande extends CreateRecord
{
    protected static string $resource = CommandeResource::class;

    protected function afterCreate(): void
    {
        Livraison::create([
            'commande_id' => $this->record->id,
            'status' => 'en_attente',
        ]);
    }
}
