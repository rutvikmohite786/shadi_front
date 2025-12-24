<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserMatch;
use App\Repositories\IgnoreRepository;
use App\Repositories\MatchRepository;
use App\Repositories\ShortlistRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MatchService
{
    public function __construct(
        protected MatchRepository $matchRepository,
        protected ShortlistRepository $shortlistRepository,
        protected IgnoreRepository $ignoreRepository
    ) {}

    public function generateDailyMatches(User $user): Collection
    {
        $ignoredIds = $this->ignoreRepository->getIgnoredUserIds($user->id);
        $ignoredIds[] = $user->id;

        $oppositeGender = $user->gender === 'male' ? 'female' : 'male';
        
        $potentialMatches = User::where('gender', $oppositeGender)
            ->where('is_active', true)->where('profile_completed', true)
            ->whereNotIn('id', $ignoredIds)->with('profile')->limit(50)->get();

        foreach ($potentialMatches as $match) {
            $score = $this->calculateMatchScore($user, $match);
            
            // Use updateOrCreate to handle existing matches gracefully
            $this->matchRepository->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'matched_user_id' => $match->id,
                ],
                [
                    'match_score' => $score,
                    'matched_date' => today(),
                ]
            );
        }

        return $this->matchRepository->getDailyMatches($user->id);
    }

    public function getDailyMatches(User $user, int $limit = 10): Collection
    {
        $matches = $this->matchRepository->getDailyMatches($user->id, $limit);
        if ($matches->isEmpty()) {
            $matches = $this->generateDailyMatches($user);
        }
        return $matches->take($limit);
    }

    public function getAllMatches(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->matchRepository->getAllMatches($userId, $perPage);
    }

    public function getMutualMatches(int $userId): Collection
    {
        return $this->matchRepository->getMutualMatches($userId);
    }

    protected function calculateMatchScore(User $user, User $match): float
    {
        $score = 0;
        $userProfile = $user->profile;
        $matchProfile = $match->profile;

        if (!$userProfile || !$matchProfile) return 50;

        if ($userProfile->religion_id === $matchProfile->religion_id) $score += 20;
        if ($userProfile->caste_id === $matchProfile->caste_id) $score += 15;
        if ($userProfile->city_id === $matchProfile->city_id) $score += 15;
        elseif ($userProfile->state_id === $matchProfile->state_id) $score += 10;
        elseif ($userProfile->country_id === $matchProfile->country_id) $score += 5;
        if ($userProfile->mother_tongue_id === $matchProfile->mother_tongue_id) $score += 10;
        if ($userProfile->education_id === $matchProfile->education_id) $score += 10;

        $matchAge = $match->getAge();
        if ($matchAge && $userProfile->partner_age_min && $userProfile->partner_age_max) {
            if ($matchAge >= $userProfile->partner_age_min && $matchAge <= $userProfile->partner_age_max) {
                $score += 15;
            }
        }

        if ($matchProfile->height && $userProfile->partner_height_min && $userProfile->partner_height_max) {
            if ($matchProfile->height >= $userProfile->partner_height_min && 
                $matchProfile->height <= $userProfile->partner_height_max) {
                $score += 10;
            }
        }

        return min($score, 100);
    }

    public function shortlist(int $userId, int $shortlistedUserId): bool
    {
        if ($this->shortlistRepository->isShortlisted($userId, $shortlistedUserId)) return false;
        $this->shortlistRepository->create(['user_id' => $userId, 'shortlisted_user_id' => $shortlistedUserId]);
        return true;
    }

    public function removeFromShortlist(int $userId, int $shortlistedUserId): bool
    {
        $shortlist = $this->shortlistRepository->findByUsers($userId, $shortlistedUserId);
        if (!$shortlist) return false;
        return $this->shortlistRepository->delete($shortlist);
    }

    public function getShortlist(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->shortlistRepository->getByUser($userId, $perPage);
    }

    public function getShortlistCount(int $userId): int
    {
        return $this->shortlistRepository->countByUser($userId);
    }

    public function isShortlisted(int $userId, int $shortlistedUserId): bool
    {
        return $this->shortlistRepository->isShortlisted($userId, $shortlistedUserId);
    }

    public function ignoreProfile(int $userId, int $ignoredUserId, ?string $reason = null): bool
    {
        if ($this->ignoreRepository->isIgnored($userId, $ignoredUserId)) return false;
        $this->ignoreRepository->create(['user_id' => $userId, 'ignored_user_id' => $ignoredUserId, 'reason' => $reason]);
        $this->removeFromShortlist($userId, $ignoredUserId);
        return true;
    }

    public function unignoreProfile(int $userId, int $ignoredUserId): bool
    {
        $ignore = $this->ignoreRepository->findByUsers($userId, $ignoredUserId);
        if (!$ignore) return false;
        return $this->ignoreRepository->delete($ignore);
    }

    public function getIgnoredProfiles(int $userId)
    {
        return $this->ignoreRepository->getByUser($userId);
    }

    public function isIgnored(int $userId, int $ignoredUserId): bool
    {
        return $this->ignoreRepository->isIgnored($userId, $ignoredUserId);
    }
}







