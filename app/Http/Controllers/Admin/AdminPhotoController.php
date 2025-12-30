<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminPhotoController extends Controller
{
    public function __construct(protected AdminService $adminService) {}

    public function pending(): View
    {
        $photos = $this->adminService->getPendingPhotos();
        return view('admin.photos.pending', compact('photos'));
    }

    public function approve(int $photoId): RedirectResponse
    {
        $this->adminService->approvePhoto($photoId);
        return back()->with('success', 'Photo approved successfully.');
    }

    public function reject(int $photoId): RedirectResponse
    {
        $this->adminService->rejectPhoto($photoId);
        return back()->with('success', 'Photo rejected and deleted.');
    }
}















