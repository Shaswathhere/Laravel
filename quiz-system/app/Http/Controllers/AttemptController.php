<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function index(\App\Models\Quiz $quiz)
    {
        $attempts    = $quiz->attempts()->latest()->get();
        $maxPossible = $quiz->questions()->sum('marks');
        return view('attempts.index', compact('quiz', 'attempts', 'maxPossible'));
    }

    public function create(\App\Models\Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('attempts.create', compact('quiz'));
    }

    public function store(\Illuminate\Http\Request $request, \App\Models\Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'nullable|array',
        ]);

        $attempt = $quiz->attempts()->create([
            'completed_at' => now(),
            'max_score' => $quiz->questions()->sum('marks'),
            'score' => 0, // Will calculate below
        ]);

        $totalScore = 0;
        $answersData = $validated['answers'] ?? [];

        foreach ($quiz->questions as $question) {
            $answerInput = $answersData[$question->id] ?? null;

            try {
                $evaluator = \App\Quiz\Evaluators\QuestionTypeRegistry::getEvaluator($question->type);
                $marksAwarded = $evaluator->evaluate($question, $answerInput);
            } catch (\Exception $e) {
                // Fallback if evaluator fails or type is unknown
                $marksAwarded = 0;
            }

            $attempt->answers()->create([
                'question_id' => $question->id,
                'answer_data' => $answerInput,
                'is_correct' => $marksAwarded > 0,
                'marks_awarded' => $marksAwarded,
            ]);

            $totalScore += $marksAwarded;
        }

        $attempt->update(['score' => $totalScore]);

        return redirect()->route('attempts.show', $attempt)->with('success', 'Quiz submitted successfully.');
    }

    public function show(\App\Models\Attempt $attempt)
    {
        $attempt->load('quiz.questions.options', 'answers');
        return view('attempts.show', compact('attempt'));
    }
}
