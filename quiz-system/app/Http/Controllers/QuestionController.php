<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(\App\Models\Quiz $quiz)
    {
        return view('questions.create', compact('quiz'));
    }

    public function store(\Illuminate\Http\Request $request, \App\Models\Quiz $quiz)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:binary,single_choice,multiple_choice,number_input,text_input',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'marks' => 'required|numeric|min:0',
            'options' => 'nullable|array',
            'options.*.text_content' => 'nullable|string',
            'options.*.image' => 'nullable|image|max:2048',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('questions', 'public');
        }

        $question = $quiz->questions()->create([
            'type' => $validated['type'],
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
            'marks' => $validated['marks'],
            'order' => $quiz->questions()->count() + 1,
        ]);

        if (isset($validated['options']) && is_array($validated['options'])) {
            foreach ($validated['options'] as $index => $optionData) {
                $optImagePath = null;
                // Workaround for file arrays in validation
                if ($request->hasFile("options.{$index}.image")) {
                    $optImagePath = $request->file("options.{$index}.image")->store('options', 'public');
                }

                $question->options()->create([
                    'text_content' => $optionData['text_content'] ?? null,
                    'image_path' => $optImagePath,
                    'is_correct' => isset($optionData['is_correct']) && $optionData['is_correct'],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Question added successfully.');
    }
    public function edit(\App\Models\Question $question)
    {
        $quiz = $question->quiz;
        return view('questions.edit', compact('question', 'quiz'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Question $question)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:binary,single_choice,multiple_choice,number_input,text_input',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'marks' => 'required|numeric|min:0',
            'options' => 'nullable|array',
            'options.*.text_content' => 'nullable|string',
            'options.*.image' => 'nullable|image|max:2048',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        $imagePath = $question->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('questions', 'public');
        }

        $question->update([
            'type' => $validated['type'],
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
            'marks' => $validated['marks'],
        ]);

        // Handle Options: Delete and re-insert
        // First delete old option images if they exist
        foreach ($question->options as $option) {
            if ($option->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($option->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($option->image_path);
            }
        }
        $question->options()->delete();

        if (isset($validated['options']) && is_array($validated['options'])) {
            foreach ($validated['options'] as $index => $optionData) {
                $optImagePath = null;
                if ($request->hasFile("options.{$index}.image")) {
                    $optImagePath = $request->file("options.{$index}.image")->store('options', 'public');
                }

                $question->options()->create([
                    'text_content' => $optionData['text_content'] ?? null,
                    'image_path' => $optImagePath,
                    'is_correct' => isset($optionData['is_correct']) && $optionData['is_correct'],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('quizzes.show', $question->quiz_id)->with('success', 'Question updated successfully.');
    }

    public function destroy(\App\Models\Question $question)
    {
        $quizId = $question->quiz_id;
        
        // Delete images
        if ($question->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($question->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($question->image_path);
        }
        foreach ($question->options as $option) {
            if ($option->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($option->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($option->image_path);
            }
        }

        $question->delete();

        return redirect()->route('quizzes.show', $quizId)->with('success', 'Question deleted successfully.');
    }
}
