<?php

namespace App\Quiz\Evaluators;

use InvalidArgumentException;

class QuestionTypeRegistry
{
    /**
     * Map of question types to their evaluator classes.
     *
     * @var array<string, string>
     */
    protected static array $types = [
        'binary' => BinaryEvaluator::class,
        'single_choice' => SingleChoiceEvaluator::class,
        'multiple_choice' => MultipleChoiceEvaluator::class,
        'number_input' => NumberInputEvaluator::class,
        'text_input' => TextInputEvaluator::class,
    ];

    /**
     * Register a new question type or override an existing one.
     *
     * @param string $type
     * @param string $evaluatorClass
     * @return void
     */
    public static function register(string $type, string $evaluatorClass): void
    {
        self::$types[$type] = $evaluatorClass;
    }

    /**
     * Get the evaluator instance for a specific question type.
     *
     * @param string $type
     * @return QuestionEvaluator
     * @throws InvalidArgumentException
     */
    public static function getEvaluator(string $type): QuestionEvaluator
    {
        if (!isset(self::$types[$type])) {
            throw new InvalidArgumentException("Unknown question type: {$type}");
        }

        $class = self::$types[$type];

        return app($class);
    }
}
