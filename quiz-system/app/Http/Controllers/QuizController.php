<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = \App\Models\Quiz::latest()->get();
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz = \App\Models\Quiz::create($validated);

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Quiz created successfully.');
    }

    public function show(\App\Models\Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('quizzes.show', compact('quiz'));
    }
    public function edit(\App\Models\Quiz $quiz)
    {
        return view('quizzes.edit', compact('quiz'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz->update($validated);

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Quiz updated successfully.');
    }

    public function destroy(\App\Models\Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully.');
    }
}
