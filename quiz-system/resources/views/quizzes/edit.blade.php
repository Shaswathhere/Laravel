@extends('layouts.app')

@section('content')

    <div style="max-width: 640px; margin: 0 auto;">

        {{-- ── Page Header ── --}}
        <div class="page-header">
            <div class="page-header-title">
                <h2>Edit Quiz</h2>
                <p>Update the title or description of this quiz.</p>
            </div>
        </div>

        {{-- ── Form Card ── --}}
        <div class="card">
            <div class="card-body-lg">
                <form action="{{ route('quizzes.update', $quiz) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label form-label-required" for="title">Quiz Title</label>
                        <input type="text" id="title" name="title" class="form-control"
                               value="{{ old('title', $quiz->title) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Description</label>
                        <textarea id="description" name="description" class="form-control">{{ old('description', $quiz->description) }}</textarea>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-default">← Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
