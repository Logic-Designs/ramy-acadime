<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailableTimes\AvailableTimesRequest;
use App\Http\Resources\LevelResource;
use App\Http\Resources\UserResource;
use App\Models\Level;
use App\Models\User;
use App\Services\AvailableTimesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AvailableTimesController extends Controller
{
    protected $availableTimesService;

    public function __construct(AvailableTimesService $availableTimesService)
    {
        $this->availableTimesService = $availableTimesService;
    }

    public function getAvailableTimes(Level $level, AvailableTimesRequest $request)
    {
        $cityId = Auth::user()->profile->city_id;

        if ($request->user_id) {
            $user = User::find($request->user_id);
            $cityId = $user->profile->city_id;
        }

        $result = $this->availableTimesService->getAvailableTimes($cityId);

        return Response::success('Available times retrieved successfully.', [
            'level' => new LevelResource($level),
            'locations' => $result,
        ]);
    }

}
