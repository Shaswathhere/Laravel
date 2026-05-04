# Dynamic Quiz System

A flexible and extensible Dynamic Quiz System built with Laravel, supporting multiple question types, dynamic evaluation logic, and media uploads.

## Requirements

- PHP >= 8.2
- Composer
- SQLite (default) or MySQL

## Features

- Create quizzes with titles and descriptions.
- Add questions of various types:
  - Binary (True/False or Yes/No)
  - Single Choice
  - Multiple Choice
  - Number Input
  - Text Input
- Rich Text / HTML support for question content.
- Upload images or link videos to questions and options.
- Dynamic Javascript interface for adding variable amount of options depending on question type.
- Automatic and accurate evaluation logic for different question types.
- Beautiful Glassmorphism UI built with Vanilla CSS.

## Setup Instructions

1. **Clone or Extract the Project**
   Ensure you are in the `quiz-system` directory.

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   The `.env` file should already be configured to use SQLite. If it's not present:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Migration**
   The database is configured to use SQLite by default (`database/database.sqlite`).
   Run the migrations to build the schema:
   ```bash
   php artisan migrate
   ```

5. **Storage Link**
   To make uploaded media accessible to the public, create the symbolic link:
   ```bash
   php artisan storage:link
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` in your browser.
