<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;

class SampleQuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::create([
            'title'       => 'General Knowledge Quiz',
            'description' => 'A sample quiz covering all question types — Binary, Single Choice, Multiple Choice, Number Input, and Text Input.',
        ]);

        // ── Q1: Binary ────────────────────────────────────────────────
        $q1 = $quiz->questions()->create([
            'type'    => 'binary',
            'content' => 'Is the Earth the third planet from the Sun?',
            'marks'   => 1,
            'order'   => 1,
        ]);
        $q1->options()->createMany([
            ['text_content' => 'True',  'is_correct' => true,  'order' => 1],
            ['text_content' => 'False', 'is_correct' => false, 'order' => 2],
        ]);

        // ── Q2: Binary ────────────────────────────────────────────────
        $q2 = $quiz->questions()->create([
            'type'    => 'binary',
            'content' => 'Is Python a compiled language?',
            'marks'   => 1,
            'order'   => 2,
        ]);
        $q2->options()->createMany([
            ['text_content' => 'Yes', 'is_correct' => false, 'order' => 1],
            ['text_content' => 'No',  'is_correct' => true,  'order' => 2],
        ]);

        // ── Q3: Single Choice ─────────────────────────────────────────
        $q3 = $quiz->questions()->create([
            'type'    => 'single_choice',
            'content' => 'What is the capital of India?',
            'marks'   => 1,
            'order'   => 3,
        ]);
        $q3->options()->createMany([
            ['text_content' => 'Mumbai',    'is_correct' => false, 'order' => 1],
            ['text_content' => 'New Delhi', 'is_correct' => true,  'order' => 2],
            ['text_content' => 'Kolkata',   'is_correct' => false, 'order' => 3],
            ['text_content' => 'Chennai',   'is_correct' => false, 'order' => 4],
        ]);

        // ── Q4: Single Choice ─────────────────────────────────────────
        $q4 = $quiz->questions()->create([
            'type'    => 'single_choice',
            'content' => 'Which planet is known as the Red Planet?',
            'marks'   => 1,
            'order'   => 4,
        ]);
        $q4->options()->createMany([
            ['text_content' => 'Venus',   'is_correct' => false, 'order' => 1],
            ['text_content' => 'Jupiter', 'is_correct' => false, 'order' => 2],
            ['text_content' => 'Mars',    'is_correct' => true,  'order' => 3],
            ['text_content' => 'Saturn',  'is_correct' => false, 'order' => 4],
        ]);

        // ── Q5: Multiple Choice ───────────────────────────────────────
        $q5 = $quiz->questions()->create([
            'type'    => 'multiple_choice',
            'content' => 'Which of the following are programming languages?',
            'marks'   => 2,
            'order'   => 5,
        ]);
        $q5->options()->createMany([
            ['text_content' => 'Python',     'is_correct' => true,  'order' => 1],
            ['text_content' => 'HTML',       'is_correct' => false, 'order' => 2],
            ['text_content' => 'JavaScript', 'is_correct' => true,  'order' => 3],
            ['text_content' => 'CSS',        'is_correct' => false, 'order' => 4],
            ['text_content' => 'PHP',        'is_correct' => true,  'order' => 5],
        ]);

        // ── Q6: Multiple Choice ───────────────────────────────────────
        $q6 = $quiz->questions()->create([
            'type'    => 'multiple_choice',
            'content' => 'Which of the following are DBMS software?',
            'marks'   => 2,
            'order'   => 6,
        ]);
        $q6->options()->createMany([
            ['text_content' => 'MySQL',      'is_correct' => true,  'order' => 1],
            ['text_content' => 'Laravel',    'is_correct' => false, 'order' => 2],
            ['text_content' => 'SQLite',     'is_correct' => true,  'order' => 3],
            ['text_content' => 'PostgreSQL', 'is_correct' => true,  'order' => 4],
            ['text_content' => 'React',      'is_correct' => false, 'order' => 5],
        ]);

        // ── Q7: Number Input ──────────────────────────────────────────
        $q7 = $quiz->questions()->create([
            'type'    => 'number_input',
            'content' => 'How many days are there in a leap year?',
            'marks'   => 1,
            'order'   => 7,
        ]);
        $q7->options()->create([
            'text_content' => '366',
            'is_correct'   => true,
            'order'        => 1,
        ]);

        // ── Q8: Number Input ──────────────────────────────────────────
        $q8 = $quiz->questions()->create([
            'type'    => 'number_input',
            'content' => 'What is 15 × 8?',
            'marks'   => 1,
            'order'   => 8,
        ]);
        $q8->options()->create([
            'text_content' => '120',
            'is_correct'   => true,
            'order'        => 1,
        ]);

        // ── Q9: Text Input ────────────────────────────────────────────
        $q9 = $quiz->questions()->create([
            'type'    => 'text_input',
            'content' => 'What is the full form of CPU?',
            'marks'   => 1,
            'order'   => 9,
        ]);
        $q9->options()->createMany([
            ['text_content' => 'Central Processing Unit', 'is_correct' => true, 'order' => 1],
            ['text_content' => 'central processing unit', 'is_correct' => true, 'order' => 2],
        ]);

        // ── Q10: Text Input ───────────────────────────────────────────
        $q10 = $quiz->questions()->create([
            'type'    => 'text_input',
            'content' => 'Which PHP framework is used in this project?',
            'marks'   => 1,
            'order'   => 10,
        ]);
        $q10->options()->createMany([
            ['text_content' => 'Laravel', 'is_correct' => true, 'order' => 1],
            ['text_content' => 'laravel', 'is_correct' => true, 'order' => 2],
        ]);

        $this->command->info('✅ Sample quiz created: "General Knowledge Quiz" with 10 questions (all types covered).');
    }
}
