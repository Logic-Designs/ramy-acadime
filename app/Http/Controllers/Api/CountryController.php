<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Country\StoreCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Traits\PaginationTrait;
use App\Traits\CountryTrait;
use Illuminate\Support\Facades\Response;

class CountryController extends Controller
{
    use CountryTrait, PaginationTrait;

    /**
     * Display a listing of the countries.
     */
    public function index()
    {
        $countries = $this->listCountries();
        return Response::success('Countries retrieved successfully.',
            CountryResource::collection($countries)
        , 200, $this->getPagination($countries));
    }

    /**
     * Store a newly created country in storage.
     */
    public function store(StoreCountryRequest $request)
    {
        $country = $this->createCountry($request->validated());
        return Response::success('Country created successfully.', new CountryResource($country));
    }

    /**
     * Display the specified country.
     */
    public function show(Country $country)
    {
        return Response::success('Country retrieved successfully.', new CountryResource($country->load('locations')));
    }

    /**
     * Update the specified country in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country = $this->updateCountry($country, $request->validated());
        return Response::success('Country updated successfully.', new CountryResource($country));
    }

    /**
     * Remove the specified country from storage.
     */
    public function destroy(Country $country)
    {
        $this->deleteCountry($country);
        return Response::success('Country deleted successfully.');
    }
}
