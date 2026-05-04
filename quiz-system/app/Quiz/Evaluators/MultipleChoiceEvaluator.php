<?php

namespace App\Quiz\Evaluators;

use App\Models\Question;

class MultipleChoiceEvaluator implements QuestionEvaluator
{
    public function evaluate(Question $question, mixed $answerData): float
    {
        // $answerData is expected to be an array of selected Option IDs (strings from POST)
        $selectedOptionIds = is_array($answerData)
            ? array_map('intval', $answerData)
            : [];

        $correctOptionIds = $question->options()
            ->where('is_correct', true)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        if (empty($correctOptionIds)) {
            return 0;
        }

        sort($selectedOptionIds);
        sort($correctOptionIds);

        // Exact match: all correct options selected, no wrong ones
        if ($selectedOptionIds === $correctOptionIds) {
            return $question->marks;
        }

        return 0;
    }
}
