<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Shadi') }} - Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card" style="max-width: 480px;">
            <div class="auth-header">
                <a href="{{ url('/') }}" class="logo">
                    <i class="fas a-heart"></i> {{ config('app.name', 'Shadi') }}
                </a>
                <h1>Reset Password</h1>
                <p>Enter your new password below.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required
                        minlength="8"
                        aria-label="New password"
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control" 
                        required
                        aria-label="Confirm new password"
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Update Password
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

