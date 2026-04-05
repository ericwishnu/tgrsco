<?php

namespace App\Filament\Resources\ProductVariantPrices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductVariantPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_variant_id')
                    ->relationship('variant', 'sku')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('currency_id')
                    ->relationship('currency', 'code')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('amount_minor')
                    ->numeric()
                    ->required()
                    ->label('Amount (minor units)'),
                TextInput::make('compare_at_minor')
                    ->numeric()
                    ->label('Compare At (minor units)'),
            ]);
    }
}
