<?php

namespace App\Http\Controllers;

use App\Services\MatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function __construct(protected MatchService $matchService) {}

    public function dailyMatches(): View
    {
        $matches = $this->matchService->getDailyMatches(auth()->user(), 20);
        return view('matches.daily', compact('matches'));
    }

    public function allMatches(): View
    {
        $matches = $this->matchService->getAllMatches(auth()->id());
        return view('matches.all', compact('matches'));
    }

    public function mutualMatches(): View
    {
        $matches = $this->matchService->getMutualMatches(auth()->id());
        return view('matches.mutual', compact('matches'));
    }

    public function shortlist(): View
    {
        $shortlisted = $this->matchService->getShortlist(auth()->id());
        return view('matches.shortlist', compact('shortlisted'));
    }

    public function addToShortlist(int $userId): RedirectResponse
    {
        $this->matchService->shortlist(auth()->id(), $userId);
        return back()->with('success', 'Profile added to shortlist.');
    }

    public function removeFromShortlist(int $userId): RedirectResponse
    {
        $this->matchService->removeFromShortlist(auth()->id(), $userId);
        return back()->with('success', 'Profile removed from shortlist.');
    }

    public function ignoredProfiles(): View
    {
        $ignored = $this->matchService->getIgnoredProfiles(auth()->id());
        return view('matches.ignored', compact('ignored'));
    }

    public function ignoreProfile(Request $request, int $userId): RedirectResponse
    {
        $this->matchService->ignoreProfile(auth()->id(), $userId, $request->get('reason'));
        return back()->with('success', 'Profile ignored.');
    }

    public function unignoreProfile(int $userId): RedirectResponse
    {
        $this->matchService->unignoreProfile(auth()->id(), $userId);
        return back()->with('success', 'Profile unignored.');
    }
}



