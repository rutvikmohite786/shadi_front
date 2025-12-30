<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Services\EncryptionService;
use Illuminate\Database\Eloquent\Collection;

class ChatService
{
    public function __construct(
        protected ChatRepository $chatRepository,
        protected InterestService $interestService,
        protected SubscriptionService $subscriptionService
    ) {}

    public function canChat(User $user, int $otherUserId): array
    {
        $hasAcceptedInterest = $this->interestService->hasAcceptedInterest($user->id, $otherUserId);

        if (!$hasAcceptedInterest) {
            $subscription = $this->subscriptionService->getCurrentSubscription($user);
            if (!$subscription || !$subscription->plan->can_chat) {
                return ['allowed' => false, 'message' => 'You can only chat with users who have accepted your interest or if you have a premium subscription.'];
            }
        }

        $canChat = $this->subscriptionService->canChat($user);
        if (!$canChat['allowed']) return $canChat;

        return ['allowed' => true, 'message' => ''];
    }

    public function sendMessage(User $sender, int $receiverId, string $message, string $type = 'text', ?string $attachment = null): array
    {
        $canChat = $this->canChat($sender, $receiverId);
        if (!$canChat['allowed']) {
            return ['success' => false, 'message' => $canChat['message']];
        }

        // Temporarily disable encryption to fix decryption issues
        // TODO: Re-enable encryption once key derivation is fixed between Laravel and Node.js
        $encryptedMessage = $message; // Store as plain text for now
        
        // Uncomment below to re-enable encryption:
        // $encryptionService = app(EncryptionService::class);
        // $encryptedMessage = $encryptionService->encrypt($message);

        $chatMessage = $this->chatRepository->create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'message' => $encryptedMessage,
            'message_type' => $type,
            'attachment_path' => $attachment,
        ]);

        $existingMessages = $this->chatRepository->getConversation($sender->id, $receiverId, 2);
        if ($existingMessages->count() <= 1) {
            $this->subscriptionService->incrementChat($sender);
        }

        // Decrypt message before returning
        $chatMessage->message = $message; // Use original decrypted message for response

        return [
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $chatMessage->load(['sender:id,name,profile_photo']),
        ];
    }

    public function getConversation(int $userId, int $otherUserId, int $limit = 50, int $offset = 0): Collection
    {
        $this->chatRepository->markAsRead($otherUserId, $userId);
        return $this->chatRepository->getConversation($userId, $otherUserId, $limit, $offset);
    }

    public function getConversationList(int $userId): Collection
    {
        $conversations = $this->chatRepository->getConversationList($userId);
        
        // Add unread count for each conversation
        return $conversations->map(function ($message) use ($userId) {
            $otherUserId = $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            $message->unread_count = $this->chatRepository->getUnreadCountForConversation($userId, $otherUserId);
            return $message;
        });
    }

    public function markAsRead(int $userId, int $senderId): void
    {
        $this->chatRepository->markAsRead($senderId, $userId);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->chatRepository->getUnreadCount($userId);
    }

    public function deleteMessage(User $user, int $messageId): bool
    {
        $message = $this->chatRepository->find($messageId);
        if (!$message) return false;
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) return false;
        $message->deleteFor($user->id);
        return true;
    }

    public function deleteConversation(User $user, int $otherUserId): void
    {
        $this->chatRepository->deleteConversation($user->id, $otherUserId);
    }

    public function getChatToken(User $user): string
    {
        $payload = ['user_id' => $user->id, 'name' => $user->name, 'exp' => time() + 3600];
        $header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payloadEncoded = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payloadEncoded", config('app.key'));
        return "$header.$payloadEncoded.$signature";
    }
}




