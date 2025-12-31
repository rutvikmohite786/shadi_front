<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        try {
            $this->authService->sendPasswordReset($request->email);
            return back()->with('success', 'We have emailed your password reset link.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    public function showResetForm(string $token): View
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => request('email')]);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        try {
            $this->authService->resetPasswordWithToken(
                $request->email,
                $request->token,
                $request->password
            );
            return redirect()->route('login')->with('success', 'Password has been reset. You can log in now.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }
}

