<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Shadi') }} - Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card" style="max-width: 480px;">
            <div class="auth-header">
                <a href="{{ url('/') }}" class="logo">
                    <i class="fas fa-heart"></i> {{ config('app.name', 'Shadi') }}
                </a>
                <h1>Forgot Password</h1>
                <p>Enter your email to receive a reset link.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        value="{{ old('email') }}" 
                        placeholder="Enter your email"
                        required
                        aria-label="Email address for password reset"
                    >
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    Send Reset Link
                </button>
            </form>

            <div class="auth-footer">
                <a href="{{ route('login') }}" class="text-primary" style="font-weight: 600;">Back to login</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

