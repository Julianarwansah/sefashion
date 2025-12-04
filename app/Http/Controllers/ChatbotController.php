<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Handle chat message
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->input('message');
        $customerId = Auth::guard('customer')->id();

        // Get AI response
        $result = $this->chatService->chat($message, $customerId);

        // Save chat history
        if ($result['success']) {
            ChatMessage::create([
                'customer_id' => $customerId,
                'message' => $message,
                'response' => $result['response'],
                'context' => json_encode([
                    'timestamp' => now(),
                    'user_agent' => $request->userAgent(),
                ]),
            ]);
        }

        return response()->json($result);
    }

    /**
     * Get chat history
     */
    public function history(Request $request)
    {
        $customerId = Auth::guard('customer')->id();

        $query = ChatMessage::query();

        if ($customerId) {
            $query->where('customer_id', $customerId);
        } else {
            // For guests, use session
            $query->where('session_id', session()->getId());
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Clear chat history
     */
    public function clearHistory(Request $request)
    {
        $customerId = Auth::guard('customer')->id();

        $query = ChatMessage::query();

        if ($customerId) {
            $query->where('customer_id', $customerId);
        } else {
            $query->where('session_id', session()->getId());
        }

        $query->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat history cleared',
        ]);
    }
}
