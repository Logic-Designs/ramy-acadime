<?php

namespace App\Traits;

use App\Models\Location;

trait LocationTrait
{
    public function listLocations()
    {
        return Location::paginate(10); // Customize the pagination as needed
    }

    public function createLocation(array $data)
    {
        return Location::create($data);
    }

    public function updateLocation(Location $location, array $data)
    {
        $location->update($data);
        return $location;
    }

    public function deleteLocation(Location $location)
    {
        $location->delete();
    }
}
