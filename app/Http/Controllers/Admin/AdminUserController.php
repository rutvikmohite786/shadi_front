<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct(protected AdminService $adminService) {}

    public function index(Request $request): View
    {
        $query = $request->get('q');
        $users = $query ? $this->adminService->searchUsers($query) : $this->adminService->getAllUsers();
        return view('admin.users.index', compact('users', 'query'));
    }

    public function show(User $user): View
    {
        $user->load(['profile', 'photos', 'subscriptions.plan']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $this->adminService->toggleUserStatus($user);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    public function verify(User $user): RedirectResponse
    {
        $this->adminService->verifyUser($user);
        return back()->with('success', 'User verified successfully.');
    }
}















