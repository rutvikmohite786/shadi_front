<?php

namespace App\Models;

use App\Services\EncryptionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'sender_id', 'receiver_id', 'message', 'message_type',
        'attachment_path', 'is_read', 'read_at',
        'is_deleted_by_sender', 'is_deleted_by_receiver',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'is_deleted_by_sender' => 'boolean',
            'is_deleted_by_receiver' => 'boolean',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeBetweenUsers($query, int $userId1, int $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId2)->where('receiver_id', $userId1);
        });
    }

    public function scopeVisibleTo($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('is_deleted_by_sender', false);
        })->orWhere(function ($q) use ($userId) {
            $q->where('receiver_id', $userId)->where('is_deleted_by_receiver', false);
        });
    }

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    public function deleteFor(int $userId): void
    {
        if ($this->sender_id === $userId) {
            $this->update(['is_deleted_by_sender' => true]);
        } elseif ($this->receiver_id === $userId) {
            $this->update(['is_deleted_by_receiver' => true]);
        }
    }

    public function getAttachmentUrl(): ?string
    {
        return $this->attachment_path ? asset('storage/chat/' . $this->attachment_path) : null;
    }

    /**
     * Accessor to automatically decrypt message when accessed
     * Note: Encryption is handled in ChatService when saving
     */
    public function getMessageAttribute($value): string
    {
        if (empty($value)) {
            return $value;
        }

        try {
            $encryptionService = app(EncryptionService::class);
            return $encryptionService->decrypt($value);
        } catch (\Exception $e) {
            \Log::error('Error decrypting message: ' . $e->getMessage());
            // Return original value if decryption fails (for backward compatibility)
            return $value;
        }
    }
}




