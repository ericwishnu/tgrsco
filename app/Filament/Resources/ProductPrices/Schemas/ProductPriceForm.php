<?php

namespace App\Filament\Resources\ProductPrices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('currency_id')
                    ->relationship('currency', 'code')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('amount_minor')
                    ->required()
                    ->numeric()
                    ->label('Amount (minor units)'),
                TextInput::make('compare_at_minor')
                    ->numeric()
                    ->label('Compare At (minor units)'),
            ]);
    }
}
