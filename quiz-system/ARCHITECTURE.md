# Architecture and Design Decisions

This document outlines the architectural decisions made for the Dynamic Quiz System and explains how it meets the requirement of being highly extensible.

## The Problem
A standard naive approach to building a quiz system is to hardcode `if/else` or `switch` statements inside controllers or models every time a quiz attempt is submitted:

```php
// Naive Approach (Avoided)
if ($question->type === 'binary') {
    // evaluate binary...
} elseif ($question->type === 'multiple_choice') {
    // evaluate multiple choice...
}
```
This violates the **Open/Closed Principle (OCP)**. If we want to add a new question type (e.g., "Matching" or "Drag and Drop"), we would have to modify the core evaluation loop, which can introduce bugs and makes the codebase rigid.

## The Solution: Strategy Pattern

To achieve true extensibility, we implemented the **Strategy Pattern** for question evaluation.

### Components:
1. **`QuestionEvaluator` Interface**: 
   Located at `app/Quiz/Evaluators/QuestionEvaluator.php`. It defines a single contract:
   `public function evaluate(Question $question, mixed $answerData): float;`

2. **Concrete Evaluators**:
   Each question type has its own isolated class that implements the interface. They contain the specific logic for determining how many marks to award.
   - `BinaryEvaluator`
   - `SingleChoiceEvaluator`
   - `MultipleChoiceEvaluator`
   - `NumberInputEvaluator`
   - `TextInputEvaluator`

3. **`QuestionTypeRegistry`**:
   A centralized registry (`app/Quiz/Evaluators/QuestionTypeRegistry.php`) maps string-based database types (e.g., `multiple_choice`) to their concrete class names. It dynamically resolves them via Laravel's service container.

### How it works during an attempt:
When a user submits a quiz, the `AttemptController` iterates through the answers. It dynamically fetches the right evaluator and scores the question:

```php
$evaluator = QuestionTypeRegistry::getEvaluator($question->type);
$marksAwarded = $evaluator->evaluate($question, $answerInput);
```

### Extensibility Guide: Adding a new type
If a developer needs to add a new question type (e.g., "Image Hotspot") in the future, they simply:
1. Create `ImageHotspotEvaluator implements QuestionEvaluator`.
2. Write the scoring logic in that isolated class.
3. Register it in `QuestionTypeRegistry::$types` (or dynamically via a service provider).
4. Update the frontend form to allow the creation of this type.

**Zero core logic needs to be modified.** The `AttemptController` and Database Schema remain completely untouched.

## Database Modeling

- **Quizzes**: High-level container.
- **Questions**: Linked to Quizzes. Contains media (`image_path`, `video_url`), `type`, and HTML-supported `content`.
- **Options**: Linked to Questions. Allows flexibility (some questions have 2 options, some have 5, some have none visible and only hold the correct value for Number/Text inputs). Supports `image_path` and `text_content`.
- **Attempts**: Stores aggregate `score` and `max_score`.
- **Answers**: Linked to Attempt and Question. The `answer_data` is a JSON column, which inherently supports both scalar values (string/number) and arrays (multiple choice answers), without requiring separate tables or serialization hacks.

## Frontend
- Blade is used as the templating engine.
- Vanilla JS handles dynamic form fields (e.g., pressing "Add Option" on the question creation screen injects DOM elements dynamically based on the current question type).
- Vanilla CSS is used with CSS variables to ensure a sleek, dynamic, glassmorphism design.
