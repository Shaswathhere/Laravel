<?php

namespace App\Quiz\Evaluators;

use App\Models\Question;

class NumberInputEvaluator implements QuestionEvaluator
{
    public function evaluate(Question $question, mixed $answerData): float
    {
        // $answerData is expected to be a number (string or int/float)
        $userNumber = (float) $answerData;

        // The correct answer is stored in the options table
        $correctOption = $question->options()->where('is_correct', true)->first();

        if (!$correctOption) {
            return 0;
        }

        $correctNumber = (float) $correctOption->text_content;

        // We can add a tolerance level here if needed, but for now exact match
        if ($userNumber === $correctNumber) {
            return $question->marks;
        }

        return 0;
    }
}
