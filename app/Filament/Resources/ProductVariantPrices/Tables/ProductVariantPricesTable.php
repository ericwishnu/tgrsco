<?php

namespace App\Filament\Resources\ProductVariantPrices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductVariantPricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('variant.sku')->label('Variant SKU')->searchable(),
                TextColumn::make('currency.code')->label('Currency')->badge(),
                TextColumn::make('amount_minor')->numeric(),
                TextColumn::make('compare_at_minor')->numeric(),
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
