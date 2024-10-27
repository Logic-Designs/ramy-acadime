<?php

namespace App\Traits;

use App\Models\Country;

    trait CountryTrait
    {
        use PaginationTrait;

        public function listCountries()
        {
            return $this->paginate(Country::with('cities'));
        }

    public function createCountry(array $data)
    {
        return Country::create($data);
    }

    public function updateCountry(Country $country, array $data)
    {
        $country->update($data);
        return $country;
    }

    public function deleteCountry(Country $country)
    {
        $country->delete();
    }
}
