<?php

namespace App\Quiz\Evaluators;

use App\Models\Question;

class SingleChoiceEvaluator implements QuestionEvaluator
{
    public function evaluate(Question $question, mixed $answerData): float
    {
        // $answerData is expected to be the selected Option ID
        $selectedOptionId = (int) $answerData;

        $option = $question->options()->find($selectedOptionId);

        if ($option && $option->is_correct) {
            return $question->marks;
        }

        return 0;
    }
}
