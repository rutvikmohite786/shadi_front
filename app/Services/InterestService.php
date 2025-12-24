<?php

namespace App\Services;

use App\Models\Interest;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\InterestRepository;
use App\Repositories\MatchRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InterestService
{
    public function __construct(
        protected InterestRepository $interestRepository,
        protected MatchRepository $matchRepository,
        protected SubscriptionService $subscriptionService
    ) {}

    public function sendInterest(User $sender, int $receiverId, ?string $message = null): array
    {
        if ($this->interestRepository->hasInterestBetween($sender->id, $receiverId)) {
            return ['success' => false, 'message' => 'Interest already exists between you and this user.'];
        }

        $canSend = $this->subscriptionService->canSendInterest($sender);
        if (!$canSend['allowed']) {
            return ['success' => false, 'message' => $canSend['message']];
        }

        $interest = $this->interestRepository->create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'status' => 'sent',
            'message' => $message,
        ]);

        $this->subscriptionService->incrementInterestSent($sender);
        $this->createNotification($sender->id, $receiverId, 'interest_received', 'New Interest Received', 
            'Someone has expressed interest in your profile.');

        return ['success' => true, 'message' => 'Interest sent successfully.', 'interest' => $interest];
    }

    public function acceptInterest(User $user, int $interestId): array
    {
        $interest = $this->interestRepository->find($interestId);

        if (!$interest || $interest->receiver_id !== $user->id) {
            return ['success' => false, 'message' => 'Interest not found.'];
        }

        if (!$interest->isPending()) {
            return ['success' => false, 'message' => 'This interest has already been responded to.'];
        }

        $this->interestRepository->update($interest, ['status' => 'accepted', 'responded_at' => now()]);
        $this->matchRepository->updateMutualStatus($interest->sender_id, $interest->receiver_id);
        $this->createNotification($user->id, $interest->sender_id, 'interest_accepted', 'Interest Accepted!', 
            'Your interest has been accepted. You can now connect!');

        return ['success' => true, 'message' => 'Interest accepted successfully.'];
    }

    public function rejectInterest(User $user, int $interestId): array
    {
        $interest = $this->interestRepository->find($interestId);

        if (!$interest || $interest->receiver_id !== $user->id) {
            return ['success' => false, 'message' => 'Interest not found.'];
        }

        if (!$interest->isPending()) {
            return ['success' => false, 'message' => 'This interest has already been responded to.'];
        }

        $this->interestRepository->update($interest, ['status' => 'rejected', 'responded_at' => now()]);
        return ['success' => true, 'message' => 'Interest declined.'];
    }

    public function cancelInterest(User $user, int $interestId): array
    {
        $interest = $this->interestRepository->find($interestId);

        if (!$interest || $interest->sender_id !== $user->id) {
            return ['success' => false, 'message' => 'Interest not found.'];
        }

        if (!$interest->isPending()) {
            return ['success' => false, 'message' => 'Cannot cancel a responded interest.'];
        }

        $this->interestRepository->delete($interest);
        return ['success' => true, 'message' => 'Interest cancelled.'];
    }

    public function getSentInterests(int $userId, ?int $limit = null): Collection|LengthAwarePaginator
    {
        return $this->interestRepository->getSentInterests($userId, $limit);
    }

    public function getReceivedInterests(int $userId, ?int $limit = null): Collection|LengthAwarePaginator
    {
        return $this->interestRepository->getReceivedInterests($userId, $limit);
    }

    public function getPendingInterests(int $userId): Collection
    {
        return $this->interestRepository->getPendingReceivedInterests($userId);
    }

    public function getAcceptedInterests(int $userId): Collection
    {
        return $this->interestRepository->getAcceptedInterests($userId);
    }

    public function getReceivedInterestsCount(int $userId): int
    {
        return $this->interestRepository->countReceivedInterests($userId);
    }

    public function getPendingInterestsCount(int $userId): int
    {
        return $this->interestRepository->countPendingInterests($userId);
    }

    public function getAcceptedInterestsCount(int $userId): int
    {
        return $this->interestRepository->countAcceptedInterests($userId);
    }

    public function hasAcceptedInterest(int $userId1, int $userId2): bool
    {
        return $this->interestRepository->hasAcceptedInterest($userId1, $userId2);
    }

    public function getInterestStatus(int $userId, int $otherUserId): ?string
    {
        $interest = $this->interestRepository->findByUsers($userId, $otherUserId);
        if ($interest) return 'sent_' . $interest->status;

        $interest = $this->interestRepository->findByUsers($otherUserId, $userId);
        if ($interest) return 'received_' . $interest->status;

        return null;
    }

    protected function createNotification(int $fromUserId, int $toUserId, string $type, string $title, string $message): void
    {
        Notification::create([
            'user_id' => $toUserId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => ['from_user_id' => $fromUserId],
        ]);
    }
}











