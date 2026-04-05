<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->helperText('Leave empty to auto-generate from product name.')
                    ->unique(ignoreRecord: true),
                TextInput::make('sku')
                    ->maxLength(255)
                    ->helperText('Leave empty to auto-generate.')
                    ->unique(ignoreRecord: true),

                // Shows the current image regardless of whether it is a local path or external URL.
                Placeholder::make('current_image_preview')
                    ->label('Current Featured Image')
                    ->content(function ($record): HtmlString|string {
                        $url = $record?->featured_image_url;
                        if (blank($url)) {
                            return 'No image set.';
                        }
                        $src = str_starts_with($url, 'http')
                            ? $url
                            : Storage::disk('public')->url($url);

                        return new HtmlString(
                            '<img src="' . e($src) . '" alt="Current image" '
                            . 'style="max-height:200px;max-width:320px;width:auto;height:auto;'
                            . 'border-radius:0.5rem;object-fit:contain;'
                            . 'box-shadow:0 1px 6px rgba(0,0,0,.2);">'
                        );
                    })
                    ->visible(fn ($record): bool => filled($record?->featured_image_url))
                    ->columnSpanFull(),

                FileUpload::make('featured_image_url')
                    ->label('Upload New Image')
                    ->helperText('Upload to replace the current image. JPEG, PNG or WEBP, max 4 MB.')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(4096)
                    ->imageEditor()
                    // External URLs cannot be loaded as disk files – clear them so the
                    // component stays empty rather than throwing a missing-file error.
                    ->afterStateHydrated(function (FileUpload $component, ?string $state): void {
                        if (is_string($state) && str_starts_with($state, 'http')) {
                            $component->state(null);
                        }
                    })
                    // When nothing new is uploaded, keep whatever was already stored.
                    ->dehydrateStateUsing(function (mixed $state, $record): mixed {
                        if (blank($state) && $record) {
                            return $record->getOriginal('featured_image_url')
                                ?? $record->featured_image_url;
                        }

                        return $state;
                    }),
                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),
                Textarea::make('details')
                    ->rows(5)
                    ->columnSpanFull(),
                Textarea::make('shipping_information')
                    ->rows(5)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
