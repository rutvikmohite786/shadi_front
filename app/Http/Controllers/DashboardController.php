<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Services\InterestService;
use App\Services\MatchService;
use App\Services\ProfileViewService;
use App\Services\UserService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected MatchService $matchService,
        protected InterestService $interestService,
        protected ProfileViewService $profileViewService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $user->load('profile');

        $dailyMatches = $this->matchService->getDailyMatches($user, 6);
        $newProfiles = $this->userService->getNewProfiles(6);
        $recentProfileViews = $this->profileViewService->getRecentViewers($user->id, 5);
        $receivedInterests = $this->interestService->getReceivedInterests($user->id, 5);
        $sentInterests = $this->interestService->getSentInterests($user->id, 5);

        // Get nearby users (same state/city or religion)
        $nearbyUsers = $this->getNearbyUsers($user, 8);
        
        // Get premium profiles
        $premiumProfiles = $this->getPremiumProfiles($user, 4);

        // Get subscription plans
        $plans = Plan::where('is_active', true)->orderBy('price')->take(3)->get();

        $stats = [
            'profile_views' => $this->profileViewService->getTotalViewsCount($user->id),
            'received_interests' => $this->interestService->getReceivedInterestsCount($user->id),
            'accepted_interests' => $this->interestService->getAcceptedInterestsCount($user->id),
            'shortlisted' => $this->matchService->getShortlistCount($user->id),
        ];

        return view('dashboard', compact(
            'user', 'dailyMatches', 'newProfiles', 'recentProfileViews', 
            'receivedInterests', 'sentInterests', 'stats', 'nearbyUsers', 
            'premiumProfiles', 'plans'
        ));
    }

    /**
     * Get nearby users based on location, religion, or preferences
     */
    protected function getNearbyUsers(User $user, int $limit = 8)
    {
        try {
            $oppositeGender = $user->gender === 'male' ? 'female' : 'male';
            
            // If user has profile with location/religion, use smart matching
            if ($user->profile && ($user->profile->city_id || $user->profile->state_id || $user->profile->religion_id)) {
                return User::where('users.id', '!=', $user->id)
                    ->where('users.gender', $oppositeGender)
                    ->where('users.is_active', true)
                    ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                    ->select('users.*')
                    ->selectRaw('
                        CASE 
                            WHEN user_profiles.city_id = ? THEN 4
                            WHEN user_profiles.state_id = ? THEN 3
                            WHEN user_profiles.religion_id = ? THEN 2
                            WHEN user_profiles.mother_tongue_id = ? THEN 1
                            ELSE 0
                        END as match_score
                    ', [
                        $user->profile->city_id ?? 0,
                        $user->profile->state_id ?? 0,
                        $user->profile->religion_id ?? 0,
                        $user->profile->mother_tongue_id ?? 0
                    ])
                    ->with(['profile.city', 'profile.education', 'profile.religion'])
                    ->orderByDesc('match_score')
                    ->orderByDesc('users.created_at')
                    ->take($limit)
                    ->get();
            }
            
            // Fallback: just get opposite gender profiles
            return User::where('users.id', '!=', $user->id)
                ->where('users.gender', $oppositeGender)
                ->where('users.is_active', true)
                ->with(['profile.city', 'profile.education', 'profile.religion'])
                ->orderByDesc('users.created_at')
                ->take($limit)
                ->get();
                
        } catch (\Exception $e) {
            // If any error, return empty collection
            return collect([]);
        }
    }

    /**
     * Get premium/featured profiles
     */
    protected function getPremiumProfiles(User $user, int $limit = 4)
    {
        $oppositeGender = $user->gender === 'male' ? 'female' : 'male';
        
        try {
            return User::where('users.id', '!=', $user->id)
                ->where('users.gender', $oppositeGender)
                ->where('users.is_active', true)
                ->where('users.is_verified', true)
                ->whereHas('subscription', function($q) {
                    $q->where('status', 'active')
                      ->where('end_date', '>=', now());
                })
                ->with(['profile.city', 'profile.education'])
                ->inRandomOrder()
                ->take($limit)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}


