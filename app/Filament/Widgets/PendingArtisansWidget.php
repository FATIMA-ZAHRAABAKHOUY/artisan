<?php

namespace App\Filament\Widgets;

use App\Models\Artisan;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingArtisansWidget extends BaseWidget
{
    protected static ?string $heading = 'Artisans en attente de validation';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Artisan::query()
                    ->with('user')
                    ->where('is_verified', false)
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nom')
                    ->searchable(),

                Tables\Columns\TextColumn::make('specialty')
                    ->label('Spécialité'),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ville'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscription')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('valider')
                    ->label('Valider')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Valider l\'artisan')
                    ->modalDescription('Confirmer la vérification de ce profil artisan ?')
                    ->action(function (Artisan $record): void {
                        $record->update(['is_verified' => true]);

                        Notification::make()
                            ->title('Artisan validé')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Aucun artisan en attente')
            ->emptyStateDescription('Tous les profils artisans ont été vérifiés.')
            ->paginated(false);
    }
}
