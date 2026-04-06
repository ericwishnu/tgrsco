<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Product;
use App\Models\ProductVariant;

class PricingService
{
    public function resolveDisplayPriceMinor(Product $product, Currency $currency): int
    {
        $variants = $product->relationLoaded('variants')
            ? $product->variants
            : $product->variants()->where('is_active', true)->with('prices')->get();

        $variantPrices = collect($variants)
            ->where('is_active', true)
            ->map(fn (ProductVariant $variant) => $this->resolveVariantPriceMinor($variant, $currency))
            ->filter(fn (int $amount) => $amount > 0);

        if ($variantPrices->isNotEmpty()) {
            return (int) $variantPrices->min();
        }

        return $this->resolvePriceMinor($product, $currency);
    }

    public function resolvePriceMinor(Product $product, Currency $currency): int
    {
        // 1. Direct price in the requested currency
        $directPrice = $product->prices->firstWhere('currency_id', $currency->id);

        if ($directPrice) {
            return (int) $directPrice->amount_minor;
        }

        // 2. Convert from any stored price that has an exchange rate to the target currency
        foreach ($product->prices as $storedPrice) {
            $rate = ExchangeRate::where('base_currency_id', $storedPrice->currency_id)
                ->where('quote_currency_id', $currency->id)
                ->latest('fetched_at')
                ->first();

            if (! $rate) {
                continue;
            }

            $baseCurrency = Currency::find($storedPrice->currency_id);

            if (! $baseCurrency) {
                continue;
            }

            return $this->convertMinorAmount(
                (int) $storedPrice->amount_minor,
                $baseCurrency,
                $currency,
                (float) $rate->rate,
            );
        }

        return 0;
    }

    public function formatMinor(int $amountMinor, Currency $currency): string
    {
        $decimals = max(0, (int) $currency->decimal_places);
        $amount = $amountMinor / (10 ** $decimals);

        return $currency->symbol . number_format($amount, $decimals);
    }

    public function resolveVariantPriceMinor(ProductVariant $variant, Currency $currency): int
    {
        // 1. Direct price in the requested currency
        $directPrice = $variant->prices->firstWhere('currency_id', $currency->id);

        if ($directPrice) {
            return (int) $directPrice->amount_minor;
        }

        // 2. Convert from any stored price that has an exchange rate to the target currency
        foreach ($variant->prices as $storedPrice) {
            $rate = ExchangeRate::where('base_currency_id', $storedPrice->currency_id)
                ->where('quote_currency_id', $currency->id)
                ->latest('fetched_at')
                ->first();

            if (! $rate) {
                continue;
            }

            $baseCurrency = Currency::find($storedPrice->currency_id);

            if (! $baseCurrency) {
                continue;
            }

            return $this->convertMinorAmount(
                (int) $storedPrice->amount_minor,
                $baseCurrency,
                $currency,
                (float) $rate->rate,
            );
        }

        return 0;
    }

    private function convertMinorAmount(
        int $amountMinor,
        Currency $baseCurrency,
        Currency $targetCurrency,
        float $rate,
    ): int {
        $baseDecimals = max(0, (int) $baseCurrency->decimal_places);
        $targetDecimals = max(0, (int) $targetCurrency->decimal_places);

        $baseAmount = $amountMinor / (10 ** $baseDecimals);
        $convertedAmount = $baseAmount * $rate;

        return (int) round($convertedAmount * (10 ** $targetDecimals));
    }
}
