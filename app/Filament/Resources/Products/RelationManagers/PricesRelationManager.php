<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\Currency;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    protected static ?string $title = 'Product Prices';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('currency_id')
                    ->relationship('currency', 'code')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('amount_minor')
                    ->required()
                    ->numeric()
                    ->label('Amount')
                    ->afterStateHydrated(function (TextInput $component, $state, $record): void {
                        if ($state !== null && $record?->currency) {
                            $decimals = max(0, (int) $record->currency->decimal_places);
                            $component->state($state / (10 ** $decimals));
                        }
                    })
                    ->dehydrateStateUsing(function ($state, $get): int {
                        $currency = Currency::find($get('currency_id'));
                        $decimals = $currency ? max(0, (int) $currency->decimal_places) : 0;
                        return (int) round((float) $state * (10 ** $decimals));
                    }),
                TextInput::make('compare_at_minor')
                    ->numeric()
                    ->label('Compare At')
                    ->afterStateHydrated(function (TextInput $component, $state, $record): void {
                        if ($state !== null && $record?->currency) {
                            $decimals = max(0, (int) $record->currency->decimal_places);
                            $component->state($state / (10 ** $decimals));
                        }
                    })
                    ->dehydrateStateUsing(function ($state, $get): ?int {
                        if ($state === null || $state === '') {
                            return null;
                        }
                        $currency = Currency::find($get('currency_id'));
                        $decimals = $currency ? max(0, (int) $currency->decimal_places) : 0;
                        return (int) round((float) $state * (10 ** $decimals));
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('currency_id')
            ->columns([
                TextColumn::make('currency.code')
                    ->label('Currency')
                    ->badge(),
                TextColumn::make('amount_minor')
                    ->label('Amount')
                    ->numeric(),
                TextColumn::make('compare_at_minor')
                    ->label('Compare At')
                    ->numeric(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
