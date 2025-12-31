<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPhoto;
use App\Repositories\UserPhotoRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PhotoService
{
    protected int $maxPhotos = 10;

    public function __construct(
        protected UserPhotoRepository $photoRepository,
        protected UserRepository $userRepository
    ) {}

    public function uploadPhoto(User $user, UploadedFile $file, string $type = 'gallery'): UserPhoto
    {
        $currentCount = $this->photoRepository->countByUser($user->id);
        if ($currentCount >= $this->maxPhotos) {
            throw new \Exception("Maximum {$this->maxPhotos} photos allowed.");
        }

        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        $storagePath = $type === 'profile' ? 'profile' : 'gallery';
        $file->move(public_path("images/{$storagePath}"), $filename);

        return $this->photoRepository->create([
            'user_id' => $user->id,
            'photo_path' => $filename,
            'photo_type' => $type,
            'is_approved' => false,
            'is_primary' => $currentCount === 0,
            'sort_order' => $currentCount + 1,
        ]);
    }

    public function uploadProfilePhoto(User $user, UploadedFile $file): bool
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;

        // Remove existing profile photos (file + record)
        if ($user->profile_photo) {
            $oldPath = public_path("images/profile/{$user->profile_photo}");
            if (file_exists($oldPath)) unlink($oldPath);
        }
        $existingProfilePhotos = $this->photoRepository->findProfileByUser($user->id);
        foreach ($existingProfilePhotos as $photo) {
            $oldPath = public_path("images/profile/{$photo->photo_path}");
            if (file_exists($oldPath)) unlink($oldPath);
            $this->photoRepository->delete($photo);
        }

        $file->move(public_path('images/profile'), $filename);
        $this->userRepository->update($user, ['profile_photo' => $filename]);

        // Also store in user_photos so it shows under "Your Photos"
        $photo = $this->photoRepository->create([
            'user_id' => $user->id,
            'photo_path' => $filename,
            'photo_type' => 'profile',
            'is_approved' => true,
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        $this->photoRepository->setPrimary($photo);

        return true;
    }

    public function deletePhoto(User $user, int $photoId): bool
    {
        $photo = $this->photoRepository->find($photoId);
        if (!$photo || $photo->user_id !== $user->id) {
            throw new \Exception('Photo not found.');
        }

        $storagePath = $photo->photo_type === 'profile' ? 'profile' : 'gallery';
        $filePath = public_path("images/{$storagePath}/{$photo->photo_path}");
        if (file_exists($filePath)) unlink($filePath);

        return $this->photoRepository->delete($photo);
    }

    public function setPrimaryPhoto(User $user, int $photoId): bool
    {
        $photo = $this->photoRepository->find($photoId);
        if (!$photo || $photo->user_id !== $user->id) {
            throw new \Exception('Photo not found.');
        }

        $this->photoRepository->setPrimary($photo);
        $this->userRepository->update($user, ['profile_photo' => $photo->photo_path]);
        return true;
    }

    public function getUserPhotos(int $userId, bool $approvedOnly = true)
    {
        $photos = $approvedOnly 
            ? $this->photoRepository->findApprovedByUser($userId)
            : $this->photoRepository->findByUser($userId);

        // Fallback: if a profile photo exists on the user record but no profile-type entry is present,
        // persist it into user_photos so "Your Photos" shows the uploaded profile photo even if it predates the photo records.
        if ($approvedOnly === false) {
            $user = $this->userRepository->find($userId);
            if ($user && $user->profile_photo) {
                $hasProfilePhoto = $photos->contains(function ($photo) {
                    return $photo->photo_type === 'profile';
                });
                if (!$hasProfilePhoto) {
                    $count = $photos->count();
                    $record = $this->photoRepository->create([
                        'user_id' => $userId,
                        'photo_path' => $user->profile_photo,
                        'photo_type' => 'profile',
                        'is_approved' => true,
                        'is_primary' => true,
                        'sort_order' => $count + 1,
                    ]);
                    $this->photoRepository->setPrimary($record);
                    $photos->prepend($record);
                }
            }
        }

        return $photos;
    }

    public function getPendingPhotos()
    {
        return $this->photoRepository->getPendingApproval();
    }

    public function approvePhoto(int $photoId): bool
    {
        $photo = $this->photoRepository->find($photoId);
        if (!$photo) throw new \Exception('Photo not found.');
        return $this->photoRepository->approve($photo);
    }

    public function rejectPhoto(int $photoId): bool
    {
        $photo = $this->photoRepository->find($photoId);
        if (!$photo) throw new \Exception('Photo not found.');

        $storagePath = $photo->photo_type === 'profile' ? 'profile' : 'gallery';
        $filePath = public_path("images/{$storagePath}/{$photo->photo_path}");
        if (file_exists($filePath)) unlink($filePath);

        return $this->photoRepository->reject($photo);
    }
}

















