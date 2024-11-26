<?php

namespace App\Traits;

use App\Models\Booking;
use App\Models\BookingTime;
use App\Models\Level;
use App\Models\LevelSession;
use App\Notifications\BookingStatusUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

trait BookingTrait
{
    use PaginationTrait;

    public function listBookings($user_id = null)
    {
        $bookings = Booking::with(['user', 'level', 'location', 'times.levelSession']);
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

            $levelSessions = LevelSession::where('level_id', $data['level_id'])
                ->orderBy('id')
                ->pluck('id')
                ->toArray();

            $sortedTimes = collect($data['times'])->sortBy('date');

            foreach ($sortedTimes as $index => $time) {
                BookingTime::create([
                    'booking_id' => $booking->id,
                    'session_time_id' => $time['session_time_id'],
                    'level_session_id' => $levelSessions[$index] ?? null,
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
            $statusChanged = isset($data['status']) && $data['status'] !== $booking->status;
            $paymentStatusChanged = isset($data['payment_status']) && $data['payment_status'] !== $booking->payment_status;

            $booking->update($data);

            if ($statusChanged || $paymentStatusChanged) {
                Notification::send($booking->user, new BookingStatusUpdated($booking));
            }

            DB::commit();

            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processBookingTimeUpdate(BookingTime $bookingTime, array $data)
    {
        DB::beginTransaction();

        try {
            $bookingTime->update($data);

            DB::commit();

            return $bookingTime->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateBookinTime(BookingTime $bookingTime, array $data)
    {
        DB::beginTransaction();

        try {

            $bookingTime->update($data);

            DB::commit();

            return $bookingTime->booking;
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
