<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductVariantForm
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
                TextInput::make('name')
                    ->maxLength(255)
                    ->placeholder('e.g. Black / M'),
                TextInput::make('sku')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                FileUpload::make('featured_image_url')
                    ->label('Variant Image')
                    ->image()
                    ->disk('public')
                    ->directory('variants')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(4096)
                    ->imageEditor(),
                Select::make('attributeValues')
                    ->relationship('attributeValues', 'label')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
