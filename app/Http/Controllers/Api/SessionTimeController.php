<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SessionTime\StoreSessionTimeRequest;
use App\Http\Requests\SessionTime\UpdateSessionTimeRequest;
use App\Http\Resources\SessionTimeResource;
use App\Models\SessionTime;
use App\Traits\SessionTimeTrait;
use App\Traits\PaginationTrait;
use Illuminate\Support\Facades\Response;

class SessionTimeController extends Controller
{
    use SessionTimeTrait, PaginationTrait;

    /**
     * Display a listing of the session times.
     */
    public function index()
    {
        $sessionTimes = $this->listSessionTimes();

        $filters = request()->only(['location_id', 'day_of_week', 'start_time', 'end_time']);
        $search = request('search', '');

        $sessionTimes = $this->listSessionTimes($filters, $search);

        return Response::success('Session times retrieved successfully.',
             SessionTimeResource::collection($sessionTimes)
        , 200, $this->getPagination($sessionTimes));
    }

    /**
     * Store a newly created session time in storage.
     */
    public function store(StoreSessionTimeRequest $request)
    {
        $sessionTime = $this->createSessionTime($request->validated());
        return Response::success('Session time created successfully.', new SessionTimeResource($sessionTime));
    }

    /**
     * Display the specified session time.
     */
    public function show(SessionTime $sessionTime)
    {
        return Response::success('Session time retrieved successfully.', new SessionTimeResource($sessionTime));
    }

    /**
     * Update the specified session time in storage.
     */
    public function update(UpdateSessionTimeRequest $request, SessionTime $sessionTime)
    {
        $sessionTime = $this->updateSessionTime($sessionTime, $request->validated());
        return Response::success('Session time updated successfully.', new SessionTimeResource($sessionTime));
    }

    /**
     * Remove the specified session time from storage.
     */
    public function destroy(SessionTime $sessionTime)
    {
        $this->deleteSessionTime($sessionTime);
        return Response::success('Session time deleted successfully.');
    }
}
