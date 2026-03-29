<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Facturi';
    protected static ?string $modelLabel = 'Factură';
    protected static ?string $pluralModelLabel = 'Facturi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('intervention_id')
                    ->label('Intervenție')
                    ->relationship('intervention', 'description')
                    ->searchable()
                    ->required(),
                TextInput::make('number')
                    ->label('Număr factură')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                TextInput::make('total')
                    ->label('Total (RON)')
                    ->numeric()
                    ->required()
                    ->prefix('RON'),
                DatePicker::make('issued_at')
                    ->label('Data emitere')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Nr. Factură')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('intervention.car.plate')
                    ->label('Mașină'),
                Tables\Columns\TextColumn::make('intervention.service.name')
                    ->label('Service'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('RON')
                    ->sortable(),
                Tables\Columns\TextColumn::make('issued_at')
                    ->label('Data emitere')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('issued_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Descarcă PDF factură
                Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function (Invoice $record) {
                        $record->load(['intervention.car', 'intervention.service']);

                        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $record]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "factura-{$record->number}.pdf"
                        );
                    }),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isService()) {
            $query->whereHas('intervention.service', fn (Builder $q) => $q->where('user_id', $user->id));
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
