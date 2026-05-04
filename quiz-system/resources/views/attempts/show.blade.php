@extends('layouts.app')

@section('content')

    @php
        $passed = $attempt->score >= ($attempt->max_score * 0.5);
    @endphp

    {{-- ── Score Card ── --}}
    <div class="card" style="max-width: 480px; margin: 0 auto 2.5rem; text-align: center;">
        <div class="card-body-lg">

            <div class="result-score-ring {{ $passed ? 'is-pass' : '' }}">
                <span class="score-num">{{ $attempt->score }}</span>
            </div>

            <h2 style="margin-bottom: 0.25rem;">
                {{ $passed ? '🎉 Great Job!' : '📚 Keep Practising!' }}
            </h2>
            <p>
                You scored <strong>{{ $attempt->score }}</strong> out of
                <strong>{{ $attempt->max_score }}</strong> mark{{ $attempt->max_score != 1 ? 's' : '' }}.
            </p>

            <div style="display: flex; justify-content: center; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('quizzes.index') }}" class="btn btn-default">← Dashboard</a>
                <a href="{{ route('attempts.create', $attempt->quiz_id) }}" class="btn btn-primary">Retry Quiz</a>
            </div>

        </div>
    </div>

    {{-- ── Breakdown ── --}}
    <p class="section-title">Answer Breakdown</p>

    <div class="result-breakdown">
        @foreach($attempt->answers as $index => $answer)
            @php $question = $answer->question; @endphp
            <div class="card result-q {{ $answer->is_correct ? 'correct' : 'wrong' }}">
                <div class="card-body">

                    {{-- Header --}}
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; gap: 0.6rem; align-items: flex-start; flex: 1;">
                            <span class="badge badge-primary">Q{{ $index + 1 }}</span>
                            <h4 style="margin: 0;">{!! $question->content !!}</h4>
                        </div>
                        <span class="badge {{ $answer->is_correct ? 'badge-success' : 'badge-danger' }}" style="flex-shrink:0;">
                            {{ $answer->marks_awarded }} / {{ $question->marks }}
                        </span>
                    </div>

                    {{-- Your Answer --}}
                    <div style="background-color: var(--surface-raised); border-radius: var(--radius); padding: 0.75rem 1rem;">
                        <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; color: var(--text-muted);">Your Answer</p>

                        @if($question->type === 'binary' || $question->type === 'single_choice')
                            @php $selected = $question->options->firstWhere('id', $answer->answer_data); @endphp
                            <p style="margin: 0; font-weight: 600; color: var(--text);">
                                {{ $selected ? $selected->text_content : '— no answer —' }}
                            </p>

                        @elseif($question->type === 'multiple_choice')
                            @php
                                $ids  = is_array($answer->answer_data) ? $answer->answer_data : [];
                                $opts = $question->options->whereIn('id', $ids);
                            @endphp
                            <p style="margin: 0; font-weight: 600; color: var(--text);">
                                {{ $opts->isEmpty() ? '— no answer —' : $opts->pluck('text_content')->join(', ') }}
                            </p>

                        @else
                            <p style="margin: 0; font-weight: 600; color: var(--text);">
                                {{ $answer->answer_data ?? '— no answer —' }}
                            </p>
                        @endif
                    </div>

                    {{-- Correct Answer (only show when wrong) --}}
                    @if(!$answer->is_correct)
                        <div style="margin-top: 0.5rem; background-color: var(--success-light); border-radius: var(--radius); padding: 0.75rem 1rem;">
                            <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; color: var(--success);">Correct Answer</p>
                            @php $correctOpts = $question->options->where('is_correct', true); @endphp
                            <p style="margin: 0; font-weight: 600; color: var(--success);">
                                {{ $correctOpts->pluck('text_content')->filter()->join(', ') ?: '—' }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
    </div>

@endsection
