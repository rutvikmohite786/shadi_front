<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function __construct(protected SubscriptionService $subscriptionService) {}

    public function handle(Request $request, Closure $next, string $feature = 'any'): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $allowed = match ($feature) {
            'contact' => $this->subscriptionService->canViewContact($user),
            'interest' => $this->subscriptionService->canSendInterest($user),
            'chat' => $this->subscriptionService->canChat($user),
            default => ['allowed' => true, 'message' => ''],
        };

        if (!$allowed['allowed']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $allowed['message'],
                    'upgrade_required' => true,
                ], 403);
            }

            return redirect()->route('subscription.plans')->withErrors(['subscription' => $allowed['message']]);
        }

        return $next($request);
    }
}















