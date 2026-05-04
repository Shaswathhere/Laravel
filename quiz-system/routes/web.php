<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AttemptController;

Route::get('/', function () {
    return redirect()->route('quizzes.index');
});

Route::resource('quizzes', QuizController::class);

Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

Route::get('quizzes/{quiz}/attempts', [AttemptController::class, 'index'])->name('attempts.index');
Route::get('quizzes/{quiz}/attempt', [AttemptController::class, 'create'])->name('attempts.create');
Route::post('quizzes/{quiz}/attempt', [AttemptController::class, 'store'])->name('attempts.store');
Route::get('attempts/{attempt}', [AttemptController::class, 'show'])->name('attempts.show');
