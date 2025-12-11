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
        $user = $this->userRepository->findByEmail($email);

        if (!$user) return false;

        if (!$user->is_active) {
            throw new \Exception('Your account has been deactivated. Please contact support.');
        }

        if (!Hash::check($password, $user->password)) return false;

        Auth::login($user, $remember);
        $this->userService->recordLogin($user);
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



