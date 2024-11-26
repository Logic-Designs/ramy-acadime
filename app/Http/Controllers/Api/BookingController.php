<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Level;
use App\Models\SessionTime;
use App\Traits\BookingTrait;
use App\Traits\PaginationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    use BookingTrait, PaginationTrait;

    /**
     * Display a listing of the bookings.
     */
    public function index()
    {
        $bookings = $this->listBookings();
        return Response::success(
            'Bookings retrieved successfully.',
            BookingResource::collection($bookings),
            200,
            $this->getPagination($bookings)
        );
    }

    public function myBookings()
    {
        $bookings = $this->listBookings(Auth::id());
        return Response::success(
            'Bookings retrieved successfully.',
            BookingResource::collection($bookings),
            200,
            $this->getPagination($bookings)
        );
    }

    public function userBookings($userId)
    {
        $bookings = $this->listBookings($userId);

        return Response::success(
            'User bookings retrieved successfully.',
            BookingResource::collection($bookings),
            200,
            $this->getPagination($bookings)
        );
    }


    /**
     * Store a newly created booking in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            // Use the trait method to create a booking
            $booking = $this->createBooking($request->validated() + ['user_id' => Auth::id()]);

            return Response::success(
                'Booking created successfully.',
                new BookingResource($booking->load('times'))
            );
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), 422);
        }
    }

    public function show(Booking $booking)
    {

        return Response::success(
            'Booking retrieved successfully.',
            new BookingResource($booking->load('times.levelSession', 'level'))
        );
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking = $this->updateBooking($booking, $request->validated());

        try {
            $booking = $this->updateBooking($booking, $request->validated());

            return Response::success(
                'Booking updated successfully.',
                new BookingResource($booking->load('times'))
            );
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), 422);
        }
    }

    public function destroy(Booking $booking)
    {
        $this->deleteBooking($booking);
        return Response::success('Booking deleted successfully.');
    }


}
