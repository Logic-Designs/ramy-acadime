<?php

namespace App\Services;

use App\Helpers\LocaleHelper;
use App\Models\Location;
use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Support\Carbon;

class AvailableTimesService
{
    /**
     * Get available times for all locations in a city.
     *
     * @param int $cityId
     * @return array
     */
    public function getAvailableTimes(int $cityId): array
    {
        $locations = $this->getLocationsInCity($cityId);
        $startDate = now()->addDay();
        $endDate = $startDate->copy()->addDays(28); // Next 4 weeks

        return $locations->map(fn($location) => $this->getLocationData($location, $startDate, $endDate))->all();
    }

    /**
     * Fetch all locations in the city.
     *
     * @param int $cityId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getLocationsInCity(int $cityId)
    {
        return Location::with(['sessionTimes', 'sessionTimes.bookingTimes'])
            ->where('city_id', $cityId)
            ->get();
    }

    /**
     * Prepare location data with available dates and times.
     *
     * @param Location $location
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function getLocationData($location, $startDate, $endDate): array
    {
        $dates = $this->getAvailableDates($location, $startDate, $endDate);

        return [
            'location_id' => $location->id,
            'location_name' => LocaleHelper::getLocalizedField($location, 'name'),
            'available_dates' => $this->formatDates($dates),
        ];
    }

    /**
     * Get available dates and times for a location.
     *
     * @param Location $location
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    // protected function getAvailableDates($location, $startDate, $endDate): array
    // {
    //     $dates = [];

    //     foreach ($location->sessionTimes as $session) {
    //         $sessionStart = $startDate->copy();

    //         while ($sessionStart->lte($endDate)) {
    //             $currentDate = $sessionStart->toDateString();

    //             if ($sessionStart->format('l') !== $session->day_of_week) {
    //                 $sessionStart->addDay();
    //                 continue;
    //             }

    //             if (!$session->bookingTimes->contains('date', $currentDate)) {
    //                 $dates[$currentDate][] = [
    //                     'session_time_id' => $session->id,
    //                     'start_time' => $session->start_time,
    //                     'end_time' => $session->end_time,
    //                 ];
    //             }

    //             $sessionStart->addDay();
    //         }
    //     }

    //     ksort($dates); // Ensure dates are sorted
    //     return $dates;
    // }

    protected function getAvailableDates($location, $startDate, $endDate): array
    {
        $dates = [];

        // Initialize all dates within the range
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $formattedDate = $currentDate->toDateString();
            $dates[$formattedDate] = [
                'day_of_week' => $currentDate->format('l'), // Add the day of the week
                'times' => [] // Initialize the times array for each date
            ];
            $currentDate->addDay();
        }

        // Process each session
        foreach ($location->sessionTimes as $session) {
            $sessionStart = $startDate->copy();

            while ($sessionStart->lte($endDate)) {
                $currentDate = $sessionStart->toDateString();
                $dayOfWeek = $sessionStart->format('l'); // Get the day of the week

                if ($dayOfWeek !== $session->day_of_week) {
                    $sessionStart->addDay();
                    continue;
                }

                if (!$session->bookingTimes->contains('date', $currentDate)) {
                    $dates[$currentDate]['times'][] = [
                        'session_time_id' => $session->id,
                        'start_time' => $session->start_time,
                        'end_time' => $session->end_time,
                    ];
                }

                $sessionStart->addDay();
            }
        }

        ksort($dates); // Ensure dates are sorted
        return $dates;
    }



    /**
     * Format dates into a structured array.
     *
     * @param array $dates
     * @return array
     */
    protected function formatDates(array $dates): array
    {
        return array_map(
            fn($date, $data) => [
                'date' => $date,
                'day_of_week' => $data['day_of_week'],  // Add day of the week
                'times' => $data['times'], // Times for each date
            ],
            array_keys($dates),
            $dates
        );
    }



    public function isTimeAvailable(string $date, int $userId, int $sessionTimeId): bool
    {
        $user = User::findOrFail($userId);

        $cityId = $user->profile->city_id;

        $session = SessionTime::findOrFail($sessionTimeId);

        $location = $session->location;
        if ($location->city_id != $cityId) {
            return false;
        }

        $sessionDay = $session->day_of_week;
        $givenDate = Carbon::parse($date);


        $givenDayOfWeek = $givenDate->format('l');

        if ($sessionDay !== $givenDayOfWeek) {
            return false;
        }

        return !$session->bookingTimes->contains(function ($bookingTime) use ($date) {
            return $bookingTime->date == $date;
        });
    }


}
