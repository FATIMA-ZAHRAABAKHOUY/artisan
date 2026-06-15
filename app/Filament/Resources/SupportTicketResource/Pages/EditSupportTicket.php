<?php

namespace App\Filament\Resources\SupportTicketResource\Pages;

use App\Filament\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSupportTicket extends EditRecord
{
    protected static string $resource = SupportTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('en_cours')
                ->label('Marquer en cours')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->visible(fn (): bool => $this->record->status === SupportTicket::STATUS_OUVERT)
                ->action(function (): void {
                    $this->record->update(['status' => SupportTicket::STATUS_EN_COURS]);
                    $this->refreshFormData(['status']);

                    Notification::make()
                        ->title('Ticket marqué en cours')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('resoudre')
                ->label('Résoudre')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->status, [SupportTicket::STATUS_OUVERT, SupportTicket::STATUS_EN_COURS], true))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update(['status' => SupportTicket::STATUS_RESOLU]);
                    $this->refreshFormData(['status']);

                    Notification::make()
                        ->title('Ticket résolu')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('fermer')
                ->label('Fermer')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => $this->record->status !== SupportTicket::STATUS_FERME)
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update(['status' => SupportTicket::STATUS_FERME]);
                    $this->refreshFormData(['status']);

                    Notification::make()
                        ->title('Ticket fermé')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}
