<?php

namespace App\Filament\Resources\ProductVariantPrices;

use App\Filament\Resources\ProductVariantPrices\Pages\CreateProductVariantPrice;
use App\Filament\Resources\ProductVariantPrices\Pages\EditProductVariantPrice;
use App\Filament\Resources\ProductVariantPrices\Pages\ListProductVariantPrices;
use App\Filament\Resources\ProductVariantPrices\Schemas\ProductVariantPriceForm;
use App\Filament\Resources\ProductVariantPrices\Tables\ProductVariantPricesTable;
use App\Models\ProductVariantPrice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductVariantPriceResource extends Resource
{
    protected static ?string $model = ProductVariantPrice::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Pricing';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductVariantPriceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductVariantPricesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductVariantPrices::route('/'),
            'create' => CreateProductVariantPrice::route('/create'),
            'edit' => EditProductVariantPrice::route('/{record}/edit'),
        ];
    }
}
