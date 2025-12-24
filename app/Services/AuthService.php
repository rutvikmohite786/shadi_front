<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        return $this->userService->register($data);
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
}






