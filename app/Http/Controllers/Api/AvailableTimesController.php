<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Services\AvailableTimesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AvailableTimesController extends Controller
{
    protected $availableTimesService;

    public function __construct(AvailableTimesService $availableTimesService)
    {
        $this->availableTimesService = $availableTimesService;
    }

    public function getAvailableTimes(Level $level)
    {
        $cityId = Auth::user()->profile->city_id;

        $result = $this->availableTimesService->getAvailableTimes($cityId);

        return Response::success('Available times retrieved successfully.', $result);
    }
}
