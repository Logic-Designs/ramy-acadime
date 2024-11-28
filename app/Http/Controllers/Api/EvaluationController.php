<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluation\StoreEvaluationRequest;
use App\Http\Resources\EvaluationResource;
use App\Models\BookingTime;
use App\Models\Evaluation;
use App\Models\Question;
use App\Traits\EvaluationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class EvaluationController extends Controller
{
    use EvaluationTrait;

    public function storeUserEvaluation(StoreEvaluationRequest $request)
    {
        $validated = $request->validated();

        $responses = $this->transformResponses($validated['responses']);

        $evaluation = Evaluation::create([
            'booking_time_id' => $validated['booking_time_id'],
            'evaluator_id' => Auth::id(),
            'evaluator_type' => 'user',
            'responses' => $responses,
        ]);

        return Response::success(
            'Evaluation saved successfully',
            new EvaluationResource($evaluation),
            201
        );
    }

    public function storeCoachEvaluation(StoreEvaluationRequest $request)
    {
        $validated = $request->validated();

        $evaluation = Evaluation::create([
            'booking_time_id' => $validated['booking_time_id'],
            'evaluator_id' => Auth::id(),
            'evaluator_type' => 'coach',
            'responses' => $validated['responses'],
        ]);

        return Response::success(
            'Evaluation saved successfully',
            new EvaluationResource($evaluation),
            201
        );

        return response()->json(['message' => 'Evaluation saved successfully', 'evaluation' => $evaluation], 201);
    }
}
