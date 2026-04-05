<?php

namespace App\Filament\Resources\ProductVariantPrices\Pages;

use App\Filament\Resources\ProductVariantPrices\ProductVariantPriceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductVariantPrices extends ListRecords
{
    protected static string $resource = ProductVariantPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
