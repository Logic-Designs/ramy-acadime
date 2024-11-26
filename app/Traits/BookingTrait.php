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
        DB::beginTransaction();

        try {
            if (isset($data['level_id'])) {
                $booking->update(['level_id' => $data['level_id']]);
            }
            if (isset($data['location_id'])) {
                $booking->update(['location_id' => $data['location_id']]);
            }

            if (isset($data['times'])) {
                foreach ($data['times'] as $time) {
                    if (isset($time['booking_time_id'])) {
                        $existingBookingTime = BookingTime::find($time['booking_time_id']);
                        if ($existingBookingTime) {
                            $existingBookingTime->update([
                                'date' => $time['date'],
                                'session_time_id' => $time['session_time_id'],
                            ]);
                        }
                    } else {
                        BookingTime::create([
                            'booking_id' => $booking->id,
                            'session_time_id' => $time['session_time_id'],
                            'date' => $time['date'],
                        ]);
                    }
                }
            }

            DB::commit();

            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteBooking(Booking $booking)
    {
        $booking->delete();
    }

}
