# Dynamic Quiz System

A flexible and extensible **Dynamic Quiz System** built with **Laravel 12** and **SQLite**.  
Supports multiple question types, dynamic JS-driven forms, automatic evaluation using the Strategy Pattern, and a fully responsive dark-themed UI.

🔗 **Live Demo**: [https://quiz-rzmf.onrender.com](https://quiz-rzmf.onrender.com)

---

## Features

- ✅ Create, edit, and delete **Quizzes**
- ✅ Add, edit, and delete **Questions** of 5 different types:
  - **Binary** — True/False or Yes/No
  - **Single Choice** — One correct answer (radio)
  - **Multiple Choice** — Multiple correct answers (checkboxes)
  - **Number Input** — Exact numeric answer
  - **Text Input** — Written/keyword answer
- ✅ HTML-supported rich question content
- ✅ Attach images or video URLs to questions and options
- ✅ Automatic, type-specific evaluation via the **Strategy Pattern**
- ✅ Full **Attempt History** with stats (total attempts, average score, highest score, pass rate)
- ✅ Per-attempt **Answer Breakdown** with correct answer reveal
- ✅ **Extensible architecture** — add new question types in 3 steps with zero changes to existing logic
- ✅ Glassmorphism dark UI built with **Vanilla CSS** (no Tailwind, no Bootstrap)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 / PHP 8.2+ |
| Database | SQLite |
| Frontend | Blade Templates + Vanilla JS + Vanilla CSS |
| Evaluation | Strategy Pattern (`QuestionTypeRegistry`) |
| Deployment | Docker on Render |

---

## Local Setup Instructions

### Prerequisites
- PHP >= 8.2
- Composer
- SQLite (built into PHP, no install needed)

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/Shaswathhere/Laravel.git
cd Laravel/quiz-system
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Set up the environment**
```bash
cp .env.example .env
php artisan key:generate
```

The default `.env` is pre-configured for SQLite — no database server needed.

**4. Create the SQLite database file**
```bash
mkdir -p database
touch database/database.sqlite
```

**5. Run database migrations**
```bash
php artisan migrate
```

**6. Create the storage symlink** *(for uploaded images)*
```bash
php artisan storage:link
```

**7. (Optional) Seed sample quiz data**
```bash
php artisan db:seed --class=SampleQuizSeeder
```

**8. Start the development server**
```bash
php artisan serve
```

Visit **http://localhost:8000** in your browser.

---

## Deployment (Docker / Render)

This project includes a `Dockerfile` and `docker-entrypoint.sh` for production deployment.

### Environment Variables (set on your host/platform)

| Variable | Value |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | *(generate with `php artisan key:generate`)* |
| `APP_URL` | `https://your-deployed-domain.com` |
| `APP_TIMEZONE` | `Asia/Kolkata` |
| `ASSET_URL` | `https://your-deployed-domain.com` |
| `DB_CONNECTION` | `sqlite` |
| `LOG_LEVEL` | `error` |

The `docker-entrypoint.sh` automatically runs migrations, creates the storage link, and caches config/routes/views on every deploy.

---

## Project Structure

```
quiz-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── QuizController.php       # CRUD for quizzes
│   │   ├── QuestionController.php   # CRUD for questions
│   │   └── AttemptController.php    # Quiz attempts & results
│   ├── Models/
│   │   ├── Quiz.php
│   │   ├── Question.php
│   │   ├── Option.php
│   │   ├── Attempt.php
│   │   └── Answer.php
│   └── Quiz/Evaluators/
│       ├── QuestionEvaluator.php        # Interface
│       ├── QuestionTypeRegistry.php     # Strategy registry
│       ├── BinaryEvaluator.php
│       ├── SingleChoiceEvaluator.php
│       ├── MultipleChoiceEvaluator.php
│       ├── NumberInputEvaluator.php
│       └── TextInputEvaluator.php
├── database/
│   ├── migrations/                  # All 5 table migrations
│   └── seeders/SampleQuizSeeder.php
├── public/style.css                 # All UI styles (Vanilla CSS)
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── quizzes/                     # index, create, edit, show
│   ├── questions/                   # create, edit, _options_script
│   └── attempts/                    # index, create, show
├── routes/web.php
├── Dockerfile
└── docker-entrypoint.sh
```

---

## Adding a New Question Type

Only 3 steps needed — no changes to existing controllers or evaluators:

1. **Create an Evaluator** in `app/Quiz/Evaluators/`:
```php
class ImageMatchEvaluator implements QuestionEvaluator {
    public function evaluate(Question $question, mixed $answerData): float {
        // your logic
    }
}
```

2. **Register it** in `QuestionTypeRegistry.php`:
```php
'image_match' => ImageMatchEvaluator::class,
```

3. **Add it to the dropdown** in `questions/create.blade.php` and `edit.blade.php`.

---

## Database Schema

```
quizzes        → id, title, description, timestamps
questions      → id, quiz_id (FK), type, content, marks, order, image_path, video_url
options        → id, question_id (FK), text_content, is_correct, image_path, order
attempts       → id, quiz_id (FK), score, max_score, completed_at, timestamps
answers        → id, attempt_id (FK), question_id (FK), answer_data (JSON), is_correct, marks_awarded
```

All foreign keys use `onDelete('cascade')` for automatic cleanup.

---

## License

This project was built as an academic assignment. All rights reserved.
