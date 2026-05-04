<?php

namespace App\Quiz\Evaluators;

use App\Models\Question;

interface QuestionEvaluator
{
    /**
     * Evaluate the user's answer against the question and return the marks awarded.
     *
     * @param Question $question
     * @param mixed $answerData
     * @return float
     */
    public function evaluate(Question $question, mixed $answerData): float;
}
