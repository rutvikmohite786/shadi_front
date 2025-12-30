<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(protected AdminService $adminService) {}

    public function index(): View
    {
        $stats = $this->adminService->getDashboardStats();
        $pendingPhotos = $this->adminService->getPendingPhotos()->take(5);
        $pendingReports = $this->adminService->getPendingReports()->take(5);
        return view('admin.dashboard', compact('stats', 'pendingPhotos', 'pendingReports'));
    }
}















