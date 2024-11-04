<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition()
    {
        // Examples of some common currency codes
        $currencyCodes = ['USD', 'SAR', 'EUR', 'GBP', 'AED'];
        $currencyCode = $this->faker->randomElement($currencyCodes);

        return [
            'name_en' => $this->faker->unique()->country,
            'name_ar' => $this->faker->unique()->word,
            'code' => $this->faker->unique()->countryCode,
            'currency_code_en' => $currencyCode,
            'currency_code_ar' => $this->getArabicCurrency($currencyCode),
        ];
    }

    private function getArabicCurrency($currencyCode)
    {
        $arabicCurrencies = [
            'USD' => 'دولار أمريكي',
            'SAR' => 'ريال سعودي',
            'EUR' => 'يورو',
            'GBP' => 'جنيه إسترليني',
            'AED' => 'درهم إماراتي',
        ];

        return $arabicCurrencies[$currencyCode] ?? 'عملة';
    }
}
