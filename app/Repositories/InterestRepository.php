<?php

namespace App\Repositories;

use App\Models\Interest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InterestRepository
{
    public function __construct(protected Interest $model) {}

    public function find(int $id): ?Interest
    {
        return $this->model->find($id);
    }

    public function findByUsers(int $senderId, int $receiverId): ?Interest
    {
        return $this->model->where('sender_id', $senderId)->where('receiver_id', $receiverId)->first();
    }

    public function create(array $data): Interest
    {
        return $this->model->create($data);
    }

    public function update(Interest $interest, array $data): bool
    {
        return $interest->update($data);
    }

    public function delete(Interest $interest): bool
    {
        return $interest->delete();
    }

    public function getSentInterests(int $userId, ?int $limit = null): Collection|LengthAwarePaginator
    {
        $query = $this->model->where('sender_id', $userId)
            ->with(['receiver', 'receiver.profile'])->orderBy('created_at', 'desc');
        return $limit ? $query->limit($limit)->get() : $query->paginate(20);
    }

    public function getReceivedInterests(int $userId, ?int $limit = null): Collection|LengthAwarePaginator
    {
        $query = $this->model->where('receiver_id', $userId)
            ->with(['sender', 'sender.profile'])->orderBy('created_at', 'desc');
        return $limit ? $query->limit($limit)->get() : $query->paginate(20);
    }

    public function getPendingReceivedInterests(int $userId): Collection
    {
        return $this->model->where('receiver_id', $userId)->pending()
            ->with(['sender', 'sender.profile'])->orderBy('created_at', 'desc')->get();
    }

    public function getAcceptedInterests(int $userId): Collection
    {
        return $this->model->where(fn($q) => $q->where('sender_id', $userId)->orWhere('receiver_id', $userId))
            ->accepted()->with(['sender', 'sender.profile', 'receiver', 'receiver.profile'])
            ->orderBy('responded_at', 'desc')->get();
    }

    public function countReceivedInterests(int $userId): int
    {
        return $this->model->where('receiver_id', $userId)->count();
    }

    public function countPendingInterests(int $userId): int
    {
        return $this->model->where('receiver_id', $userId)->pending()->count();
    }

    public function countAcceptedInterests(int $userId): int
    {
        return $this->model->where(fn($q) => $q->where('sender_id', $userId)->orWhere('receiver_id', $userId))
            ->accepted()->count();
    }

    public function countSentInterests(int $userId): int
    {
        return $this->model->where('sender_id', $userId)->count();
    }

    public function hasInterestBetween(int $userId1, int $userId2): bool
    {
        return $this->model->where(fn($q) => $q->where('sender_id', $userId1)->where('receiver_id', $userId2))
            ->orWhere(fn($q) => $q->where('sender_id', $userId2)->where('receiver_id', $userId1))->exists();
    }

    public function hasAcceptedInterest(int $userId1, int $userId2): bool
    {
        return $this->model->where(fn($q) => $q->where('sender_id', $userId1)->where('receiver_id', $userId2))
            ->orWhere(fn($q) => $q->where('sender_id', $userId2)->where('receiver_id', $userId1))
            ->accepted()->exists();
    }
}











