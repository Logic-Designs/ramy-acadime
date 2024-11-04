<?php

namespace App\Traits;

use App\Models\Location;

trait LocationTrait
{
    use PaginationTrait;


    public function listLocations()
    {
        return $this->paginate(Location::query());
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
