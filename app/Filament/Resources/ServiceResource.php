<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Service-uri';
    protected static ?string $modelLabel = 'Service';
    protected static ?string $pluralModelLabel = 'Service-uri';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Utilizator asociat')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->hidden(fn () => auth()->user()?->isService()),
                TextInput::make('name')
                    ->label('Nume service')
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Adresă')
                    ->required()
                    ->maxLength(255),
                TextInput::make('city')
                    ->label('Oraș')
                    ->required()
                    ->maxLength(100),
                TextInput::make('lat')
                    ->label('Latitudine')
                    ->numeric()
                    ->step(0.000001),
                TextInput::make('lng')
                    ->label('Longitudine')
                    ->numeric()
                    ->step(0.000001),
                TextInput::make('rating')
                    ->label('Rating')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(5)
                    ->step(0.1)
                    ->hidden(fn () => auth()->user()?->isService()),
                TextInput::make('phone')
                    ->label('Telefon')
                    ->tel()
                    ->maxLength(20),
                Textarea::make('description')
                    ->label('Descriere')
                    ->rows(3)
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nume')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Oraș')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon'),
                Tables\Columns\TextColumn::make('interventions_count')
                    ->label('Intervenții')
                    ->counts('interventions')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->label('Oraș')
                    ->options(fn () => Service::distinct()->pluck('city', 'city')->toArray()),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn () => auth()->user()?->isService()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Service users see only their own service, admin sees all
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isService()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    // Service users cannot create new services (they already have one)
    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
