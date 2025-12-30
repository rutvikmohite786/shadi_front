<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ProfileView;
use App\Repositories\ProfileViewRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileViewService
{
    public function __construct(protected ProfileViewRepository $profileViewRepository) {}

    public function recordView(int $viewerId, int $viewedId): ?ProfileView
    {
        if ($viewerId === $viewedId) return null;
        if ($this->profileViewRepository->hasViewedRecently($viewerId, $viewedId)) return null;

        $view = $this->profileViewRepository->create(['viewer_id' => $viewerId, 'viewed_id' => $viewedId]);
        $this->createNotification($viewerId, $viewedId);
        return $view;
    }

    public function getProfileViewers(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->profileViewRepository->getViewers($userId, $perPage);
    }

    public function getRecentViewers(int $userId, int $limit = 10): Collection
    {
        return $this->profileViewRepository->getRecentViewers($userId, $limit);
    }

    public function getViewedProfiles(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->profileViewRepository->getViewedProfiles($userId, $perPage);
    }

    public function getTotalViewsCount(int $userId): int
    {
        return $this->profileViewRepository->countViews($userId);
    }

    public function getTodayViewsCount(int $userId): int
    {
        return $this->profileViewRepository->countTodayViews($userId);
    }

    protected function createNotification(int $viewerId, int $viewedId): void
    {
        Notification::create([
            'user_id' => $viewedId,
            'type' => 'profile_viewed',
            'title' => 'Someone viewed your profile',
            'message' => 'A member has viewed your profile. Check who visited you.',
            'data' => ['viewer_id' => $viewerId],
        ]);
    }
}
















