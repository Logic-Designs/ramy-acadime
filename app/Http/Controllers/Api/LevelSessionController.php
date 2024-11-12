<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LevelSession\StoreLevelSessionRequest;
use App\Http\Requests\LevelSession\UpdateLevelSessionRequest;
use App\Http\Resources\LevelSessionResource;
use App\Models\LevelSession; // Import the LevelSession model
use App\Traits\LevelSessionTrait;
use App\Traits\PaginationTrait; // Import pagination trait
use Illuminate\Support\Facades\Response;

class LevelSessionController extends Controller
{
    use LevelSessionTrait, PaginationTrait;

    /**
     * Display a listing of the level sessions.
     */
    public function index()
    {
        $sessions = $this->listLevelSessions(); // Use the method from the SessionTrait
        return Response::success('Level sessions retrieved successfully.', [
            LevelSessionResource::collection($sessions)
        ], 200, $this->getPagination($sessions));
    }

    /**
     * Store a newly created level session in storage.
     */
    public function store(StoreLevelSessionRequest $request)
    {
        $session = $this->createLevelSession($request->validated());
        return Response::success('Level session created successfully.', new LevelSessionResource($session));
    }

    /**
     * Display the specified level session.
     */
    public function show(LevelSession $levelSession)
    {

        return Response::success('Level session retrieved successfully.', new LevelSessionResource($levelSession));
    }

    /**
     * Update the specified level session in storage.
     */
    public function update(UpdateLevelSessionRequest $request, LevelSession $levelSession)
    {
        $session = $this->updateLevelSession($levelSession, $request->validated());
        return Response::success('Level session updated successfully.', new LevelSessionResource($session));
    }

    /**
     * Remove the specified level session from storage.
     */
    public function destroy(LevelSession $levelSession)
    {
        $this->deleteLevelSession($levelSession);
        return Response::success('Level session deleted successfully.');
    }
}
