<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Traits\LocationTrait;
use App\Traits\PaginationTrait;
use Illuminate\Support\Facades\Response;

class LocationController extends Controller
{
    use LocationTrait, PaginationTrait;

    /**
     * Display a listing of the locations.
     */
    public function index()
    {
        $locations = $this->listLocations();
        return Response::success('Locations retrieved successfully.', [
            LocationResource::collection($locations)
        ], 200, $this->getPagination($locations));
    }

    /**
     * Store a newly created location in storage.
     */
    public function store(StoreLocationRequest $request)
    {
        $location = $this->createLocation($request->validated());
        return Response::success('Location created successfully.', new LocationResource($location));
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        return Response::success('Location retrieved successfully.', new LocationResource($location));
    }

    /**
     * Update the specified location in storage.
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location = $this->updateLocation($location, $request->validated());
        return Response::success('Location updated successfully.', new LocationResource($location));
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location)
    {
        $this->deleteLocation($location);
        return Response::success('Location deleted successfully.');
    }
}
