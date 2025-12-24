<?php

namespace App\Repositories;

use App\Models\UserProfile;

class UserProfileRepository
{
    public function __construct(protected UserProfile $model) {}

    public function findByUserId(int $userId): ?UserProfile
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function create(array $data): UserProfile
    {
        return $this->model->create($data);
    }

    public function update(UserProfile $profile, array $data): bool
    {
        return $profile->update($data);
    }

    public function updateOrCreate(int $userId, array $data): UserProfile
    {
        return $this->model->updateOrCreate(['user_id' => $userId], $data);
    }

    public function delete(UserProfile $profile): bool
    {
        return $profile->delete();
    }
}











