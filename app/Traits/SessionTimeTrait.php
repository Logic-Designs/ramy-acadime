<?php

namespace App\Traits;

use App\Models\SessionTime;
use Illuminate\Database\Eloquent\Builder;

trait SessionTimeTrait
{
    use PaginationTrait;


    public function listSessionTimes(array $filters = [], string $search = '')
    {
        $query = SessionTime::query();

        foreach ($filters as $key => $value) {
            if (isset($value)) {
                $query = $this->applyFilter($query, $key, $value);
            }
        }

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('start_time', 'like', "%{$search}%")
                      ->orWhere('end_time', 'like', "%{$search}%")
                      ->orWhere('day_of_week', 'like', "%{$search}%");
            });
        }


        return $this->paginate($query);
    }


    private function applyFilter(Builder $query, string $key, $value)
    {
        switch ($key) {
            case 'location_id':
                return $query->where('location_id', $value);
            case 'day_of_week':
                return $query->where('day_of_week', $value);
            case 'start_time':
                return $query->where('start_time', '>=', $value);
            case 'end_time':
                return $query->where('end_time', '<=', $value);
            default:
                return $query;
        }
    }

    /**
     * Create a new session time.
     */
    public function createSessionTime(array $data)
    {
        return SessionTime::create($data);
    }

    /**
     * Update an existing session time.
     */
    public function updateSessionTime(SessionTime $sessionTime, array $data)
    {
        $sessionTime->update($data);
        return $sessionTime;
    }

    /**
     * Delete a session time.
     */
    public function deleteSessionTime(SessionTime $sessionTime)
    {
        $sessionTime->delete();
    }
}
