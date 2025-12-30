<?php

namespace App\Http\Controllers;

use App\Services\ContactViewService;
use App\Services\ProfileViewService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProfileViewController extends Controller
{
    public function __construct(
        protected ProfileViewService $profileViewService,
        protected ContactViewService $contactViewService,
        protected UserService $userService
    ) {}

    public function whoViewedMe(): View
    {
        $viewers = $this->profileViewService->getProfileViewers(auth()->id());
        return view('matches.profile-views', compact('viewers'));
    }

    public function viewedByMe(): View
    {
        $viewed = $this->profileViewService->getViewedProfiles(auth()->id());
        return view('matches.viewed-profiles', compact('viewed'));
    }

    public function contactViewers(): View
    {
        $viewers = $this->contactViewService->getContactViewers(auth()->id());
        return view('matches.contact-views', compact('viewers'));
    }

    public function viewContact(int $userId): JsonResponse
    {
        $result = $this->contactViewService->viewContact(auth()->user(), $userId);
        if (!$result['success']) {
            return response()->json($result, 403);
        }

        $profile = $this->userService->getProfile($userId);
        return response()->json([
            'success' => true,
            'contact' => ['phone' => $profile->phone, 'email' => $profile->email],
        ]);
    }
}
















