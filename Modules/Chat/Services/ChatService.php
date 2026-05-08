<?php

namespace Modules\Chat\Services;

use Modules\Admin\Models\ChatSession;
use Modules\Admin\Models\ChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    /**
     * =========================
     * CREATE OR GET SESSION (FIXED IDENTITY BUG)
     * =========================
     */
    public function getOrCreateSession(string $token, array $guestData = []): ChatSession
    {
        return ChatSession::firstOrCreate(
            ['session_token' => $token],
            array_merge([
                'status' => 'open',
                'last_message_at' => now(),

                /**
                 * 🔥 FIX CORE BUG:
                 * Nếu user login → gắn luôn user_id
                 */
                'user_id' => Auth::id(),
            ], $guestData)
        );
    }

    /**
     * =========================
     * SEND MESSAGE (CORE FLOW)
     * =========================
     */
    public function sendMessage(array $data): ChatMessage
    {
        return DB::transaction(function () use ($data) {

            $session = ChatSession::findOrFail($data['session_id']);

            /**
             * 🔥 FIX SAFETY:
             * đảm bảo session có user_id nếu user đang login
             */
            if (!$session->user_id && Auth::check()) {
                $session->update([
                    'user_id' => Auth::id()
                ]);
            }

            // 1. Save message
            $message = ChatMessage::create([
                'chat_session_id' => $session->id,
                'sender_id'       => $data['sender_id'] ?? Auth::id(),
                'sender_type'     => $data['sender_type'],
                'message'         => $data['message'],
                'metadata'        => $data['metadata'] ?? null,
            ]);

            // 2. Update session meta
            $session->update([
                'last_message_at' => now(),
                'status' => 'open'
            ]);

            // 3. Broadcast to NodeJS
            $this->broadcastToNodeJS([
                'event' => 'MessageSent',
                'data'  => [
                    'session_id'  => (int) $session->id,
                    'message'     => $message->message,
                    'sender_type' => $message->sender_type,
                    'sender_id'   => $message->sender_id,
                    'created_at'  => $message->created_at->format('H:i'),
                ]
            ]);

            return $message;
        });
    }

    /**
     * =========================
     * DELETE MESSAGE
     * =========================
     */
    public function deleteMessage($messageId): bool
    {
        return DB::transaction(function () use ($messageId) {

            $message = ChatMessage::find($messageId);
            if (!$message) return false;

            $sessionId = $message->chat_session_id;

            $message->delete();

            $this->broadcastToNodeJS([
                'event' => 'MessageDeleted',
                'data'  => [
                    'message_id' => $messageId,
                    'session_id' => (int) $sessionId,
                ]
            ]);

            return true;
        });
    }

    /**
     * =========================
     * DELETE ALL MESSAGES
     * =========================
     */
    public function deleteAllMessages($sessionId): bool
    {
        return DB::transaction(function () use ($sessionId) {

            ChatMessage::where('chat_session_id', $sessionId)->delete();

            $this->broadcastToNodeJS([
                'event' => 'AllMessagesDeleted',
                'data'  => [
                    'session_id' => (int) $sessionId,
                ]
            ]);

            return true;
        });
    }

    /**
     * =========================
     * NODEJS BRIDGE
     * =========================
     */
    protected function broadcastToNodeJS(array $payload): void
    {
        try {
            $url = config('services.nodejs.url', env('NODEJS_SERVER_URL', 'http://127.0.0.1:6001'))
                 . '/broadcast';

            Http::withHeaders([
                'X-Bridge-Secret' => env('BRIDGE_SECRET_KEY'),
                'Content-Type'    => 'application/json',
            ])
            ->timeout(2)
            ->post($url, $payload);

        } catch (\Throwable $e) {
            Log::error("❌ Node Bridge Failed: " . $e->getMessage());
        }
    }
}