<?php

namespace App\Repositories;

use App\Models\UserPhoto;
use Illuminate\Database\Eloquent\Collection;

class UserPhotoRepository
{
    public function __construct(protected UserPhoto $model) {}

    public function find(int $id): ?UserPhoto
    {
        return $this->model->find($id);
    }

    public function findByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('is_primary', 'desc')->orderBy('sort_order')->get();
    }

    public function findApprovedByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->approved()
            ->orderBy('is_primary', 'desc')->orderBy('sort_order')->get();
    }

    public function create(array $data): UserPhoto
    {
        return $this->model->create($data);
    }

    public function update(UserPhoto $photo, array $data): bool
    {
        return $photo->update($data);
    }

    public function delete(UserPhoto $photo): bool
    {
        return $photo->delete();
    }

    public function setPrimary(UserPhoto $photo): void
    {
        $this->model->where('user_id', $photo->user_id)->where('id', '!=', $photo->id)
            ->update(['is_primary' => false]);
        $photo->update(['is_primary' => true]);
    }

    public function countByUser(int $userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }

    public function getPendingApproval(): Collection
    {
        return $this->model->where('is_approved', false)->with('user')
            ->orderBy('created_at', 'asc')->get();
    }

    public function approve(UserPhoto $photo): bool
    {
        return $photo->update(['is_approved' => true]);
    }

    public function reject(UserPhoto $photo): bool
    {
        return $photo->delete();
    }
}
















