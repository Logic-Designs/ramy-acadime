<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Level;
use App\Models\SessionTime;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SessionTimeSeeder extends Seeder
{
    public function run(): void
    {
        $daysOfWeek = SessionTime::DAYS_OF_WEEK;

        $initialStartTime = Carbon::createFromTime(10, 0);
        $sessionDuration = 45;
        $breakDuration = 10; // Break duration between sessions in minutes

        $locations = Location::all();

        foreach ($locations as $location) {
            foreach ($daysOfWeek as $day) {
                $startTime = $initialStartTime->copy();

                while ($startTime->lt(Carbon::createFromTime(12, 0))) {
                    $endTime = $startTime->copy()->addMinutes($sessionDuration);

                    SessionTime::create([
                        'location_id' => $location->id,
                        'day_of_week' => $day,
                        'start_time' => $startTime->format('H:i:s'),
                        'end_time' => $endTime->format('H:i:s'),
                    ]);

                    $startTime = $endTime->copy()->addMinutes($breakDuration);
                }
            }
        }
    }
}
