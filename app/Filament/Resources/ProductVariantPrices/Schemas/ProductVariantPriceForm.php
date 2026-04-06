<?php

namespace App\Filament\Resources\ProductVariantPrices\Schemas;

use App\Models\Currency;
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
}
