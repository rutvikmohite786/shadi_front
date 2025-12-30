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
            return '';
        }

        // Check if value is in encrypted format (hex:hex pattern like "16f2f8fa9e3d669cd4429d461b600f6c:63656b1603ad20e18f1eb79c41906518")
        $isEncryptedFormat = preg_match('/^[a-f0-9]{32}:[a-f0-9]+$/i', $value);
        
        // If it doesn't look encrypted, return as-is (might be plain text for backward compatibility)
        if (!$isEncryptedFormat) {
            return $value;
        }

        // Try to decrypt encrypted message
        try {
            $encryptionService = app(EncryptionService::class);
            $decrypted = $encryptionService->decrypt($value);
            
            // Check if decryption actually worked
            // If the result is the same as input or still looks encrypted, decryption failed
            if ($decrypted === $value || preg_match('/^[a-f0-9]{32}:[a-f0-9]+$/i', $decrypted)) {
                // Decryption failed - this message was encrypted with a different key
                // Try to show a helpful message instead of the encrypted string
                \Log::warning('Message decryption failed - key mismatch', [
                    'message_id' => $this->id ?? 'unknown',
                    'sender_id' => $this->sender_id ?? 'unknown',
                    'receiver_id' => $this->receiver_id ?? 'unknown',
                    'created_at' => $this->created_at ?? 'unknown'
                ]);
                
                // Show a simple indicator that this is an old message
                return '[Old message - encryption key mismatch]';
            }
            
            // Decryption successful
            return $decrypted;
        } catch (\Exception $e) {
            \Log::error('Exception decrypting message: ' . $e->getMessage(), [
                'message_id' => $this->id ?? 'unknown',
                'sender_id' => $this->sender_id ?? 'unknown',
                'receiver_id' => $this->receiver_id ?? 'unknown',
                'value_preview' => substr($value, 0, 50),
                'exception' => get_class($e)
            ]);
            
            // If decryption throws exception, return the encrypted value
            // This allows the system to continue functioning
            return $value;
        }
    }
}




