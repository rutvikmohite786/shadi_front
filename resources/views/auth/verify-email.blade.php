<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Shadi') }} - Verify Email</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card" style="max-width: 480px;">
            <div class="auth-header">
                <a href="{{ url('/') }}" class="logo">
                    <i class="fas fa-heart"></i> {{ config('app.name', 'Shadi') }}
                </a>
                <h1>Verify your email</h1>
                <p>Enter the 6-digit code sent to your email address.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify') }}" class="space-y-4" data-validate>
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', request('email')) }}"
                        readonly
                        tabindex="0"
                        aria-label="Email address used for verification"
                    >
                </div>

                <div class="form-group">
                    <label for="otp" class="form-label">Verification Code</label>
                    <input
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        id="otp"
                        name="otp"
                        class="form-control @error('otp') is-invalid @enderror"
                        placeholder="Enter 6-digit code"
                        required
                        autofocus
                        tabindex="0"
                        aria-label="Enter the 6 digit verification code"
                    >
                    @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Code expires in 15 minutes.</div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-check"></i> Verify Email
                </button>
            </form>

            <div class="auth-footer mt-4">
                <form method="POST" action="{{ route('verification.resend') }}" class="inline-flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
                    <button type="submit" class="btn btn-outline" tabindex="0" aria-label="Resend verification code">
                        <i class="fas fa-paper-plane"></i> Resend Code
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                Already verified?
                <a href="{{ route('login') }}" class="text-primary" style="font-weight: 600;">Login</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

