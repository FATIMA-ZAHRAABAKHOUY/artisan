<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandeResource\Pages;
use App\Models\Commande;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommandeResource extends Resource
{
    protected static ?string $model = Commande::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Gestion';

    protected static ?string $navigationLabel = 'Commandes';

    protected static ?string $modelLabel = 'commande';

    protected static ?string $pluralModelLabel = 'commandes';

    protected static ?int $navigationSort = 4;

    public static function getStatusOptions(): array
    {
        return [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Commande')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Client')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options(static::getStatusOptions())
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('payment_method')
                            ->label('Mode de paiement')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('total_ht')
                            ->label('Total HT')
                            ->numeric()
                            ->prefix('MAD')
                            ->required(),

                        Forms\Components\TextInput::make('tva')
                            ->label('TVA')
                            ->numeric()
                            ->prefix('MAD')
                            ->required(),

                        Forms\Components\TextInput::make('total_ttc')
                            ->label('Total TTC')
                            ->numeric()
                            ->prefix('MAD')
                            ->required(),

                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Adresse de livraison')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('shipping_city')
                            ->label('Ville')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_postal_code')
                            ->label('Code postal')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Articles')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produit')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->disabled(fn (?Commande $record): bool => $record !== null)
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Quantité')
                                    ->numeric()
                                    ->required()
                                    ->disabled(fn (?Commande $record): bool => $record !== null)
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Prix unitaire')
                                    ->numeric()
                                    ->prefix('MAD')
                                    ->required()
                                    ->disabled(fn (?Commande $record): bool => $record !== null)
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Sous-total')
                                    ->numeric()
                                    ->prefix('MAD')
                                    ->required()
                                    ->disabled(fn (?Commande $record): bool => $record !== null)
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->addable(fn (?Commande $record): bool => $record === null)
                            ->deletable(fn (?Commande $record): bool => $record === null)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N°')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::getStatusOptions()[$state] ?? ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'en_attente' => 'warning',
                        'confirmee' => 'info',
                        'livree' => 'success',
                        'annulee' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_ttc')
                    ->label('Total TTC')
                    ->money('MAD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_city')
                    ->label('Ville')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options(static::getStatusOptions()),
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
            'index' => Pages\ListCommandes::route('/'),
            'create' => Pages\CreateCommande::route('/create'),
            'edit' => Pages\EditCommande::route('/{record}/edit'),
        ];
    }
}
