@extends('layouts.app')

@section('content')

    <div style="max-width: 720px; margin: 0 auto;">

        {{-- ── Page Header ── --}}
        <div class="page-header">
            <div class="page-header-title">
                <h2>Edit Question</h2>
                <p>In quiz: <strong>{{ $quiz->title }}</strong></p>
            </div>
            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-default">← Back to Quiz</a>
        </div>

        {{-- ── Form Card ── --}}
        <div class="card">
            <div class="card-body-lg">
                <form action="{{ route('questions.update', $question) }}" method="POST" enctype="multipart/form-data" id="questionForm">
                    @csrf
                    @method('PUT')

                    {{-- Question Type --}}
                    <div class="form-group">
                        <label class="form-label form-label-required" for="type">Question Type</label>
                        <select id="type" name="type" class="form-control" required onchange="handleTypeChange()">
                            <option value="binary"          {{ $question->type === 'binary'          ? 'selected' : '' }}>Binary — True / False or Yes / No</option>
                            <option value="single_choice"   {{ $question->type === 'single_choice'   ? 'selected' : '' }}>Single Choice — one correct answer</option>
                            <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice — multiple correct answers</option>
                            <option value="number_input"    {{ $question->type === 'number_input'    ? 'selected' : '' }}>Number Input — exact number answer</option>
                            <option value="text_input"      {{ $question->type === 'text_input'      ? 'selected' : '' }}>Text Input — written answer</option>
                        </select>
                    </div>

                    {{-- Question Content --}}
                    <div class="form-group">
                        <label class="form-label form-label-required" for="content">Question</label>
                        <textarea id="content" name="content" class="form-control" rows="3" required>{{ old('content', $question->content) }}</textarea>
                        <span class="form-hint">HTML tags are supported for rich formatting.</span>
                    </div>

                    {{-- Media --}}
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="image">Replace Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            @if($question->image_path)
                                <span class="form-hint">Current: {{ basename($question->image_path) }}</span>
                            @endif
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="video_url">Video URL</label>
                            <input type="url" id="video_url" name="video_url" class="form-control"
                                   placeholder="https://youtube.com/…"
                                   value="{{ old('video_url', $question->video_url) }}">
                        </div>
                    </div>

                    {{-- Marks --}}
                    <div class="form-group" style="max-width: 180px; margin-top: 1.5rem;">
                        <label class="form-label form-label-required" for="marks">Marks</label>
                        <input type="number" id="marks" name="marks" class="form-control"
                               value="{{ old('marks', $question->marks) }}" min="0" step="0.5" required>
                    </div>

                    <hr class="form-divider">

                    {{-- Dynamic Options Container --}}
                    <div id="optionsSection">
                        <div id="optionsHeader"></div>
                        <div id="optionsContainer"></div>
                        <div id="addOptionBtnContainer"></div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">Update Question</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@include('questions._options_script', ['existingOptions' => $question->options, 'questionType' => $question->type])
@endpush
