<?php

namespace App\Repositories;

use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ChatRepository
{
    public function __construct(protected ChatMessage $model) {}

    public function find(int $id): ?ChatMessage
    {
        return $this->model->find($id);
    }

    public function create(array $data): ChatMessage
    {
        return $this->model->create($data);
    }

    public function update(ChatMessage $message, array $data): bool
    {
        return $message->update($data);
    }

    public function getConversation(int $userId1, int $userId2, int $limit = 50, int $offset = 0): Collection
    {
        return $this->model->betweenUsers($userId1, $userId2)->visibleTo($userId1)
            ->with(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo'])
            ->orderBy('created_at', 'desc')->skip($offset)->take($limit)->get()->reverse()->values();
    }

    public function getConversationList(int $userId): Collection
    {
        $subQuery = $this->model
            ->select(DB::raw('CASE WHEN sender_id = ' . $userId . ' THEN receiver_id ELSE sender_id END as other_user_id, MAX(id) as max_id'))
            ->where(fn($q) => $q->where('sender_id', $userId)->where('is_deleted_by_sender', false))
            ->orWhere(fn($q) => $q->where('receiver_id', $userId)->where('is_deleted_by_receiver', false))
            ->groupBy('other_user_id');

        return $this->model
            ->joinSub($subQuery, 'latest', fn($join) => $join->on('chat_messages.id', '=', 'latest.max_id'))
            ->with(['sender:id,name,profile_photo', 'receiver:id,name,profile_photo'])
            ->orderBy('chat_messages.created_at', 'desc')->get();
    }

    public function markAsRead(int $senderId, int $receiverId): int
    {
        return $this->model->where('sender_id', $senderId)->where('receiver_id', $receiverId)
            ->where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->model->where('receiver_id', $userId)->where('is_read', false)->count();
    }

    public function deleteConversation(int $userId, int $otherUserId): void
    {
        $this->model->where('sender_id', $userId)->where('receiver_id', $otherUserId)
            ->update(['is_deleted_by_sender' => true]);
        $this->model->where('sender_id', $otherUserId)->where('receiver_id', $userId)
            ->update(['is_deleted_by_receiver' => true]);
    }
}



