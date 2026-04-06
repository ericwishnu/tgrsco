<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Seed all pairwise exchange rates derived from IDR base rates.
     *
     * Strategy:
     *  1. Collect every existing rate as a map  base_id -> quote_id -> rate.
     *  2. Use IDR as the pivot to compute any missing cross-rate:
     *       rate(A→B) = rate(A→IDR) * rate(IDR→B)
     *     where rate(A→IDR) = 1 / rate(IDR→A).
     *  3. Insert missing pairs with updateOrCreate so re-running is safe.
     */
    public function run(): void
    {
        $now       = Carbon::now();
        $provider  = 'seeder';
        $idr       = Currency::where('code', 'IDR')->firstOrFail();
        $currencies = Currency::all()->keyBy('id');

        // Build a rate lookup: [base_id][quote_id] => rate
        $rateMap = [];
        ExchangeRate::all()->each(function ($r) use (&$rateMap) {
            $rateMap[$r->base_currency_id][$r->quote_currency_id] = (float) $r->rate;
        });

        // For every ordered pair (base, quote) where base ≠ quote, ensure a rate exists.
        foreach ($currencies as $base) {
            foreach ($currencies as $quote) {
                if ($base->id === $quote->id) {
                    continue;
                }

                // Already exists — skip.
                if (isset($rateMap[$base->id][$quote->id])) {
                    continue;
                }

                $rate = $this->crossRate($base->id, $quote->id, $idr->id, $rateMap);

                if ($rate === null) {
                    $this->command->warn(
                        "Cannot compute {$base->code} → {$quote->code}: no IDR pivot rates available."
                    );
                    continue;
                }

                ExchangeRate::updateOrCreate(
                    [
                        'base_currency_id'  => $base->id,
                        'quote_currency_id' => $quote->id,
                    ],
                    [
                        'rate'       => $rate,
                        'provider'   => $provider,
                        'fetched_at' => $now,
                    ],
                );

                $rateMap[$base->id][$quote->id] = $rate;

                $this->command->info(
                    sprintf('Seeded %s → %s : %s', $base->code, $quote->code, $rate)
                );
            }
        }

        $this->command->info('Exchange rate seeding complete.');
    }

    /**
     * Derive rate(base → quote) via IDR pivot:
     *   rate(base → IDR)  = 1 / rate(IDR → base)   [or direct if stored]
     *   rate(IDR → quote) = stored directly
     *   result = rate(base → IDR) * rate(IDR → quote)
     */
    private function crossRate(int $baseId, int $quoteId, int $idrId, array $rateMap): ?float
    {
        // rate(base → IDR)
        if ($baseId === $idrId) {
            $baseToIdr = 1.0;
        } elseif (isset($rateMap[$baseId][$idrId])) {
            $baseToIdr = $rateMap[$baseId][$idrId];
        } elseif (isset($rateMap[$idrId][$baseId]) && $rateMap[$idrId][$baseId] > 0) {
            $baseToIdr = 1.0 / $rateMap[$idrId][$baseId];
        } else {
            return null;
        }

        // rate(IDR → quote)
        if ($quoteId === $idrId) {
            $idrToQuote = 1.0;
        } elseif (isset($rateMap[$idrId][$quoteId])) {
            $idrToQuote = $rateMap[$idrId][$quoteId];
        } elseif (isset($rateMap[$quoteId][$idrId]) && $rateMap[$quoteId][$idrId] > 0) {
            $idrToQuote = 1.0 / $rateMap[$quoteId][$idrId];
        } else {
            return null;
        }

        return $baseToIdr * $idrToQuote;
    }
}
