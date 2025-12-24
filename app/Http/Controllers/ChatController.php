<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService,
        protected UserService $userService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $conversations = $this->chatService->getConversationList($user->id);
        $unreadCount = $this->chatService->getUnreadCount($user->id);
        $chatToken = $this->chatService->getChatToken($user);
        return view('chat.index', compact('conversations', 'unreadCount', 'chatToken'));
    }

    public function conversation(int $userId): View
    {
        $user = auth()->user();
        $otherUser = $this->userService->getProfile($userId);
        if (!$otherUser) abort(404);

        $canChat = $this->chatService->canChat($user, $userId);
        $messages = $canChat['allowed'] ? $this->chatService->getConversation($user->id, $userId) : collect();
        $chatToken = $this->chatService->getChatToken($user);

        return view('chat.conversation', compact('otherUser', 'messages', 'canChat', 'chatToken'));
    }

    public function getMessages(int $userId, Request $request): JsonResponse
    {
        $user = auth()->user();
        $canChat = $this->chatService->canChat($user, $userId);
        if (!$canChat['allowed']) {
            return response()->json(['success' => false, 'message' => $canChat['message']], 403);
        }

        $messages = $this->chatService->getConversation($user->id, $userId, 
            $request->get('limit', 50), $request->get('offset', 0));

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function sendMessage(Request $request, int $userId): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $request->validate(['message' => ['required', 'string', 'max:1000']]);
        $result = $this->chatService->sendMessage(auth()->user(), $userId, $request->message);
        
        // Return JSON for AJAX requests, redirect for regular form submissions
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 403);
        }
        
        return $result['success']
            ? back()->with('success', 'Message sent.')
            : back()->withErrors(['message' => $result['message']]);
    }

    public function markAsRead(int $userId): JsonResponse
    {
        $this->chatService->markAsRead(auth()->id(), $userId);
        return response()->json(['success' => true]);
    }

    public function getUnreadCount(): JsonResponse
    {
        return response()->json(['count' => $this->chatService->getUnreadCount(auth()->id())]);
    }

    public function deleteMessage(int $messageId): JsonResponse
    {
        $result = $this->chatService->deleteMessage(auth()->user(), $messageId);
        return response()->json([
            'success' => $result,
            'message' => $result ? 'Message deleted.' : 'Unable to delete message.',
        ]);
    }

    public function deleteConversation(int $userId): JsonResponse
    {
        $this->chatService->deleteConversation(auth()->user(), $userId);
        return response()->json(['success' => true, 'message' => 'Conversation deleted.']);
    }

    public function getChatToken(): JsonResponse
    {
        return response()->json(['token' => $this->chatService->getChatToken(auth()->user())]);
    }
}




