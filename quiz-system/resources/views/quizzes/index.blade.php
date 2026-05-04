@extends('layouts.app')

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-title">
            <h2>All Quizzes</h2>
            <p>Browse, manage, and attempt quizzes.</p>
        </div>
    </div>

    @if($quizzes->isEmpty())
        {{-- ── Empty State ── --}}
        <div class="card">
            <div class="card-body empty-state">
                <h3>No quizzes yet</h3>
                <p>Get started by creating your very first quiz.</p>
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary btn-lg">+ Create Quiz</a>
            </div>
        </div>
    @else
        {{-- ── Quiz Grid ── --}}
        <div class="quiz-grid">
            @foreach($quizzes as $quiz)
                <div class="card quiz-card">
                    <div class="card-body">
                        <h3>{{ $quiz->title }}</h3>
                        <p>{{ $quiz->description ? Str::limit($quiz->description, 120) : 'No description provided.' }}</p>
                        <div class="quiz-card-footer">
                            <div class="quiz-card-footer-left">
                                <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-default btn-sm">Manage</a>
                                <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-default btn-sm">Edit</a>
                                <a href="{{ route('attempts.index', $quiz) }}" class="btn btn-default btn-sm">History</a>
                                <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST"
                                      onsubmit="return confirm('Delete this quiz and all its content?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                            <a href="{{ route('attempts.create', $quiz) }}" class="btn btn-primary btn-sm">Attempt →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
