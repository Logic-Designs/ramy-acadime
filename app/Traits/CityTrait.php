<?php

namespace App\Traits;

use App\Models\City;

trait CityTrait
{
    /**
     * List all cities with pagination.
     */
    public function listCities()
    {
        return City::paginate(10); // Customize the pagination as needed
    }

    /**
     * Create a new city with the provided data.
     */
    public function createCity(array $data)
    {
        return City::create($data);
    }

    /**
     * Update the specified city with new data.
     */
    public function updateCity(City $city, array $data)
    {
        $city->update($data);
        return $city;
    }

    /**
     * Delete the specified city.
     */
    public function deleteCity(City $city)
    {
        $city->delete();
    }
}
