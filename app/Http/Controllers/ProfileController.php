<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdatePartnerPreferencesRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UploadPhotoRequest;
use App\Services\AuthService;
use App\Services\PhotoService;
use App\Services\ProfileViewService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected PhotoService $photoService,
        protected AuthService $authService
    ) {}

    public function show(int $id): View
    {
        $profile = $this->userService->getProfile($id);
        if (!$profile) abort(404);

        if (auth()->id() !== $id) {
            app(ProfileViewService::class)->recordView(auth()->id(), $id);
        }

        // Get photos for display
        // If viewing own profile, show all photos (including pending)
        // If viewing other's profile, show only approved photos
        $approvedOnly = auth()->id() !== $id;
        $photos = $this->photoService->getUserPhotos($id, $approvedOnly);

        return view('profile.show', compact('profile', 'photos'));
    }

    public function edit(): View
    {
        $user = $this->userService->getProfile(auth()->id());
        if (!$user) {
            $user = auth()->user();
            $user->load('profile');
        }
        $profile = $user->profile;
        $photos = $this->photoService->getUserPhotos($user->id, false);
        return view('profile.edit', compact('user', 'profile', 'photos'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->userService->updateProfile(auth()->user(), $request->validated());
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateBasicInfo(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $user->id],
        ]);
        $this->userService->updateBasicInfo($user, $validated);
        return back()->with('success', 'Basic info updated successfully.');
    }

    public function updatePartnerPreferences(UpdatePartnerPreferencesRequest $request): RedirectResponse
    {
        $this->userService->updateProfile(auth()->user(), $request->validated());
        return back()->with('success', 'Partner preferences updated successfully.');
    }

    public function uploadPhoto(UploadPhotoRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();
            $type = $request->get('type', 'gallery');
            if ($type === 'profile') {
                $this->photoService->uploadProfilePhoto($user, $request->file('photo'));
            } else {
                $this->photoService->uploadPhoto($user, $request->file('photo'), $type);
            }
            return back()->with('success', 'Photo uploaded successfully. Pending approval.');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => $e->getMessage()]);
        }
    }

    public function deletePhoto(int $photoId): RedirectResponse
    {
        try {
            $this->photoService->deletePhoto(auth()->user(), $photoId);
            return back()->with('success', 'Photo deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => $e->getMessage()]);
        }
    }

    public function setPrimaryPhoto(int $photoId): RedirectResponse
    {
        try {
            $this->photoService->setPrimaryPhoto(auth()->user(), $photoId);
            return back()->with('success', 'Primary photo updated.');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => $e->getMessage()]);
        }
    }

    public function updatePrivacy(Request $request): RedirectResponse
    {
        $validated = $request->validate(['photo_privacy' => ['required', 'in:public,private']]);
        $this->userService->updatePhotoPrivacy(auth()->user(), $validated['photo_privacy']);
        return back()->with('success', 'Privacy settings updated.');
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        try {
            $this->authService->changePassword(auth()->user(), $request->current_password, $request->password);
            return back()->with('success', 'Password changed successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }

    public function deactivate(): RedirectResponse
    {
        $this->userService->deactivateAccount(auth()->user());
        $this->authService->logout();
        return redirect()->route('home')->with('success', 'Your account has been deactivated.');
    }
}




