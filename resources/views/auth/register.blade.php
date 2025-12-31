<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Shadi') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card" style="max-width: 500px;">
            <div class="auth-header">
                <a href="{{ url('/') }}" class="logo">
                    <i class="fas fa-heart"></i> {{ config('app.name', 'Shadi') }}
                </a>
                <h1>Create Account</h1>
                <p>Start your journey to find your life partner</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" data-validate>
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name') }}" 
                        placeholder="Enter your full name"
                        required 
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="flex gap-2 items-center">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email"
                            required
                            aria-label="Email address"
                        >
                        <button 
                            type="button" 
                            class="btn btn-outline"
                            id="open-email-verification"
                            aria-label="Open email verification"
                        >
                            Verify Email
                        </button>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        class="form-control @error('phone') is-invalid @enderror" 
                        value="{{ old('phone') }}" 
                        placeholder="10-digit Indian mobile (starts with 6/7/8/9)"
                        pattern="^[6-9][0-9]{9}$"
                        maxlength="10"
                        required
                        aria-label="10 digit Indian mobile number starting with 6,7,8 or 9"
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="gender" class="form-label">Gender</label>
                        <select 
                            id="gender" 
                            name="gender" 
                            class="form-select @error('gender') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input 
                            type="date" 
                            id="dob" 
                            name="dob" 
                            class="form-control @error('dob') is-invalid @enderror" 
                            value="{{ old('dob') }}"
                            max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                            required
                        >
                        @error('dob')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div style="position: relative;">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Create a strong password"
                            minlength="8"
                            required
                        >
                        <button 
                            type="button" 
                            class="password-toggle" 
                            data-target="password"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--gray-500); cursor: pointer;"
                            aria-label="Show password"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-text">Password must be at least 8 characters</div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control" 
                        placeholder="Confirm your password"
                        required
                    >
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
                    <label for="terms" class="form-check-label">
                        I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div id="email-verification-modal" class="modal" style="display: none;">
                <div class="modal-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.4);"></div>
                <div class="modal-content" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 24px; border-radius: 12px; width: min(480px, 90%); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <div class="flex items-center justify-between mb-3">
                        <h3 style="margin: 0;">Verify Email</h3>
                        <button type="button" id="close-email-verification" aria-label="Close verification modal" style="background: none; border: none; font-size: 18px; cursor: pointer;">×</button>
                    </div>
                    <div id="email-verify-status" class="mb-3" style="display:none;"></div>
                    <div class="form-group">
                        <label for="otp" class="form-label">Enter OTP</label>
                        <input 
                            type="text" 
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="6"
                            id="otp" 
                            name="otp" 
                            class="form-control"
                            placeholder="6-digit code"
                            aria-label="Enter verification code"
                        >
                        <small class="form-text">We sent a 6-digit code to your email.</small>
                    </div>
                    <button type="button" class="btn btn-primary btn-block" id="verify-otp-btn">Verify</button>
                </div>
            </div>

            <div class="auth-divider">
                <span>or continue with</span>
            </div>

            <div class="flex gap-3">
                <button class="btn btn-outline flex-1" disabled>
                    <i class="fab fa-google"></i> Google
                </button>
                <button class="btn btn-outline flex-1" disabled>
                    <i class="fab fa-facebook-f"></i> Facebook
                </button>
            </div>

            <div class="auth-footer">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-primary" style="font-weight: 600;">Login</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        (function() {
            const modal = document.getElementById('email-verification-modal');
            const openBtn = document.getElementById('open-email-verification');
            const closeBtn = document.getElementById('close-email-verification');
            const emailInput = document.getElementById('email');
            const overlay = modal ? modal.querySelector('.modal-overlay') : null;
            const statusBox = document.getElementById('email-verify-status');
            const verifyBtn = document.getElementById('verify-otp-btn');
            const otpInput = document.getElementById('otp');
            let currentEmail = '';

            const openModal = () => {
                currentEmail = emailInput?.value || '';
                if (!currentEmail) {
                    alert('Please enter your email first.');
                    return;
                }
                sendOtp(currentEmail);
                if (modal) modal.style.display = 'block';
            };

            const closeModal = () => {
                if (modal) modal.style.display = 'none';
            };

            const setStatus = (message, type = 'success') => {
                if (!statusBox) return;
                statusBox.style.display = 'block';
                statusBox.className = `alert alert-${type} mb-3`;
                statusBox.textContent = message;
            };

            const sendOtp = async (email) => {
                try {
                    const res = await fetch('{{ route('verification.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ email }),
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || 'Failed to send code');
                    }
                    setStatus(data.message || 'Code sent to your email.', 'success');
                } catch (err) {
                    setStatus(err.message || 'Could not send code.', 'danger');
                }
            };

            const verifyOtp = async () => {
                const otp = otpInput?.value || '';
                if (!currentEmail) {
                    setStatus('Enter email and send code first.', 'danger');
                    return;
                }
                if (!otp) {
                    setStatus('Please enter the OTP.', 'danger');
                    return;
                }
                try {
                    const res = await fetch('{{ route('verification.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ email: currentEmail, otp }),
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || 'Invalid code.');
                    }
                    setStatus(data.message || 'Email verified. You can now sign up.', 'success');
                    closeModal();
                } catch (err) {
                    setStatus(err.message || 'Verification failed.', 'danger');
                }
            };

            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (overlay) overlay.addEventListener('click', closeModal);
            if (verifyBtn) verifyBtn.addEventListener('click', verifyOtp);
        })();
    </script>
</body>
</html>

