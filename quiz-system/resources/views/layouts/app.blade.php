<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dynamic Quiz System — create, manage, and attempt quizzes.">
    <title>QuizSystem</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @stack('styles')
</head>
<body>

    {{-- ── Navbar ── --}}
    <nav class="navbar">
        <div class="container navbar-inner">
            <a href="{{ route('quizzes.index') }}" class="navbar-brand">QuizSystem</a>
            <div class="navbar-nav">
                <a href="{{ route('quizzes.index') }}" class="btn btn-default">Dashboard</a>
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary">+ Create Quiz</a>
            </div>
        </div>
    </nav>

    {{-- ── Flash Messages ── --}}
    <div class="container" style="padding-top: 1.5rem; padding-bottom: 0;">
        @if(session('success'))
            <div class="alert alert-success">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    {{-- ── Main Content ── --}}
    <main class="page">
        <div class="container">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
