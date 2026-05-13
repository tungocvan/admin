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

    protected $listeners = [
        'appendMessage',
    ];

    /**
     * =========================================
     * SELECT SESSION
     * =========================================
     */
    public function selectSession(
        int $sessionId
    ): void {

        $this->activeSessionId = $sessionId;

        $session = ChatSession::find($sessionId);

        if (!$session) {
            return;
        }

        /**
         * Claim admin
         */
        ChatSession::query()
            ->where('id', $sessionId)
            ->whereNull('admin_id')
            ->update([
                'admin_id' => Auth::id(),
            ]);

        /**
         * Load messages
         */
        $this->messages = ChatMessage::query()
            ->where(
                'chat_session_id',
                $sessionId
            )
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values()
            ->toArray();

        /**
         * Join realtime room
         */
        $this->dispatch(
            'chat-session-selected',
            sessionId: $sessionId
        );

        /**
         * Scroll
         */
        $this->dispatch('scroll-bottom');
    }

    /**
     * =========================================
     * SEND MESSAGE
     * =========================================
     */
    public function send(
        ChatService $chatService
    ): void {

        /**
         * No active session
         */
        if (!$this->activeSessionId) {
            return;
        }

        /**
         * Clean message
         */
        $message = trim($this->message);

        /**
         * Empty message
         */
        if (!$message) {
            return;
        }

        /**
         * Save message
         */
        $chat = $chatService->sendMessage([

            'chat_session_id' => $this->activeSessionId,

            'sender_id' => Auth::id(),

            'sender_type' => 'admin',

            'message' => $message,

        ]);

        /**
         * IMPORTANT:
         * Do NOT append local message
         *
         * Realtime event will append automatically
         * via Echo -> appendMessage()
         */

        /**
         * Reset input
         */
        $this->reset('message');

        /**
         * Scroll bottom
         */
        $this->dispatch(
            'scroll-bottom'
        );
    }

    /**
     * =========================================
     * REALTIME APPEND
     * =========================================
     */
    public function appendMessage(
        $message
    ): void {

        if (is_string($message)) {

            $message = json_decode(
                $message,
                true
            );
        }

        if (!is_array($message)) {
            return;
        }

        /**
         * Wrong room
         */
        if (
            (int) $message['chat_session_id']
            !==
            $this->activeSessionId
        ) {
            return;
        }

        /**
         * Duplicate prevent
         */
        $exists = collect($this->messages)
            ->contains(
                fn($msg)
                =>
                $msg['id']
                    ==
                    $message['id']
            );

        if ($exists) {
            return;
        }

        /**
         * Append realtime
         */
        $this->messages[] = $message;

        /**
         * Scroll
         */
        $this->dispatch('scroll-bottom');
    }

    /**
     * =========================================
     * COMPUTED SESSIONS
     * =========================================
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
     * =========================================
     * ACTIVE SESSION
     * =========================================
     */
    public function getActiveSessionProperty()
    {
        if (!$this->activeSessionId) {
            return null;
        }

        return ChatSession::find(
            $this->activeSessionId
        );
    }

    /**
     * =========================================
     * RENDER
     * =========================================
     */
    public function render()
    {
        return view(
            'Chat::livewire.chat.chat-manager'
        );
    }
}
