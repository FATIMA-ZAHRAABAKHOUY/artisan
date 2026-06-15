<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LivraisonResource\Pages;
use App\Models\Livraison;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LivraisonResource extends Resource
{
    protected static ?string $model = Livraison::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Gestion';

    protected static ?string $navigationLabel = 'Livraisons';

    protected static ?string $modelLabel = 'livraison';

    protected static ?string $pluralModelLabel = 'livraisons';

    protected static ?int $navigationSort = 5;

    public static function getStatusOptions(): array
    {
        return [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'livree' => 'Livrée',
        ];
    }

    public static function getCarrierOptions(): array
    {
        return [
            'Amana' => 'Amana',
            'CTM Express' => 'CTM Express',
            'Colis Privé' => 'Colis Privé',
            'DHL' => 'DHL',
            'Autre' => 'Autre',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Livraison')
                    ->schema([
                        Forms\Components\Select::make('commande_id')
                            ->label('Commande')
                            ->relationship('commande', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => 'Commande #'.$record->id.' — '.$record->user?->name)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (?Livraison $record): bool => $record !== null)
                            ->dehydrated(),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('N° de suivi')
                            ->maxLength(255),

                        Forms\Components\Select::make('carrier')
                            ->label('Transporteur')
                            ->options(static::getCarrierOptions())
                            ->searchable()
                            ->native(false),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options(static::getStatusOptions())
                            ->required()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('shipped_at')
                            ->label('Expédiée le'),

                        Forms\Components\DateTimePicker::make('delivered_at')
                            ->label('Livrée le'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('commande.id')
                    ->label('Commande')
                    ->formatStateUsing(fn ($state) => '#'.$state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('commande.user.name')
                    ->label('Client')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Suivi')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('carrier')
                    ->label('Transporteur')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::getStatusOptions()[$state] ?? ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'en_attente' => 'warning',
                        'en_cours' => 'info',
                        'livree' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('shipped_at')
                    ->label('Expédiée')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('delivered_at')
                    ->label('Livrée')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options(static::getStatusOptions()),

                Tables\Filters\SelectFilter::make('carrier')
                    ->label('Transporteur')
                    ->options(static::getCarrierOptions()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLivraisons::route('/'),
            'create' => Pages\CreateLivraison::route('/create'),
            'edit' => Pages\EditLivraison::route('/{record}/edit'),
        ];
    }
}
