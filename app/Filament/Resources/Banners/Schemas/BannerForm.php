<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Banner Image')
                    ->image()
                    ->directory('banners')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(8192)
                    ->helperText('Recommended size: 1600×600 px. Max 8 MB. Tip: you can also paste an external URL (https://…) directly into this field if needed.')
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->maxLength(255)
                    ->placeholder('e.g. New Collection'),

                TextInput::make('subtitle')
                    ->maxLength(255)
                    ->placeholder('e.g. Discover our latest arrivals'),

                TextInput::make('link_url')
                    ->label('Button Link')
                    ->url()
                    ->placeholder('https://… or /categories/home-living'),

                TextInput::make('link_text')
                    ->label('Button Text')
                    ->default('View Product')
                    ->maxLength(100),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->integer()
                    ->default(0)
                    ->helperText('Lower numbers appear first.'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
