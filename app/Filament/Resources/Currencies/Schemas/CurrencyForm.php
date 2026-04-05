<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(3)
                    ->minLength(3)
                    ->unique(ignoreRecord: true),
                TextInput::make('symbol')
                    ->required()
                    ->maxLength(8),
                TextInput::make('decimal_places')
                    ->required()
                    ->numeric()
                    ->default(2),
                Toggle::make('is_default')
                    ->label('Default Currency'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
