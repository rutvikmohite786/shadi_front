<?php

namespace App\Http\Controllers;

use App\Services\InterestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterestController extends Controller
{
    public function __construct(protected InterestService $interestService) {}

    public function sent(): View
    {
        $interests = $this->interestService->getSentInterests(auth()->id());
        return view('interests.sent', compact('interests'));
    }

    public function received(): View
    {
        $interests = $this->interestService->getReceivedInterests(auth()->id());
        return view('interests.received', compact('interests'));
    }

    public function accepted(): View
    {
        $interests = $this->interestService->getAcceptedInterests(auth()->id());
        return view('interests.accepted', compact('interests'));
    }

    public function send(Request $request, int $userId): RedirectResponse|JsonResponse
    {
        $request->validate(['message' => ['nullable', 'string', 'max:500']]);
        $result = $this->interestService->sendInterest(auth()->user(), $userId, $request->get('message'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        return $result['success'] 
            ? back()->with('success', $result['message'])
            : back()->withErrors(['interest' => $result['message']]);
    }

    public function accept(int $interestId): RedirectResponse|JsonResponse
    {
        $result = $this->interestService->acceptInterest(auth()->user(), $interestId);

        if (request()->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        return $result['success'] 
            ? back()->with('success', $result['message'])
            : back()->withErrors(['interest' => $result['message']]);
    }

    public function reject(int $interestId): RedirectResponse|JsonResponse
    {
        $result = $this->interestService->rejectInterest(auth()->user(), $interestId);

        if (request()->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        return $result['success'] 
            ? back()->with('success', $result['message'])
            : back()->withErrors(['interest' => $result['message']]);
    }

    public function cancel(int $interestId): RedirectResponse|JsonResponse
    {
        $result = $this->interestService->cancelInterest(auth()->user(), $interestId);

        if (request()->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        return $result['success'] 
            ? back()->with('success', $result['message'])
            : back()->withErrors(['interest' => $result['message']]);
    }
}






