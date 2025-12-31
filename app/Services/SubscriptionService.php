<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repositories\PlanRepository;
use App\Repositories\UserSubscriptionRepository;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionService
{
    protected int $freeContactViews = 5;
    protected int $freeInterests = 10;
    protected int $freeChats = 3;

    public function __construct(
        protected PlanRepository $planRepository,
        protected UserSubscriptionRepository $subscriptionRepository
    ) {}

    public function getAvailablePlans(): Collection
    {
        return $this->planRepository->getActive();
    }

    public function getPlan(int $planId): ?Plan
    {
        return $this->planRepository->find($planId);
    }

    public function getCurrentSubscription(User $user): ?UserSubscription
    {
        return $this->subscriptionRepository->getActiveByUser($user->id);
    }

    public function getSubscriptionHistory(User $user): Collection
    {
        return $this->subscriptionRepository->getByUser($user->id);
    }

    public function subscribe(User $user, int $planId, ?string $paymentReference = null): UserSubscription
    {
        $plan = $this->planRepository->find($planId);
        if (!$plan || !$plan->is_active) {
            throw new \Exception('Invalid or inactive plan.');
        }

        $existingSubscription = $this->getCurrentSubscription($user);
        if ($existingSubscription) {
            $this->subscriptionRepository->update($existingSubscription, ['status' => 'cancelled']);
        }

        return $this->subscriptionRepository->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration_days),
            'status' => 'active',
            'payment_reference' => $paymentReference,
        ]);
    }

    public function cancelSubscription(User $user): bool
    {
        $subscription = $this->getCurrentSubscription($user);
        if (!$subscription) return false;
        return $this->subscriptionRepository->update($subscription, ['status' => 'cancelled']);
    }

    public function canViewContact(User $user): array
    {
        $subscription = $this->getCurrentSubscription($user);

        if (!$subscription) {
            $viewedCount = $user->contactViews()->count();
            if ($viewedCount >= $this->freeContactViews) {
                return ['allowed' => false, 'message' => 'You have reached your free contact view limit. Please upgrade to view more contacts.'];
            }
            return ['allowed' => true, 'message' => ''];
        }

        if (!$subscription->plan->can_see_contact) {
            return ['allowed' => false, 'message' => 'Your plan does not include contact viewing. Please upgrade.'];
        }

        if (!$subscription->canViewMoreContacts()) {
            return ['allowed' => false, 'message' => 'You have reached your contact view limit for this subscription.'];
        }

        return ['allowed' => true, 'message' => ''];
    }

    public function canSendInterest(User $user): array
    {
        $subscription = $this->getCurrentSubscription($user);

        if (!$subscription) {
            $sentCount = $user->sentInterests()->count();
            if ($sentCount >= $this->freeInterests) {
                return ['allowed' => false, 'message' => 'You have reached your free interest limit. Please upgrade to send more interests.'];
            }
            return ['allowed' => true, 'message' => ''];
        }

        if (!$subscription->canSendMoreInterests()) {
            return ['allowed' => false, 'message' => 'You have reached your interest limit for this subscription.'];
        }

        return ['allowed' => true, 'message' => ''];
    }

    public function canChat(User $user): array
    {
        $subscription = $this->getCurrentSubscription($user);

        if (!$subscription) {
            $chatCount = $user->sentMessages()->distinct('receiver_id')->count('receiver_id');
            if ($chatCount >= $this->freeChats) {
                return ['allowed' => false, 'message' => 'You have reached your free chat limit. Please upgrade to chat with more users.'];
            }
            return ['allowed' => true, 'message' => ''];
        }

        if (!$subscription->plan->can_chat) {
            return ['allowed' => false, 'message' => 'Your plan does not include chat. Please upgrade.'];
        }

        if (!$subscription->canChatMore()) {
            return ['allowed' => false, 'message' => 'You have reached your chat limit for this subscription.'];
        }

        return ['allowed' => true, 'message' => ''];
    }

    public function incrementContactView(User $user): void
    {
        $subscription = $this->getCurrentSubscription($user);
        if ($subscription) $this->subscriptionRepository->incrementContactViews($subscription);
    }

    public function incrementInterestSent(User $user): void
    {
        $subscription = $this->getCurrentSubscription($user);
        if ($subscription) $this->subscriptionRepository->incrementInterests($subscription);
    }

    public function incrementChat(User $user): void
    {
        $subscription = $this->getCurrentSubscription($user);
        if ($subscription) $this->subscriptionRepository->incrementChats($subscription);
    }

    public function getUsageSummary(User $user): array
    {
        $subscription = $this->getCurrentSubscription($user);

        if (!$subscription) {
            return [
                'plan' => 'Free',
                'expires' => null,
                'contacts_remaining' => $this->freeContactViews - $user->contactViews()->count(),
                'interests_remaining' => $this->freeInterests - $user->sentInterests()->count(),
                'chats_remaining' => $this->freeChats,
            ];
        }

        return [
            'plan' => $subscription->plan->name,
            'expires' => $subscription->end_date,
            'days_remaining' => $subscription->daysRemaining(),
            'contacts_remaining' => $subscription->getRemainingContacts(),
            'interests_remaining' => $subscription->getRemainingInterests(),
            'chats_remaining' => $subscription->getRemainingChats(),
        ];
    }

    public function expireSubscriptions(): int
    {
        return $this->subscriptionRepository->expireOldSubscriptions();
    }
}

















