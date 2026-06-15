<?php

namespace App\Filament\Resources\CommandeResource\Pages;

use App\Filament\Resources\CommandeResource;
use App\Models\Livraison;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommande extends EditRecord
{
    protected static string $resource = CommandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $commande = $this->record->fresh(['livraison']);
        $status = $commande->status;

        $livraison = $commande->livraison ?? Livraison::create([
            'commande_id' => $commande->id,
            'status' => 'en_attente',
        ]);

        match ($status) {
            'confirmee' => $livraison->update([
                'status' => 'en_cours',
                'shipped_at' => $livraison->shipped_at ?? now(),
            ]),
            'livree' => $livraison->update([
                'status' => 'livree',
                'shipped_at' => $livraison->shipped_at ?? now(),
                'delivered_at' => now(),
            ]),
            'annulee' => $livraison->update([
                'status' => 'en_attente',
                'delivered_at' => null,
            ]),
            'en_attente' => $livraison->update([
                'status' => 'en_attente',
            ]),
            default => null,
        };
    }
}
