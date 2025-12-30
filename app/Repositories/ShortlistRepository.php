<?php

namespace App\Repositories;

use App\Models\Shortlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ShortlistRepository
{
    public function __construct(protected Shortlist $model) {}

    public function find(int $id): ?Shortlist
    {
        return $this->model->find($id);
    }

    public function findByUsers(int $userId, int $shortlistedUserId): ?Shortlist
    {
        return $this->model->where('user_id', $userId)->where('shortlisted_user_id', $shortlistedUserId)->first();
    }

    public function create(array $data): Shortlist
    {
        return $this->model->create($data);
    }

    public function delete(Shortlist $shortlist): bool
    {
        return $shortlist->delete();
    }

    public function getByUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->with(['shortlistedUser', 'shortlistedUser.profile'])
            ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function countByUser(int $userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }

    public function isShortlisted(int $userId, int $shortlistedUserId): bool
    {
        return $this->model->where('user_id', $userId)->where('shortlisted_user_id', $shortlistedUserId)->exists();
    }
}















