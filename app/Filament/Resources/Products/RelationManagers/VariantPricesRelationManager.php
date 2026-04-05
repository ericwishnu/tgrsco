<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'variantPrices';

    protected static ?string $title = 'Variant Prices';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_variant_id')
                    ->label('Variant')
                    ->options(function (): array {
                        return $this->getOwnerRecord()
                            ->variants()
                            ->orderBy('sku')
                            ->get()
                            ->mapWithKeys(fn ($variant) => [
                                $variant->id => ($variant->sku ?: 'N/A') . ' — ' . ($variant->name ?: 'Variant'),
                            ])
                            ->toArray();
                    })
                    ->searchable()
                    ->required(),
                Select::make('currency_id')
                    ->relationship('currency', 'code')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('amount_minor')
                    ->required()
                    ->numeric()
                    ->label('Amount (minor units)'),
                TextInput::make('compare_at_minor')
                    ->numeric()
                    ->label('Compare At (minor units)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('product_variant_id')
            ->columns([
                TextColumn::make('variant.sku')
                    ->label('Variant SKU')
                    ->searchable(),
                TextColumn::make('variant.name')
                    ->label('Variant')
                    ->searchable(),
                TextColumn::make('currency.code')
                    ->label('Currency')
                    ->badge(),
                TextColumn::make('amount_minor')
                    ->label('Amount')
                    ->numeric(),
                TextColumn::make('compare_at_minor')
                    ->label('Compare At')
                    ->numeric(),
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
