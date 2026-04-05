<?php

namespace App\Filament\Resources\ProductPrices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductPricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->searchable()
                    ->label('Product'),
                TextColumn::make('currency.code')
                    ->label('Currency')
                    ->badge(),
                TextColumn::make('amount_minor')
                    ->label('Amount')
                    ->numeric(),
                TextColumn::make('compare_at_minor')
                    ->label('Compare At')
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
