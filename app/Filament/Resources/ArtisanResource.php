<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtisanResource\Pages;
use App\Models\Artisan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArtisanResource extends Resource
{
    protected static ?string $model = Artisan::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Gestion';

    protected static ?string $navigationLabel = 'Artisans';

    protected static ?string $modelLabel = 'artisan';

    protected static ?string $pluralModelLabel = 'artisans';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profil artisan')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship(
                                'user',
                                'name',
                                fn ($query) => $query->where('role', 'artisan')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nom')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email'),
                                Forms\Components\TextInput::make('password')
                                    ->label('Mot de passe')
                                    ->password()
                                    ->required()
                                    ->minLength(8),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return User::create([
                                    'name' => $data['name'],
                                    'email' => $data['email'],
                                    'password' => $data['password'],
                                    'role' => 'artisan',
                                    'is_active' => true,
                                ])->id;
                            }),

                        Forms\Components\TextInput::make('specialty')
                            ->label('Spécialité')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->label('Ville')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('bio')
                            ->label('Biographie')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_verified')
                            ->label('Vérifié')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('specialty')
                    ->label('Spécialité')
                    ->searchable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ville')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Vérifié')
                    ->boolean(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Note')
                    ->numeric(decimalPlaces: 2),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscription')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Vérifié'),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Valider')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Artisan $record): bool => ! $record->is_verified)
                    ->requiresConfirmation()
                    ->modalHeading('Valider l\'artisan')
                    ->action(function (Artisan $record): void {
                        $record->update(['is_verified' => true]);

                        Notification::make()
                            ->title('Artisan validé avec succès')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtisans::route('/'),
            'create' => Pages\CreateArtisan::route('/create'),
            'edit' => Pages\EditArtisan::route('/{record}/edit'),
        ];
    }
}
