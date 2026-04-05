<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(255),
                TextInput::make('sku')
                    ->maxLength(255),
                FileUpload::make('featured_image_url')
                    ->label('Variant Image')
                    ->image()
                    ->disk('public')
                    ->directory('variants')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(4096)
                    ->imageEditor()
                    ->afterStateHydrated(function (FileUpload $component, ?string $state): void {
                        if (is_string($state) && str_starts_with($state, 'http')) {
                            $component->state(null);
                        }
                    })
                    ->dehydrateStateUsing(function (mixed $state, $record): mixed {
                        if (blank($state) && $record) {
                            return $record->getOriginal('featured_image_url')
                                ?? $record->featured_image_url;
                        }

                        return $state;
                    }),
                Select::make('attributeValues')
                    ->relationship('attributeValues', 'label')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('featured_image_url')
                    ->label('Image')
                    ->disk('public')
                    ->defaultImageUrl(asset('images/placeholders/product.svg'))
                    ->square(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sku')
                    ->searchable(),
                TextColumn::make('attributeValues_count')
                    ->counts('attributeValues')
                    ->label('Options'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
