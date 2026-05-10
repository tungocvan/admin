<?php

namespace Modules\Chat\Livewire\Chat;

use Livewire\Component;
use Modules\Chat\Models\ChatSession;
use Modules\Chat\Models\ChatMessage;
use Modules\Chat\Services\ChatService;
use Illuminate\Support\Facades\Auth;

class ChatManager extends Component
{
    public ?int $activeSessionId = null;

    public string $message = '';

    public array $messages = [];

    /**
     * =========================
     * SELECT SESSION
     * =========================
     */
    public function selectSession(int $sessionId): void
    {
        $this->activeSessionId = $sessionId;

        $session = ChatSession::find($sessionId);

        if (!$session) {
            return;
        }

        /**
         * Atomic claim
         */
        ChatSession::where('id', $sessionId)
            ->whereNull('admin_id')
            ->update([
                'admin_id' => Auth::id(),
            ]);

        /**
         * Load latest messages
         */
        $this->messages = ChatMessage::query()
            ->where('session_id', $sessionId)
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values()
            ->toArray();

        $this->dispatch('chat-session-selected', [
            'sessionId' => $sessionId
        ]);
    }

    /**
     * =========================
     * SEND MESSAGE
     * =========================
     */
    public function send(ChatService $chatService): void
    {
        if (!$this->activeSessionId) {
            return;
        }

        $message = trim($this->message);

        if (!$message) {
            return;
        }

        $chat = $chatService->sendMessage([
            'session_id'  => $this->activeSessionId,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => $message,
        ]);

        /**
         * Append local instantly
         */
        $this->messages[] = $chat->toArray();

        $this->message = '';

        $this->dispatch('message-sent');
    }

    /**
     * =========================
     * RECEIVE REALTIME MESSAGE
     * =========================
     */
    public function appendMessage(array $message): void
    {
        /**
         * Prevent duplicate
         */
        $exists = collect($this->messages)
            ->contains(fn ($msg) => $msg['id'] == $message['id']);

        if ($exists) {
            return;
        }

        $this->messages[] = $message;

        $this->dispatch('message-received');
    }

    /**
     * =========================
     * COMPUTED
     * =========================
     */
    public function getSessionsProperty()
    {
        return ChatSession::query()
            ->with([
                'user',
                'latestMessage',
            ])
            ->latest('last_message_at')
            ->limit(30)
            ->get();
    }

    /**
     * =========================
     * ACTIVE SESSION
     * =========================
     */
    public function getActiveSessionProperty()
    {
        if (!$this->activeSessionId) {
            return null;
        }

        return ChatSession::find($this->activeSessionId);
    }

    /**
     * =========================
     * RENDER
     * =========================
     */
    public function render()
    {
        return view('Chat::livewire.chat.chat-manager');
    }
}