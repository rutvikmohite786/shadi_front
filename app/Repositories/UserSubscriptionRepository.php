<?php

namespace App\Repositories;

use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Collection;

class UserSubscriptionRepository
{
    public function __construct(protected UserSubscription $model) {}

    public function find(int $id): ?UserSubscription
    {
        return $this->model->find($id);
    }

    public function getActiveByUser(int $userId): ?UserSubscription
    {
        return $this->model->where('user_id', $userId)->active()->with('plan')->first();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->with('plan')->orderBy('created_at', 'desc')->get();
    }

    public function create(array $data): UserSubscription
    {
        return $this->model->create($data);
    }

    public function update(UserSubscription $subscription, array $data): bool
    {
        return $subscription->update($data);
    }

    public function incrementContactViews(UserSubscription $subscription): void
    {
        $subscription->increment('contact_views_used');
    }

    public function incrementChats(UserSubscription $subscription): void
    {
        $subscription->increment('chats_used');
    }

    public function incrementInterests(UserSubscription $subscription): void
    {
        $subscription->increment('interests_sent');
    }

    public function expireOldSubscriptions(): int
    {
        return $this->model->where('status', 'active')->where('end_date', '<', now())
            ->update(['status' => 'expired']);
    }

    public function getExpiringSubscriptions(int $daysAhead = 3): Collection
    {
        return $this->model->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays($daysAhead)])
            ->with(['user', 'plan'])->get();
    }

    public function getTotalRevenue(): float
    {
        return $this->model->join('plans', 'user_subscriptions.plan_id', '=', 'plans.id')->sum('plans.price');
    }

    public function getMonthlyRevenue(): float
    {
        return $this->model->join('plans', 'user_subscriptions.plan_id', '=', 'plans.id')
            ->whereMonth('user_subscriptions.created_at', now()->month)
            ->whereYear('user_subscriptions.created_at', now()->year)->sum('plans.price');
    }

    public function getActiveSubscriptionsCount(): int
    {
        return $this->model->active()->count();
    }
}



