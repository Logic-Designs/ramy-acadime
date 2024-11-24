<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\AddCoachToLocation;
use App\Http\Requests\Location\AddManagerToLocation;
use App\Http\Requests\Location\RemoveCoachFromLocation;
use App\Http\Requests\Location\RemoveManagerFromLocation;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Http\Resources\UserResource;
use App\Models\Location;
use App\Models\User;
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
        return Response::success('Locations retrieved successfully.',
            LocationResource::collection($locations)
        , 200, $this->getPagination($locations));
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


    public function assignManagers(AddManagerToLocation $request, Location $location)
    {
        $user = User::findOrFail($request->user_id);

        if (!$user->hasRole('site manager')) {
            return Response::error('Not allowed', ['error' => 'The user does not have the required site manager role.'], 422);
        }

        $location->siteManagers()->syncWithoutDetaching([$request->user_id]);

        return Response::success('Manager assigned successfully.', new LocationResource($location->load('siteManagers')));
    }


    public function getSiteManagers(Location $location)
    {
        $siteManagers = $location->siteManagers;

        return Response::success('Site managers retrieved successfully.', UserResource::collection($siteManagers));
    }

    public function removeManager(RemoveManagerFromLocation $request, Location $location)
    {
        $location->siteManagers()->detach($request->user_id);

        return Response::success('Manager removed successfully.', new LocationResource($location->load('siteManagers')));
    }


    public function assignCoachs(AddCoachToLocation $request, Location $location)
    {
        $user = User::findOrFail($request->user_id);

        if (!$user->hasRole('coach')) {
            return Response::error('Not allowed', ['error' => 'The user does not have the required coach role.'], 422);
        }

        $location->coaches()->syncWithoutDetaching([$request->user_id]);

        return Response::success('Coach assigned successfully.', new LocationResource($location->load('coaches')));
    }


    public function getCoachs(Location $location)
    {
        $coaches = $location->coaches;

        return Response::success('Coachs retrieved successfully.', UserResource::collection($coaches));
    }

    public function removeCoach(RemoveCoachFromLocation $request, Location $location)
    {
        $location->coaches()->detach($request->user_id);

        return Response::success('Coach removed successfully.', new LocationResource($location->load('coaches')));
    }


}
