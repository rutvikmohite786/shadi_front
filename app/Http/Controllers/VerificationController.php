<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\EmailVerification;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class VerificationController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showVerifyForm(): View
    {
        return view('auth.verify-email');
    }

    public function verify(VerifyOtpRequest $request): RedirectResponse|JsonResponse
    {
        $pending = EmailVerification::where('email', $request->email)->first();
        if (!$pending) {
            return $this->respond($request, ['message' => 'No pending verification found for this email.'], 404);
        }
        try {
            $this->authService->verifyEmailOtp($pending, $request->otp);
            return $this->respond($request, ['message' => 'Email verified. You can now sign up.']);
        } catch (\Exception $e) {
            return $this->respond($request, ['message' => $e->getMessage()], 422);
        }
    }

    public function resend(ResendOtpRequest $request): RedirectResponse|JsonResponse
    {
        $pending = EmailVerification::where('email', $request->email)->first();
        if (!$pending) {
            return $this->respond($request, ['message' => 'No pending verification found for this email.'], 404);
        }

        $this->authService->sendVerificationOtp($pending->email);

        return $this->respond($request, ['message' => 'A new verification code has been sent to your email.']);
    }

    public function requestOtp(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $this->authService->sendVerificationOtp($request->email);
        return $this->respond($request, ['message' => 'Verification code sent to your email.']);
    }

    protected function respond(Request $request, array $payload, int $status = 200): RedirectResponse|JsonResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($payload, $status);
        }

        if ($status >= 400) {
            return back()->withErrors(['email_verification' => $payload['message'] ?? 'Failed'])->withInput();
        }

        return back()->with('success', $payload['message'] ?? 'Success');
    }
}

