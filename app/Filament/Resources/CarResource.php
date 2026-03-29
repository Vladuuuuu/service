<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Mașini';
    protected static ?string $modelLabel = 'Mașină';
    protected static ?string $pluralModelLabel = 'Mașini';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Proprietar')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('brand')
                    ->label('Marca')
                    ->required()
                    ->maxLength(100),
                TextInput::make('model')
                    ->label('Model')
                    ->required()
                    ->maxLength(100),
                TextInput::make('year')
                    ->label('An fabricație')
                    ->numeric()
                    ->required()
                    ->minValue(1900)
                    ->maxValue(date('Y') + 1),
                TextInput::make('plate')
                    ->label('Nr. înmatriculare')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),
                TextInput::make('km_current')
                    ->label('Kilometraj actual')
                    ->numeric()
                    ->required()
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('An')
                    ->sortable(),
                Tables\Columns\TextColumn::make('plate')
                    ->label('Nr. înmatriculare')
                    ->searchable(),
                Tables\Columns\TextColumn::make('km_current')
                    ->label('Km')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Proprietar')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->label('Marca')
                    ->options(fn () => Car::distinct()->pluck('brand', 'brand')->toArray()),
            ])
            ->actions([
                ViewAction::make(),
            ]);
    }

    // Service users pot doar vizualiza mașinile - fără create/edit/delete
    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isService() && $user->service) {
            $query->whereHas('interventions', function ($q) use ($user) {
                $q->where('service_id', $user->service->id);
            });
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCars::route('/'),
        ];
    }
}
