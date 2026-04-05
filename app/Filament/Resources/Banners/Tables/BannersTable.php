<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->width(120)
                    ->height(45)
                    ->defaultImageUrl(asset('images/placeholders/product.svg')),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->default('—'),

                TextColumn::make('subtitle')
                    ->limit(40)
                    ->default('—'),

                TextColumn::make('link_url')
                    ->label('Link')
                    ->limit(35)
                    ->default('—'),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
