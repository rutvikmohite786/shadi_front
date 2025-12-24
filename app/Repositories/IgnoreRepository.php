<?php

namespace App\Repositories;

use App\Models\Ignore;
use Illuminate\Database\Eloquent\Collection;

class IgnoreRepository
{
    public function __construct(protected Ignore $model) {}

    public function find(int $id): ?Ignore
    {
        return $this->model->find($id);
    }

    public function findByUsers(int $userId, int $ignoredUserId): ?Ignore
    {
        return $this->model->where('user_id', $userId)->where('ignored_user_id', $ignoredUserId)->first();
    }

    public function create(array $data): Ignore
    {
        return $this->model->create($data);
    }

    public function delete(Ignore $ignore): bool
    {
        return $ignore->delete();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with(['ignoredUser', 'ignoredUser.profile'])
            ->orderBy('created_at', 'desc')->get();
    }

    public function isIgnored(int $userId, int $ignoredUserId): bool
    {
        return $this->model->where('user_id', $userId)->where('ignored_user_id', $ignoredUserId)->exists();
    }

    public function getIgnoredUserIds(int $userId): array
    {
        return $this->model->where('user_id', $userId)->pluck('ignored_user_id')->toArray();
    }
}











