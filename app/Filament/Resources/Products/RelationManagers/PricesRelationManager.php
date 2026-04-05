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

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';

    protected static ?string $title = 'Product Prices';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ->defaultSort('currency_id')
            ->columns([
                TextColumn::make('currency.code')
                    ->label('Currency')
                    ->badge(),
                TextColumn::make('amount_minor')
                    ->label('Amount')
                    ->numeric(),
                TextColumn::make('compare_at_minor')
                    ->label('Compare At')
                    ->numeric(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->since(),
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
