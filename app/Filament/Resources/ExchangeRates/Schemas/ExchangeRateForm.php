<?php

namespace App\Filament\Resources\ExchangeRates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExchangeRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('base_currency_id')
                    ->relationship('baseCurrency', 'code')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('quote_currency_id')
                    ->relationship('quoteCurrency', 'code')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('rate')
                    ->required()
                    ->numeric(),
                TextInput::make('provider')
                    ->maxLength(255),
                DateTimePicker::make('fetched_at')
                    ->required(),
            ]);
    }
}
