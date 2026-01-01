<?php

namespace App\Repositories;

use App\Models\ContactView;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactViewRepository
{
    public function __construct(protected ContactView $model) {}

    public function find(int $id): ?ContactView
    {
        return $this->model->find($id);
    }

    public function create(array $data): ContactView
    {
        return $this->model->create($data);
    }

    public function findByUsers(int $viewerId, int $viewedId): ?ContactView
    {
        return $this->model->where('viewer_id', $viewerId)->where('viewed_id', $viewedId)->first();
    }

    public function hasViewed(int $viewerId, int $viewedId): bool
    {
        return $this->model->where('viewer_id', $viewerId)->where('viewed_id', $viewedId)->exists();
    }

    public function getViewers(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->where('viewed_id', $userId)
            ->with(['viewer', 'viewer.profile'])
            ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getViewedContacts(int $userId): Collection
    {
        return $this->model->where('viewer_id', $userId)
            ->with(['viewedUser', 'viewedUser.profile'])
            ->orderBy('created_at', 'desc')->get();
    }

    public function countViewedByUser(int $userId): int
    {
        return $this->model->where('viewer_id', $userId)->count();
    }
}



















