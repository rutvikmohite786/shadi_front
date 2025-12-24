<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserProfileRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected UserProfileRepository $profileRepository
    ) {}

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        $this->profileRepository->create(['user_id' => $user->id]);
        return $user;
    }

    public function updateBasicInfo(User $user, array $data): bool
    {
        $result = $this->userRepository->update($user, $data);
        // Refresh profile relationship to ensure latest data
        $user->load('profile');
        return $result;
    }

    public function updateProfile(User $user, array $profileData): bool
    {
        $this->profileRepository->updateOrCreate($user->id, $profileData);
        $this->checkProfileCompletion($user);
        return true;
    }

    public function updatePassword(User $user, string $newPassword): bool
    {
        return $this->userRepository->update($user, ['password' => Hash::make($newPassword)]);
    }

    public function updatePhotoPrivacy(User $user, string $privacy): bool
    {
        return $this->userRepository->update($user, ['photo_privacy' => $privacy]);
    }

    public function getProfile(int $userId): ?User
    {
        return $this->userRepository->getUserWithFullProfile($userId);
    }

    public function searchProfiles(User $currentUser, array $filters): LengthAwarePaginator
    {
        $filters['exclude_user_id'] = $currentUser->id;
        if (!isset($filters['looking_for'])) {
            $filters['looking_for'] = $currentUser->gender === 'male' ? 'female' : 'male';
        }
        return $this->userRepository->searchProfiles($filters);
    }

    public function getNewProfiles(int $limit = 10)
    {
        return $this->userRepository->getNewProfiles($limit);
    }

    public function deactivateAccount(User $user): bool
    {
        return $this->userRepository->update($user, ['is_active' => false]);
    }

    public function activateAccount(User $user): bool
    {
        return $this->userRepository->update($user, ['is_active' => true]);
    }

    public function verifyUser(User $user): bool
    {
        return $this->userRepository->update($user, ['is_verified' => true]);
    }

    public function recordLogin(User $user): void
    {
        $this->userRepository->updateLastLogin($user);
    }

    protected function checkProfileCompletion(User $user): void
    {
        $profile = $user->profile()->first();
        if (!$profile) return;

        $requiredFields = ['marital_status', 'religion_id', 'country_id', 'state_id', 'education_id'];
        $isComplete = true;
        foreach ($requiredFields as $field) {
            if (empty($profile->$field)) {
                $isComplete = false;
                break;
            }
        }

        if ($isComplete && !$user->profile_completed) {
            $this->userRepository->update($user, ['profile_completed' => true]);
        }
    }

    public function getDashboardStats(): array
    {
        return [
            'total_users' => $this->userRepository->getTotalUsersCount(),
            'active_users' => $this->userRepository->getActiveUsersCount(),
            'male_users' => $this->userRepository->getMaleUsersCount(),
            'female_users' => $this->userRepository->getFemaleUsersCount(),
        ];
    }
}







