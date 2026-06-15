<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormationResource\Pages;
use App\Models\Formation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class FormationResource extends Resource
{
    protected static ?string $model = Formation::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Formations';

    protected static ?string $navigationLabel = 'Formations';

    protected static ?string $modelLabel = 'formation';

    protected static ?string $pluralModelLabel = 'formations';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Formation')
                    ->schema([
                        Forms\Components\Select::make('artisan_id')
                            ->label('Artisan')
                            ->relationship('artisan', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->name ?? 'Artisan #'.$record->id)
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('date_debut')
                            ->label('Date de début')
                            ->required(),

                        Forms\Components\TextInput::make('city')
                            ->label('Ville')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('is_free')
                            ->label('Gratuite')
                            ->live(),

                        Forms\Components\TextInput::make('price')
                            ->label('Prix (MAD)')
                            ->numeric()
                            ->prefix('MAD')
                            ->default(0)
                            ->visible(fn (Forms\Get $get): bool => ! $get('is_free')),

                        Forms\Components\TextInput::make('max_participants')
                            ->label('Places max')
                            ->numeric()
                            ->default(10)
                            ->required(),

                        Forms\Components\TextInput::make('current_participants')
                            ->label('Participants inscrits')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('formations')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Participants')
                    ->schema([
                        Forms\Components\Placeholder::make('participants_list')
                            ->label('')
                            ->content(function (?Formation $record): HtmlString|string {
                                if (! $record) {
                                    return 'Les participants apparaîtront après la création.';
                                }

                                $enrollments = $record->enrollments()->with('user')->get();

                                if ($enrollments->isEmpty()) {
                                    return 'Aucun participant inscrit.';
                                }

                                $lines = $enrollments
                                    ->map(fn ($enrollment) => sprintf(
                                        '• %s — %s (%s)',
                                        $enrollment->user?->name ?? 'Utilisateur inconnu',
                                        $enrollment->user?->email ?? '—',
                                        $enrollment->status
                                    ))
                                    ->implode('<br>');

                                return new HtmlString($lines);
                            })
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (?Formation $record): bool => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('artisan.user.name')
                    ->label('Artisan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ville')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_debut')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_participants')
                    ->label('Participants')
                    ->formatStateUsing(fn ($state, Formation $record): string => $state.' / '.$record->max_participants),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),

                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('Gratuite'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date_debut', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormations::route('/'),
            'create' => Pages\CreateFormation::route('/create'),
            'edit' => Pages\EditFormation::route('/{record}/edit'),
        ];
    }
}
