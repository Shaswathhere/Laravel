@extends('layouts.app')

@section('content')

    {{-- ── Header ── --}}
    <div class="page-header">
        <div class="page-header-title">
            <h2>{{ $quiz->title }}</h2>
            <p>Answer every question then submit your attempt.</p>
        </div>
        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-default">← Back</a>
    </div>

    <form action="{{ route('attempts.store', $quiz) }}" method="POST" id="attemptForm">
        @csrf

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
                            <span class="badge badge-muted">{{ $question->marks }} pt{{ $question->marks != 1 ? 's' : '' }}</span>
                        </div>

                        {{-- ── Media ── --}}
                        @if($question->image_path)
                            <div class="media-block">
                                <img src="{{ Storage::url($question->image_path) }}" alt="Question image">
                            </div>
                        @endif
                        @if($question->video_url)
                            <div class="media-block">
                                <a href="{{ $question->video_url }}" target="_blank" rel="noopener">🎬 View attached video</a>
                            </div>
                        @endif

                        {{-- ── Answer Input ── --}}
                        <div style="margin-top: 1.25rem;">

                            @if($question->type === 'binary' || $question->type === 'single_choice')
                                <div class="answer-options-list">
                                    @foreach($question->options as $option)
                                        <label class="answer-option">
                                            <input type="radio"
                                                   name="answers[{{ $question->id }}]"
                                                   value="{{ $option->id }}" required>
                                            @if($option->image_path)
                                                <img src="{{ Storage::url($option->image_path) }}"
                                                     style="max-height: 44px; border-radius: 4px;">
                                            @endif
                                            @if($option->text_content)
                                                <span>{{ $option->text_content }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->type === 'multiple_choice')
                                <div class="answer-options-list">
                                    @foreach($question->options as $option)
                                        <label class="answer-option">
                                            <input type="checkbox"
                                                   name="answers[{{ $question->id }}][]"
                                                   value="{{ $option->id }}">
                                            @if($option->image_path)
                                                <img src="{{ Storage::url($option->image_path) }}"
                                                     style="max-height: 44px; border-radius: 4px;">
                                            @endif
                                            @if($option->text_content)
                                                <span>{{ $option->text_content }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->type === 'number_input')
                                <input type="number"
                                       name="answers[{{ $question->id }}]"
                                       class="form-control"
                                       style="max-width: 300px;"
                                       step="any" required
                                       placeholder="Enter your number…">

                            @elseif($question->type === 'text_input')
                                <input type="text"
                                       name="answers[{{ $question->id }}]"
                                       class="form-control"
                                       required
                                       placeholder="Type your answer here…">
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Submit Bar ── --}}
        <div style="position: sticky; bottom: 1.5rem; display: flex; justify-content: flex-end; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary btn-lg"
                    style="box-shadow: 0 8px 24px rgba(220,38,38,0.4); padding: 0.9rem 2.5rem;">
                Submit Quiz →
            </button>
        </div>

    </form>

@endsection
