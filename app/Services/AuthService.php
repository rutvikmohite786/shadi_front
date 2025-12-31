<?php

namespace App\Services;

use App\Events\EmailVerificationRequested;
use App\Events\PasswordResetRequested;
use App\Models\EmailVerification;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected UserService $userService
    ) {}

    public function login(string $email, string $password, bool $remember = false): bool
    {
        // Try to find user by email first
        $user = $this->userRepository->findByEmail($email);
        
        // If not found by email, try phone number
        if (!$user) {
            $user = $this->userRepository->findByPhone($email);
        }

        if (!$user) {
            \Log::info('Login attempt failed: User not found', ['email_or_phone' => $email]);
            return false;
        }

        if (!$user->is_active) {
            \Log::warning('Login attempt failed: Account deactivated', ['user_id' => $user->id]);
            throw new \Exception('Your account has been deactivated. Please contact support.');
        }

        if (!$user->is_verified) {
            \Log::warning('Login attempt failed: Email not verified', ['user_id' => $user->id]);
            throw new \Exception('Please verify your email to continue. We can resend the code if needed.');
        }

        // Check password
        if (!Hash::check($password, $user->password)) {
            \Log::info('Login attempt failed: Invalid password', ['user_id' => $user->id, 'email_or_phone' => $email]);
            return false;
        }

        Auth::login($user, $remember);
        $this->userService->recordLogin($user);
        \Log::info('User logged in successfully', ['user_id' => $user->id]);
        return true;
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    public function register(array $data): User
    {
        $verifiedRecord = EmailVerification::where('email', $data['email'])
            ->whereNotNull('verified_at')
            ->first();

        if (!$verifiedRecord) {
            throw new \Exception('Please verify your email before signing up.');
        }

        $data['is_verified'] = true;
        $data['email_verified_at'] = now();

        $user = $this->userService->register($data);

        $verifiedRecord->delete();

        return $user;
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('Current password is incorrect.');
        }
        return $this->userService->updatePassword($user, $newPassword);
    }

    public function resetPassword(string $email, string $newPassword): bool
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) throw new \Exception('User not found.');
        return $this->userService->updatePassword($user, $newPassword);
    }

    public function sendPasswordReset(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new \Exception('No account found with that email.');
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        PasswordResetRequested::dispatch($email, $user->name, $token);
    }

    public function resetPasswordWithToken(string $email, string $token, string $newPassword): void
    {
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record) {
            throw new \Exception('Invalid or expired reset request.');
        }

        if (!Hash::check($token, $record->token)) {
            throw new \Exception('Invalid reset token.');
        }

        if (now()->subMinutes(60)->greaterThan($record->created_at)) {
            throw new \Exception('Reset token has expired.');
        }

        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new \Exception('User not found.');
        }

        $this->userService->updatePassword($user, $newPassword);
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }

    public function sendVerificationOtp(string $email): void
    {
        $otp = $this->generateOtp();
        $record = EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'otp_code' => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(15),
                'otp_attempts' => 0,
                'verified_at' => null,
            ]
        );

        EmailVerificationRequested::dispatch($record->email, $otp);
    }

    public function verifyEmailOtp(EmailVerification $pending, string $otp): void
    {
        if (!$pending->otp_code || !$pending->otp_expires_at) {
            throw new \Exception('Verification code not found. Please request a new code.');
        }

        if (now()->greaterThan($pending->otp_expires_at)) {
            throw new \Exception('The verification code has expired. Please request a new code.');
        }

        if ($pending->otp_attempts >= 5) {
            throw new \Exception('Too many attempts. Please request a new code.');
        }

        if (!Hash::check($otp, $pending->otp_code)) {
            $pending->increment('otp_attempts');
            throw new \Exception('Invalid verification code.');
        }

        $pending->update([
            'verified_at' => now(),
            // Keep column non-nullable; clear value with empty string
            'otp_code' => '',
            'otp_expires_at' => null,
            'otp_attempts' => 0,
        ]);
    }

    protected function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}