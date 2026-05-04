@extends('layouts.app')

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-title">
            <h2>Attempt History</h2>
            <p>Quiz: <strong>{{ $quiz->title }}</strong></p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-default">← Manage Quiz</a>
            <a href="{{ route('attempts.create', $quiz) }}" class="btn btn-primary">▶ Take Quiz</a>
        </div>
    </div>

    @if($attempts->isEmpty())
        <div class="card">
            <div class="card-body empty-state">
                <h3>No attempts yet</h3>
                <p>Be the first to attempt this quiz!</p>
                <a href="{{ route('attempts.create', $quiz) }}" class="btn btn-primary">▶ Take Quiz</a>
            </div>
        </div>
    @else

        {{-- ── Stats ── --}}
        @php
            $avgScore  = round($attempts->avg('score'), 1);
            $highScore = $attempts->max('score');
            $passCount = $attempts->filter(fn($a) => $a->score >= ($a->max_score * 0.5))->count();
        @endphp

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 1.25rem;">
                    <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; color: var(--text-muted);">Total Attempts</p>
                    <p style="font-size: 2rem; font-weight: 800; color: var(--text); margin: 0;">{{ $attempts->count() }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 1.25rem;">
                    <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; color: var(--text-muted);">Average Score</p>
                    <p style="font-size: 2rem; font-weight: 800; color: var(--primary); margin: 0;">
                        {{ $avgScore }}<span style="font-size: 1rem; color: var(--text-muted);"> / {{ $maxPossible }}</span>
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 1.25rem;">
                    <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; color: var(--text-muted);">Highest Score</p>
                    <p style="font-size: 2rem; font-weight: 800; color: var(--success); margin: 0;">
                        {{ $highScore }}<span style="font-size: 1rem; color: var(--text-muted);"> / {{ $maxPossible }}</span>
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 1.25rem;">
                    <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; color: var(--text-muted);">Pass Rate</p>
                    <p style="font-size: 2rem; font-weight: 800; color: var(--success); margin: 0;">
                        {{ $attempts->count() > 0 ? round(($passCount / $attempts->count()) * 100) : 0 }}<span style="font-size: 1rem; color: var(--text-muted);">%</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Attempts Table ── --}}
        <div class="card">
            <div class="card-body" style="padding: 0; overflow: hidden; border-radius: var(--radius-lg);">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                        <tr style="background-color: var(--surface-raised); border-bottom: 2px solid var(--border);">
                            <th style="padding: 0.9rem 1.25rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700;">Attempt</th>
                            <th style="padding: 0.9rem 1.25rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700;">Score</th>
                            <th style="padding: 0.9rem 1.25rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700;">Result</th>
                            <th style="padding: 0.9rem 1.25rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700;">Date & Time</th>
                            <th style="padding: 0.9rem 1.25rem; text-align: right; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $i => $attempt)
                            @php $passed = $attempt->score >= ($attempt->max_score * 0.5); @endphp
                            <tr style="border-bottom: 1px solid var(--border); transition: background-color 150ms ease;"
                                onmouseover="this.style.backgroundColor='var(--surface-raised)'"
                                onmouseout="this.style.backgroundColor='transparent'">

                                <td style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 600;">
                                    #{{ $attempts->count() - $i }}
                                </td>
                                <td style="padding: 0.85rem 1.25rem;">
                                    <span style="font-weight: 700; font-size: 1rem; color: var(--text);">{{ $attempt->score }}</span>
                                    <span style="color: var(--text-muted);"> / {{ $attempt->max_score }}</span>
                                </td>
                                <td style="padding: 0.85rem 1.25rem;">
                                    <span class="badge {{ $passed ? 'badge-success' : 'badge-danger' }}">
                                        {{ $passed ? 'Passed' : 'Failed' }}
                                    </span>
                                </td>
                                <td style="padding: 0.85rem 1.25rem; color: var(--text-muted);">
                                    {{ $attempt->created_at->format('M j, Y · g:i A') }}
                                </td>
                                <td style="padding: 0.85rem 1.25rem; text-align: right;">
                                    <a href="{{ route('attempts.show', $attempt) }}" class="btn btn-default btn-sm">View Results</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif

@endsection
