<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Level\StoreLevelRequest;
use App\Http\Requests\Level\UpdateLevelRequest;
use App\Http\Resources\LevelResource;
use App\Models\Level;
use App\Traits\LevelTrait;
use App\Traits\PaginationTrait;
use Illuminate\Support\Facades\Response;

class LevelController extends Controller
{
    use LevelTrait, PaginationTrait;

    /**
     * Display a listing of the levels.
     */
    public function index()
    {
        $levels = $this->listLevels();
        return Response::success('Levels retrieved successfully.',
            LevelResource::collection($levels)
        , 200, $this->getPagination($levels));
    }

    /**
     * Store a newly created level in storage.
     */
    public function store(StoreLevelRequest $request)
    {
        $level = $this->createLevel($request->validated());

        return Response::success('Level created successfully.', new LevelResource($level->load('prices')));
    }

    /**
     * Display the specified level.
     */
    public function show(Level $level)
    {
        return Response::success('Level retrieved successfully.', new LevelResource($level->load('prices.country')));
    }

    /**
     * Update the specified level in storage.
     */
    public function update(UpdateLevelRequest $request, Level $level)
    {
        $level = $this->updateLevel($level, $request->validated());
        return Response::success('Level updated successfully.', new LevelResource($level->load('prices')));
    }

    /**
     * Remove the specified level from storage.
     */
    public function destroy(Level $level)
    {
        $this->deleteLevel($level);
        return Response::success('Level deleted successfully.');
    }
}
