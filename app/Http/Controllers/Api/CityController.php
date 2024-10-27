<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\City\StoreCityRequest;
use App\Http\Requests\City\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Traits\CityTrait;
use App\Traits\PaginationTrait;
use Illuminate\Support\Facades\Response;

class CityController extends Controller
{
    use CityTrait, PaginationTrait;

    /**
     * Display a listing of the cities.
     */
    public function index()
    {
        $cities = $this->listCities();
        return Response::success('Cities retrieved successfully.', [
            'cities' => CityResource::collection($cities)
        ], 200, $this->getPagination($cities));
    }

    /**
     * Store a newly created city in storage.
     */
    public function store(StoreCityRequest $request)
    {
        $city = $this->createCity($request->validated());
        return Response::success('City created successfully.', new CityResource($city));
    }

    /**
     * Display the specified city.
     */
    public function show(City $city)
    {
        return Response::success('City retrieved successfully.', new CityResource($city));
    }

    /**
     * Update the specified city in storage.
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        $city = $this->updateCity($city, $request->validated());
        return Response::success('City updated successfully.', new CityResource($city));
    }

    /**
     * Remove the specified city from storage.
     */
    public function destroy(City $city)
    {
        $this->deleteCity($city);
        return Response::success('City deleted successfully.');
    }
}
