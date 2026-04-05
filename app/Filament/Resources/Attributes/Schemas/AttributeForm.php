<?php

namespace App\Filament\Resources\Attributes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->required()
                    ->options([
                        'text' => 'Text',
                        'color' => 'Color',
                    ])
                    ->default('text'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
