<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\IgnoreRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    public function __construct(protected IgnoreRepository $ignoreRepository) {}

    public function search(User $currentUser, array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $ignoredIds = $this->ignoreRepository->getIgnoredUserIds($currentUser->id);
        $ignoredIds[] = $currentUser->id;

        $query = User::where('is_active', true)->where('profile_completed', true)
            ->whereNotIn('id', $ignoredIds)
            ->with(['profile', 'profile.religion', 'profile.caste', 'profile.city', 'profile.education']);

        if (isset($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        } else {
            $query->where('gender', $currentUser->gender === 'male' ? 'female' : 'male');
        }

        if (!empty($filters['age_min'])) {
            $query->whereDate('dob', '<=', now()->subYears($filters['age_min']));
        }
        if (!empty($filters['age_max'])) {
            $query->whereDate('dob', '>=', now()->subYears($filters['age_max']));
        }

        $query->whereHas('profile', function ($q) use ($filters) {
            if (!empty($filters['height_min'])) $q->where('height', '>=', $filters['height_min']);
            if (!empty($filters['height_max'])) $q->where('height', '<=', $filters['height_max']);
            if (!empty($filters['religion_id'])) {
                $ids = is_array($filters['religion_id']) ? $filters['religion_id'] : [$filters['religion_id']];
                $q->whereIn('religion_id', $ids);
            }
            if (!empty($filters['caste_id'])) {
                $ids = is_array($filters['caste_id']) ? $filters['caste_id'] : [$filters['caste_id']];
                $q->whereIn('caste_id', $ids);
            }
            if (!empty($filters['mother_tongue_id'])) $q->where('mother_tongue_id', $filters['mother_tongue_id']);
            if (!empty($filters['marital_status'])) {
                $statuses = is_array($filters['marital_status']) ? $filters['marital_status'] : [$filters['marital_status']];
                $q->whereIn('marital_status', $statuses);
            }
            if (!empty($filters['education_id'])) {
                $ids = is_array($filters['education_id']) ? $filters['education_id'] : [$filters['education_id']];
                $q->whereIn('education_id', $ids);
            }
            if (!empty($filters['occupation_id'])) $q->where('occupation_id', $filters['occupation_id']);
            if (!empty($filters['country_id'])) $q->where('country_id', $filters['country_id']);
            if (!empty($filters['state_id'])) {
                $ids = is_array($filters['state_id']) ? $filters['state_id'] : [$filters['state_id']];
                $q->whereIn('state_id', $ids);
            }
            if (!empty($filters['city_id'])) {
                $ids = is_array($filters['city_id']) ? $filters['city_id'] : [$filters['city_id']];
                $q->whereIn('city_id', $ids);
            }
            if (!empty($filters['diet'])) {
                $diets = is_array($filters['diet']) ? $filters['diet'] : [$filters['diet']];
                $q->whereIn('diet', $diets);
            }
        });

        $sortBy = $filters['sort_by'] ?? 'last_login_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $allowedSorts = ['last_login_at', 'created_at', 'dob'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate($perPage);
    }

    public function quickSearch(User $currentUser, string $keyword, int $perPage = 20): LengthAwarePaginator
    {
        $ignoredIds = $this->ignoreRepository->getIgnoredUserIds($currentUser->id);
        $ignoredIds[] = $currentUser->id;

        return User::where('is_active', true)->where('profile_completed', true)
            ->whereNotIn('id', $ignoredIds)
            ->where('gender', $currentUser->gender === 'male' ? 'female' : 'male')
            ->where(fn($q) => $q->where('name', 'like', "%{$keyword}%")
                ->orWhereHas('profile', fn($pq) => $pq->where('about_me', 'like', "%{$keyword}%")))
            ->with(['profile', 'profile.religion', 'profile.city'])
            ->orderBy('last_login_at', 'desc')->paginate($perPage);
    }
}



