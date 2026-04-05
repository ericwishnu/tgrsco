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
        $directPrice = $product->prices->firstWhere('currency_id', $currency->id);

        if ($directPrice) {
            return (int) $directPrice->amount_minor;
        }

        $defaultCurrency = Currency::where('is_default', true)->first();

        if (! $defaultCurrency) {
            return 0;
        }

        $basePrice = $product->prices->firstWhere('currency_id', $defaultCurrency->id);

        if (! $basePrice) {
            return 0;
        }

        if ($defaultCurrency->id === $currency->id) {
            return (int) $basePrice->amount_minor;
        }

        $rate = ExchangeRate::where('base_currency_id', $defaultCurrency->id)
            ->where('quote_currency_id', $currency->id)
            ->latest('fetched_at')
            ->first();

        if (! $rate) {
            return 0;
        }

        return $this->convertMinorAmount(
            (int) $basePrice->amount_minor,
            $defaultCurrency,
            $currency,
            (float) $rate->rate,
        );
    }

    public function formatMinor(int $amountMinor, Currency $currency): string
    {
        $decimals = max(0, (int) $currency->decimal_places);
        $amount = $amountMinor / (10 ** $decimals);

        return $currency->symbol . number_format($amount, $decimals);
    }

    public function resolveVariantPriceMinor(ProductVariant $variant, Currency $currency): int
    {
        $directPrice = $variant->prices->firstWhere('currency_id', $currency->id);

        if ($directPrice) {
            return (int) $directPrice->amount_minor;
        }

        $defaultCurrency = Currency::where('is_default', true)->first();

        if (! $defaultCurrency) {
            return 0;
        }

        $basePrice = $variant->prices->firstWhere('currency_id', $defaultCurrency->id);

        if (! $basePrice) {
            return 0;
        }

        if ($defaultCurrency->id === $currency->id) {
            return (int) $basePrice->amount_minor;
        }

        $rate = ExchangeRate::where('base_currency_id', $defaultCurrency->id)
            ->where('quote_currency_id', $currency->id)
            ->latest('fetched_at')
            ->first();

        if (! $rate) {
            return 0;
        }

        return $this->convertMinorAmount(
            (int) $basePrice->amount_minor,
            $defaultCurrency,
            $currency,
            (float) $rate->rate,
        );
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
