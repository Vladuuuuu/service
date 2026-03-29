<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InterventionResource\Pages;
use App\Models\Intervention;
use App\Models\Invoice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InterventionResource extends Resource
{
    protected static ?string $model = Intervention::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Intervenții';
    protected static ?string $modelLabel = 'Intervenție';
    protected static ?string $pluralModelLabel = 'Intervenții';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('car_id')
                    ->label('Mașină')
                    ->relationship('car', 'plate')
                    ->searchable()
                    ->required(),
                Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->required()
                    ->default(fn () => auth()->user()?->service?->id)
                    ->hidden(fn () => auth()->user()?->isService()),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'În așteptare',
                        'in_progress' => 'În lucru',
                        'completed' => 'Finalizat',
                        'cancelled' => 'Anulat',
                    ])
                    ->required()
                    ->default('pending'),
                Select::make('type')
                    ->label('Tip intervenție')
                    ->options([
                        'ulei' => 'Schimb ulei',
                        'revizie' => 'Revizie',
                        'frane' => 'Frâne',
                        'general' => 'General',
                    ])
                    ->required()
                    ->default('general'),
                Textarea::make('description')
                    ->label('Descriere lucrare')
                    ->required()
                    ->rows(3),
                TextInput::make('estimated_hours')
                    ->label('Ore estimate')
                    ->numeric()
                    ->step(0.5),
                TextInput::make('final_cost')
                    ->label('Cost final (RON)')
                    ->numeric()
                    ->prefix('RON'),
                TextInput::make('km_at_intervention')
                    ->label('Km la intervenție')
                    ->numeric(),
                DatePicker::make('scheduled_at')
                    ->label('Data programării'),
                DatePicker::make('completed_at')
                    ->label('Data finalizării'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('car.plate')
                    ->label('Mașină')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'ulei' => 'warning',
                        'revizie' => 'info',
                        'frane' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'În așteptare',
                        'in_progress' => 'În lucru',
                        'completed' => 'Finalizat',
                        'cancelled' => 'Anulat',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('final_cost')
                    ->label('Cost')
                    ->money('RON')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Data')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'În așteptare',
                        'in_progress' => 'În lucru',
                        'completed' => 'Finalizat',
                        'cancelled' => 'Anulat',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tip')
                    ->options([
                        'ulei' => 'Schimb ulei',
                        'revizie' => 'Revizie',
                        'frane' => 'Frâne',
                        'general' => 'General',
                    ]),
            ])
            ->actions([
                // Acțiune: Start lucrare
                Action::make('start')
                    ->label('Start')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Intervention $record): bool => $record->status === 'pending')
                    ->action(fn (Intervention $record) => $record->update(['status' => 'in_progress'])),

                // Acțiune: Finalizare lucrare
                Action::make('complete')
                    ->label('Finalizat')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Intervention $record): bool => $record->status === 'in_progress')
                    ->action(fn (Intervention $record) => $record->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ])),

                // Acțiune: Generează factură
                Action::make('invoice')
                    ->label('Factură')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->visible(fn (Intervention $record): bool => $record->status === 'completed' && !$record->invoice && $record->final_cost)
                    ->action(function (Intervention $record) {
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                            $lastInvoice = Invoice::lockForUpdate()->orderByDesc('id')->first();
                            $nextNumber = $lastInvoice
                                ? 'FA-' . date('Y') . '-' . str_pad((int)substr($lastInvoice->number, -3) + 1, 3, '0', STR_PAD_LEFT)
                                : 'FA-' . date('Y') . '-001';

                            Invoice::create([
                                'intervention_id' => $record->id,
                                'number' => $nextNumber,
                                'total' => $record->final_cost,
                                'issued_at' => now(),
                            ]);
                        });
                    }),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Service users văd doar intervențiile lor, admin vede tot
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isService()) {
            $query->whereHas('service', fn (Builder $q) => $q->where('user_id', $user->id));
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterventions::route('/'),
            'create' => Pages\CreateIntervention::route('/create'),
            'edit' => Pages\EditIntervention::route('/{record}/edit'),
        ];
    }
}
