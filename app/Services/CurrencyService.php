<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyService
{
    public const SESSION_KEY = 'currency_code';

    public function getCurrentCurrency(Request $request): Currency
    {
        $requestedCode = strtoupper((string) $request->query('currency', ''));

        if ($requestedCode !== '' && Currency::active()->where('code', $requestedCode)->exists()) {
            $request->session()->put(self::SESSION_KEY, $requestedCode);
        }

        $sessionCode = strtoupper((string) $request->session()->get(self::SESSION_KEY, ''));

        if ($sessionCode !== '') {
            $currency = Currency::active()->where('code', $sessionCode)->first();
            if ($currency) {
                return $currency;
            }
        }

        return Currency::active()->where('is_default', true)->first()
            ?? Currency::active()->first()
            ?? Currency::firstOrFail();
    }

    public function setCurrency(Request $request, string $code): void
    {
        $code = strtoupper($code);

        if (Currency::active()->where('code', $code)->exists()) {
            $request->session()->put(self::SESSION_KEY, $code);
        }
    }
}
