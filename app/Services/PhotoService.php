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

        if ($user->profile_photo) {
            $oldPath = public_path("images/profile/{$user->profile_photo}");
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $file->move(public_path('images/profile'), $filename);
        return $this->userRepository->update($user, ['profile_photo' => $filename]);
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
        return $approvedOnly 
            ? $this->photoRepository->findApprovedByUser($userId)
            : $this->photoRepository->findByUser($userId);
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



