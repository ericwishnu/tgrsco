<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'IDR', 'symbol' => 'Rp', 'decimal_places' => 0, 'is_default' => true,  'is_active' => true],
            ['code' => 'GBP', 'symbol' => '£', 'decimal_places' => 2, 'is_default' => false, 'is_active' => true],
            ['code' => 'USD', 'symbol' => '$', 'decimal_places' => 2, 'is_default' => false, 'is_active' => true],
            ['code' => 'EUR', 'symbol' => '€', 'decimal_places' => 2, 'is_default' => false, 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency,
            );
        }
    }
}
