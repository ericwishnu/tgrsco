<?php

namespace App\Filament\Resources\ProductVariantPrices\Pages;

use App\Filament\Resources\ProductVariantPrices\ProductVariantPriceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductVariantPrice extends EditRecord
{
    protected static string $resource = ProductVariantPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
