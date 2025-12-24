<?php

namespace App\Services;

use App\Models\ContactView;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\ContactViewRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactViewService
{
    public function __construct(
        protected ContactViewRepository $contactViewRepository,
        protected SubscriptionService $subscriptionService
    ) {}

    public function viewContact(User $viewer, int $viewedUserId): array
    {
        if ($this->contactViewRepository->hasViewed($viewer->id, $viewedUserId)) {
            return ['success' => true, 'message' => 'Contact already viewed'];
        }

        $canView = $this->subscriptionService->canViewContact($viewer);
        if (!$canView['allowed']) {
            return ['success' => false, 'message' => $canView['message']];
        }

        $this->contactViewRepository->create(['viewer_id' => $viewer->id, 'viewed_id' => $viewedUserId]);
        $this->subscriptionService->incrementContactView($viewer);
        $this->createNotification($viewer->id, $viewedUserId);

        return ['success' => true, 'message' => 'Contact viewed successfully'];
    }

    public function hasViewedContact(int $viewerId, int $viewedId): bool
    {
        return $this->contactViewRepository->hasViewed($viewerId, $viewedId);
    }

    public function getContactViewers(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->contactViewRepository->getViewers($userId, $perPage);
    }

    public function getViewedContacts(int $userId)
    {
        return $this->contactViewRepository->getViewedContacts($userId);
    }

    public function getViewedCount(int $userId): int
    {
        return $this->contactViewRepository->countViewedByUser($userId);
    }

    protected function createNotification(int $viewerId, int $viewedId): void
    {
        Notification::create([
            'user_id' => $viewedId,
            'type' => 'contact_viewed',
            'title' => 'Someone viewed your contact',
            'message' => 'A member has viewed your contact details.',
            'data' => ['viewer_id' => $viewerId],
        ]);
    }
}











