<?php

namespace App\Repositories;

use App\Models\UserMatch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MatchRepository
{
    public function __construct(protected UserMatch $model) {}

    public function find(int $id): ?UserMatch
    {
        return $this->model->find($id);
    }

    public function findByUsers(int $userId, int $matchedUserId): ?UserMatch
    {
        return $this->model->where('user_id', $userId)->where('matched_user_id', $matchedUserId)->first();
    }

    public function create(array $data): UserMatch
    {
        return $this->model->create($data);
    }

    public function getDailyMatches(int $userId, int $limit = 10): Collection
    {
        return $this->model->where('user_id', $userId)->whereDate('matched_date', today())
            ->with(['matchedUser', 'matchedUser.profile'])
            ->orderBy('match_score', 'desc')->limit($limit)->get();
    }

    public function getAllMatches(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->with(['matchedUser', 'matchedUser.profile'])
            ->orderBy('match_score', 'desc')->paginate($perPage);
    }

    public function getMutualMatches(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->where('is_mutual', true)
            ->with(['matchedUser', 'matchedUser.profile'])->get();
    }

    public function updateMutualStatus(int $userId, int $matchedUserId): void
    {
        $this->model->where('user_id', $userId)->where('matched_user_id', $matchedUserId)
            ->update(['is_mutual' => true]);
        $this->model->where('user_id', $matchedUserId)->where('matched_user_id', $userId)
            ->update(['is_mutual' => true]);
    }

    public function deleteOldMatches(int $daysOld = 7): int
    {
        return $this->model->where('matched_date', '<', now()->subDays($daysOld))
            ->where('is_mutual', false)->delete();
    }
}



