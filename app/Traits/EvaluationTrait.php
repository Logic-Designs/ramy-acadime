<?php

namespace App\Traits;

use App\Models\Question;

trait EvaluationTrait
{
    public function transformResponses(array $responses): array
    {
        return collect($responses)->map(function ($response) {
            $question = Question::find($response['question_id']);

            if (!$question) {
                throw new \Exception("Question with ID '{$response['question_id']}' not found.");
            }

            return [
                'question_text' => $question->text_ar??$question->text_en,
                'answer' => $response['answer'],
            ];
        })->toArray();
    }
}
