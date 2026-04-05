<?php

namespace App\Filament\Resources\AttributeValues\Schemas;

use App\Models\Attribute;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeValueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('attribute_id')
                    ->relationship('attribute', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live(),
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('value')
                    ->required()
                    ->maxLength(255),
                ColorPicker::make('hex_code')
                    ->label('Hex Color')
                    ->placeholder('#111111')
                    ->visible(fn ($get): bool => Attribute::query()
                        ->whereKey((int) ($get('attribute_id') ?? 0))
                        ->value('type') === 'color'),
                TextInput::make('hex_code')
                    ->label('Hex Value')
                    ->maxLength(16)
                    ->placeholder('#111111')
                    ->visible(fn ($get): bool => Attribute::query()
                        ->whereKey((int) ($get('attribute_id') ?? 0))
                        ->value('type') !== 'color'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
