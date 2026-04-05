<?php

namespace App\Filament\Resources\AttributeValues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class AttributeValuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute.name')->label('Attribute')->badge(),
                TextColumn::make('label')->searchable(),
                TextColumn::make('value')->searchable(),
                TextColumn::make('hex_code')->label('Hex'),
                TextColumn::make('sort_order')->numeric(),
                ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([
                //
            ])
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
