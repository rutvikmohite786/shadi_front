<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        try {
            $user = $this->authService->register($request->validated());
            auth()->login($user);
            return redirect()->route('profile.edit')->with('success', 'Registration successful! Please complete your profile.');
        } catch (\Exception $e) {
            return back()->withInput($request->except('password', 'password_confirmation'))->withErrors(['email' => $e->getMessage()]);
        }
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $success = $this->authService->login($request->email, $request->password, $request->boolean('remember'));
            if (!$success) {
                return back()->withInput($request->only('email'))->withErrors(['email' => 'Invalid credentials.']);
            }
            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');
        } catch (\Exception $e) {
            return back()->withInput($request->only('email'))->withErrors(['email' => $e->getMessage()]);
        }
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}

















