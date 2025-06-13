<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request, $receiverId): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $sender = $request->user();
        $receiver = User::findOrFail($receiverId);

        // csak akkor, ha mindketten jelöltek(barátok)
        if (
            !$sender->friends()->where('friend_id', $receiverId)->exists() ||
            !$receiver->friends()->where('friend_id', $sender->id)->exists()
        ) {
            return response()->json(['message' => 'Nem vagytok ismerősök.'], 403);
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'content' => $request->input('content'),
        ]);

        return response()->json($message, 201);
    }

    public function conversation(Request $request, $userId): JsonResponse
    {
        $auth = $request->user();

        $messages = Message::where(function ($q) use ($auth, $userId) {
            $q->where('sender_id', $auth->id)->where('receiver_id', $userId);
        })
            ->orWhere(function ($q) use ($auth, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $auth->id);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
}
