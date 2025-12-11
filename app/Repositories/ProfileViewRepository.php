<?php

namespace App\Repositories;

use App\Models\ProfileView;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileViewRepository
{
    public function __construct(protected ProfileView $model) {}

    public function create(array $data): ProfileView
    {
        return $this->model->create($data);
    }

    public function getViewers(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->where('viewed_id', $userId)
            ->with(['viewer', 'viewer.profile'])
            ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getRecentViewers(int $userId, int $limit = 10): Collection
    {
        return $this->model->where('viewed_id', $userId)
            ->with(['viewer', 'viewer.profile'])
            ->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function getViewedProfiles(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->where('viewer_id', $userId)
            ->with(['viewedUser', 'viewedUser.profile'])
            ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function countViews(int $userId): int
    {
        return $this->model->where('viewed_id', $userId)->count();
    }

    public function countTodayViews(int $userId): int
    {
        return $this->model->where('viewed_id', $userId)->whereDate('created_at', today())->count();
    }

    public function hasViewedRecently(int $viewerId, int $viewedId, int $hours = 24): bool
    {
        return $this->model->where('viewer_id', $viewerId)->where('viewed_id', $viewedId)
            ->where('created_at', '>=', now()->subHours($hours))->exists();
    }
}



