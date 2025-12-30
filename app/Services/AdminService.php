<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Report;
use App\Models\SuccessStory;
use App\Models\User;
use App\Repositories\UserPhotoRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserSubscriptionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class AdminService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected UserPhotoRepository $photoRepository,
        protected UserSubscriptionRepository $subscriptionRepository
    ) {}

    public function getDashboardStats(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'male_users' => User::where('gender', 'male')->count(),
            'female_users' => User::where('gender', 'female')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_month' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'active_subscriptions' => $this->subscriptionRepository->getActiveSubscriptionsCount(),
            'monthly_revenue' => $this->subscriptionRepository->getMonthlyRevenue(),
            'pending_photos' => $this->photoRepository->getPendingApproval()->count(),
            'pending_reports' => Report::pending()->count(),
        ];
    }

    public function getAllUsers(int $perPage = 20): LengthAwarePaginator
    {
        return User::with('profile')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function searchUsers(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->with('profile')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function toggleUserStatus(User $user): bool
    {
        return $user->update(['is_active' => !$user->is_active]);
    }

    public function verifyUser(User $user): bool
    {
        return $user->update(['is_verified' => true]);
    }

    public function getPendingPhotos(): Collection
    {
        return $this->photoRepository->getPendingApproval();
    }

    public function approvePhoto(int $photoId): bool
    {
        return $this->photoRepository->approve($this->photoRepository->find($photoId));
    }

    public function rejectPhoto(int $photoId): bool
    {
        $photo = $this->photoRepository->find($photoId);
        $path = public_path("images/gallery/{$photo->photo_path}");
        if (file_exists($path)) unlink($path);
        return $this->photoRepository->reject($photo);
    }

    public function getAllBanners(): Collection
    {
        return Banner::orderBy('sort_order')->get();
    }

    public function getActiveBanners(string $position = 'home'): Collection
    {
        return Banner::active()->position($position)->orderBy('sort_order')->get();
    }

    public function createBanner(array $data, $image): Banner
    {
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/banners'), $filename);
        $data['image_path'] = $filename;
        return Banner::create($data);
    }

    public function updateBanner(Banner $banner, array $data, $image = null): bool
    {
        if ($image) {
            $oldPath = public_path("images/banners/{$banner->image_path}");
            if (file_exists($oldPath)) unlink($oldPath);
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/banners'), $filename);
            $data['image_path'] = $filename;
        }
        return $banner->update($data);
    }

    public function deleteBanner(Banner $banner): bool
    {
        $path = public_path("images/banners/{$banner->image_path}");
        if (file_exists($path)) unlink($path);
        return $banner->delete();
    }

    public function getAllSuccessStories(): Collection
    {
        return SuccessStory::with(['bride', 'groom'])->orderBy('created_at', 'desc')->get();
    }

    public function getPendingSuccessStories(): Collection
    {
        return SuccessStory::where('is_approved', false)->with(['bride', 'groom'])->orderBy('created_at', 'desc')->get();
    }

    public function approveSuccessStory(SuccessStory $story): bool
    {
        return $story->update(['is_approved' => true]);
    }

    public function featureSuccessStory(SuccessStory $story): bool
    {
        return $story->update(['is_featured' => !$story->is_featured]);
    }

    public function getPendingReports(): Collection
    {
        return Report::pending()->with(['reporter', 'reportedUser'])->orderBy('created_at', 'desc')->get();
    }

    public function getAllReports(int $perPage = 20): LengthAwarePaginator
    {
        return Report::with(['reporter', 'reportedUser', 'reviewer'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function resolveReport(Report $report, string $status, string $notes, int $adminId): bool
    {
        return $report->update([
            'status' => $status,
            'admin_notes' => $notes,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);
    }
}
















