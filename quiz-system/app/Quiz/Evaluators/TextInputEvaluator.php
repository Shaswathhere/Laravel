<?php

namespace App\Quiz\Evaluators;

use App\Models\Question;

class TextInputEvaluator implements QuestionEvaluator
{
    public function evaluate(Question $question, mixed $answerData): float
    {
        // $answerData is expected to be a string
        $userText = trim(strtolower((string) $answerData));

        // The correct answer is stored in the options table
        $correctOptions = $question->options()->where('is_correct', true)->get();

        if ($correctOptions->isEmpty()) {
            return 0;
        }

        // Check if the user text matches any of the correct options (case-insensitive)
        foreach ($correctOptions as $option) {
            $correctText = trim(strtolower((string) $option->text_content));
            if ($userText === $correctText) {
                return $question->marks;
            }
        }

        return 0;
    }
}
