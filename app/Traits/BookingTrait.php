<?php

namespace App\Traits;

use App\Models\Booking;
use App\Models\BookingTime;
use App\Models\Level;
use Illuminate\Support\Facades\DB;

trait BookingTrait
{
    use PaginationTrait;

    public function listBookings($user_id = null)
    {
        $bookings = Booking::with(['user', 'level', 'location', 'times']);
        if($user_id){
            $bookings = $bookings->where('user_id', $user_id);
        }

        return $this->paginate($bookings);
    }

    public function createBooking(array $data)
    {
        DB::beginTransaction();

        try {
            // Create the booking
            $booking = Booking::create([
                'level_id' => $data['level_id'],
                'location_id' => $data['location_id'],
                'user_id' => $data['user_id'],
            ]);

            // Create associated booking times
            foreach ($data['times'] as $time) {
                BookingTime::create([
                    'booking_id' => $booking->id,
                    'session_time_id' => $time['session_time_id'],
                    'date' => $time['date'],
                ]);
            }

            DB::commit();

            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateBooking(Booking $booking, array $data)
    {
        $booking->update($data);
        return $booking;
    }

    public function deleteBooking(Booking $booking)
    {
        $booking->delete();
    }


    public function validateBookingConstraints(array $data)
    {
        $level = Level::with('sessionTimes')->findOrFail($data['level_id']);

        // 1. Check count of sessions in the level
        if ($level->sessionTimes->isEmpty()) {
            abort(422, 'The selected level has no sessions.');
        }

        // 2. Check that all dates are after today
        $today = now()->toDateString();
        $invalidSessions = $level->sessionTimes->filter(
            fn($session) => $session->day_of_week < $today
        );

        if ($invalidSessions->isNotEmpty()) {
            abort(422, 'Some sessions are scheduled for dates before today.');
        }

        // 3. Check if session times are already booked
        foreach ($level->sessionTimes as $session) {
            foreach ($session->bookingTimes as $bookingTime) {
                if (
                    $bookingTime->date === $data['date'] &&
                    $bookingTime->session_time_id === $session->id
                ) {
                    abort(422, "Session {$session->id} is already booked for {$data['date']}.");
                }
            }
        }
    }

    private function operatorKeys(){
        return [
            'id'=> '==',
            'user_id'=> '==',
            'level_id'=> '==',
        ];
    }

}
