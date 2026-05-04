@extends('layouts.app')

@section('content')

    <div style="max-width: 640px; margin: 0 auto;">

        {{-- ── Page Header ── --}}
        <div class="page-header" style="margin-bottom: 2rem;">
            <div class="page-header-title">
                <h2>Create New Quiz</h2>
                <p>Set a title and description to get started.</p>
            </div>
        </div>

        {{-- ── Form Card ── --}}
        <div class="card">
            <div class="card-body-lg">
                <form action="{{ route('quizzes.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label form-label-required" for="title">Quiz Title</label>
                        <input type="text" id="title" name="title" class="form-control"
                               value="{{ old('title') }}" required
                               placeholder="e.g. PHP Fundamentals">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Description <span class="text-muted" style="font-weight:400;">(optional)</span></label>
                        <textarea id="description" name="description" class="form-control"
                                  placeholder="Briefly describe what this quiz covers…">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('quizzes.index') }}" class="btn btn-default">← Back</a>
                        <button type="submit" class="btn btn-primary btn-lg">Create Quiz</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
