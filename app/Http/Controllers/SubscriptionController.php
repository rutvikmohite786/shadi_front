<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptionService) {}

    public function plans(): View
    {
        $plans = $this->subscriptionService->getAvailablePlans();
        $currentSubscription = $this->subscriptionService->getCurrentSubscription(auth()->user());
        return view('subscription.plans', compact('plans', 'currentSubscription'));
    }

    public function mySubscription(): View
    {
        $user = auth()->user();
        $currentSubscription = $this->subscriptionService->getCurrentSubscription($user);
        $subscriptionHistory = $this->subscriptionService->getSubscriptionHistory($user);
        $usage = $this->subscriptionService->getUsageSummary($user);
        return view('subscription.my-subscription', compact('currentSubscription', 'subscriptionHistory', 'usage'));
    }

    public function subscribe(int $planId): RedirectResponse
    {
        try {
            $plan = $this->subscriptionService->getPlan($planId);
            if (!$plan) {
                return back()->withErrors(['plan' => 'Invalid plan selected.']);
            }

            $this->subscriptionService->subscribe(auth()->user(), $planId, 'DEMO_' . uniqid());
            return redirect()->route('subscription.my')->with('success', "Successfully subscribed to {$plan->name} plan!");
        } catch (\Exception $e) {
            return back()->withErrors(['plan' => $e->getMessage()]);
        }
    }

    public function cancel(): RedirectResponse
    {
        $this->subscriptionService->cancelSubscription(auth()->user());
        return redirect()->route('subscription.my')->with('success', 'Subscription cancelled successfully.');
    }
}















