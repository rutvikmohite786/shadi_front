<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function __construct(protected User $model) {}

    public function find(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByPhone(string $phone): ?User
    {
        return $this->model->where('phone', $phone)->first();
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function getActiveUsers(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->active()->verified()->profileCompleted()
            ->with('profile')->orderBy('last_login_at', 'desc')->paginate($perPage);
    }

    public function getNewProfiles(int $limit = 10): Collection
    {
        return $this->model->active()->verified()->profileCompleted()
            ->with('profile')->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function searchProfiles(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->active()->verified()->profileCompleted()
            ->with(['profile', 'profile.religion', 'profile.caste', 'profile.city']);

        if (isset($filters['looking_for'])) {
            $query->where('gender', $filters['looking_for']);
        }

        if (isset($filters['age_min'])) {
            $query->whereDate('dob', '<=', now()->subYears($filters['age_min']));
        }
        if (isset($filters['age_max'])) {
            $query->whereDate('dob', '>=', now()->subYears($filters['age_max']));
        }

        if (isset($filters['religion_id']) || isset($filters['caste_id']) || 
            isset($filters['height_min']) || isset($filters['height_max']) ||
            isset($filters['marital_status']) || isset($filters['education_id']) ||
            isset($filters['country_id']) || isset($filters['state_id']) || isset($filters['city_id'])) {
            
            $query->whereHas('profile', function ($q) use ($filters) {
                if (isset($filters['religion_id'])) $q->where('religion_id', $filters['religion_id']);
                if (isset($filters['caste_id'])) $q->where('caste_id', $filters['caste_id']);
                if (isset($filters['height_min'])) $q->where('height', '>=', $filters['height_min']);
                if (isset($filters['height_max'])) $q->where('height', '<=', $filters['height_max']);
                if (isset($filters['marital_status'])) $q->where('marital_status', $filters['marital_status']);
                if (isset($filters['education_id'])) $q->where('education_id', $filters['education_id']);
                if (isset($filters['country_id'])) $q->where('country_id', $filters['country_id']);
                if (isset($filters['state_id'])) $q->where('state_id', $filters['state_id']);
                if (isset($filters['city_id'])) $q->where('city_id', $filters['city_id']);
            });
        }

        return $query->orderBy('last_login_at', 'desc')->paginate($perPage);
    }

    public function getUserWithFullProfile(int $userId): ?User
    {
        return $this->model->with([
            'profile', 'profile.religion', 'profile.caste', 'profile.subcaste',
            'profile.motherTongue', 'profile.country', 'profile.state', 'profile.city',
            'profile.education', 'profile.occupation',
            'photos' => fn($q) => $q->approved()->orderBy('sort_order'),
        ])->find($userId);
    }

    public function updateLastLogin(User $user): void
    {
        $user->update(['last_login_at' => now()]);
    }

    public function getTotalUsersCount(): int
    {
        return $this->model->count();
    }

    public function getActiveUsersCount(): int
    {
        return $this->model->active()->count();
    }

    public function getMaleUsersCount(): int
    {
        return $this->model->active()->male()->count();
    }

    public function getFemaleUsersCount(): int
    {
        return $this->model->active()->female()->count();
    }
}



