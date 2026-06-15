<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';

    protected static ?string $navigationGroup = 'Config';

    protected static ?string $navigationLabel = 'Support';

    protected static ?string $modelLabel = 'ticket support';

    protected static ?string $pluralModelLabel = 'tickets support';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ticket')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Sujet')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Placeholder::make('user_info')
                            ->label('Utilisateur')
                            ->content(fn (?SupportTicket $record): string => $record?->user
                                ? $record->user->name.' ('.$record->user->email.')'
                                : '—'),

                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->rows(6)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options(SupportTicket::statusOptions())
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N°')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Sujet')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SupportTicket::statusOptions()[$state] ?? ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        SupportTicket::STATUS_OUVERT => 'warning',
                        SupportTicket::STATUS_EN_COURS => 'info',
                        SupportTicket::STATUS_RESOLU => 'success',
                        SupportTicket::STATUS_FERME => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options(SupportTicket::statusOptions()),
            ])
            ->actions([
                Tables\Actions\Action::make('en_cours')
                    ->label('En cours')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn (SupportTicket $record): bool => $record->status === SupportTicket::STATUS_OUVERT)
                    ->action(function (SupportTicket $record): void {
                        $record->update(['status' => SupportTicket::STATUS_EN_COURS]);

                        Notification::make()
                            ->title('Ticket marqué en cours')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('resoudre')
                    ->label('Résoudre')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (SupportTicket $record): bool => in_array($record->status, [SupportTicket::STATUS_OUVERT, SupportTicket::STATUS_EN_COURS], true))
                    ->requiresConfirmation()
                    ->action(function (SupportTicket $record): void {
                        $record->update(['status' => SupportTicket::STATUS_RESOLU]);

                        Notification::make()
                            ->title('Ticket résolu')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('fermer')
                    ->label('Fermer')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (SupportTicket $record): bool => $record->status !== SupportTicket::STATUS_FERME)
                    ->requiresConfirmation()
                    ->action(function (SupportTicket $record): void {
                        $record->update(['status' => SupportTicket::STATUS_FERME]);

                        Notification::make()
                            ->title('Ticket fermé')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
