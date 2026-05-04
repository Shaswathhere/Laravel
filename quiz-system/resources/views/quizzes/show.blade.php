@extends('layouts.app')

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-title">
            <h2>{{ $quiz->title }}</h2>
            @if($quiz->description)
                <p>{{ $quiz->description }}</p>
            @endif
        </div>
        <div class="page-header-actions">
            <a href="{{ route('attempts.create', $quiz) }}" class="btn btn-success">▶ Take Quiz</a>
            <a href="{{ route('questions.create', $quiz) }}" class="btn btn-primary">+ Add Question</a>
            <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-default">Edit</a>
            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST"
                  onsubmit="return confirm('Delete this quiz and all its questions and attempts? This cannot be undone.')"
                  style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    {{-- ── Questions List ── --}}
    @if($quiz->questions->isEmpty())
        <div class="card">
            <div class="card-body empty-state">
                <h3>No questions yet</h3>
                <p>Add your first question to this quiz.</p>
                <a href="{{ route('questions.create', $quiz) }}" class="btn btn-primary">+ Add Question</a>
            </div>
        </div>
    @else
        <p class="section-title" style="margin-top:0;">{{ $quiz->questions->count() }} Question(s)</p>
        <div class="question-list">
            @foreach($quiz->questions as $index => $question)
                <div class="card question-card">
                    <div class="card-body">

                        {{-- ── Question Header ── --}}
                        <div class="question-header">
                            <div class="question-title">
                                <span class="badge badge-primary">Q{{ $index + 1 }}</span>
                                <h4>{!! $question->content !!}</h4>
                            </div>
                            <div class="question-actions">
                                <span class="badge badge-muted">{{ str_replace('_', ' ', Str::title($question->type)) }}</span>
                                <span class="badge badge-muted">{{ $question->marks }} pt{{ $question->marks != 1 ? 's' : '' }}</span>
                                <a href="{{ route('questions.edit', $question) }}" class="btn btn-default btn-sm">Edit</a>
                                <form action="{{ route('questions.destroy', $question) }}" method="POST"
                                      onsubmit="return confirm('Delete this question?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>

                        {{-- ── Media ── --}}
                        @if($question->image_path)
                            <div class="media-block">
                                <img src="{{ Storage::url($question->image_path) }}" alt="Question image">
                            </div>
                        @endif
                        @if($question->video_url)
                            <div class="media-block">
                                <a href="{{ $question->video_url }}" target="_blank" rel="noopener">
                                    🎬 View attached video
                                </a>
                            </div>
                        @endif

                        {{-- ── Options ── --}}
                        @if($question->options->isNotEmpty())
                            <p class="options-label">Options / Answers</p>
                            <div class="option-list">
                                @foreach($question->options as $option)
                                    <div class="option-row {{ $option->is_correct ? 'is-correct' : '' }}">
                                        <span class="check">{{ $option->is_correct ? '✓' : '○' }}</span>
                                        @if($option->text_content)
                                            <span>{{ $option->text_content }}</span>
                                        @endif
                                        @if($option->image_path)
                                            <img src="{{ Storage::url($option->image_path) }}"
                                                 alt="Option image"
                                                 style="max-height: 48px; border-radius: 4px; margin-left: auto;">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Quick link to Attempts History ── --}}
    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
        <a href="{{ route('attempts.index', $quiz) }}" class="btn btn-default">
            View Attempt History →
        </a>
    </div>

@endsection
