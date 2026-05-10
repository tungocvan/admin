<?php

namespace Modules\Chat\Livewire\Chat;

use Livewire\Component;
use Modules\Chat\Models\ChatSession;
use Modules\Chat\Services\ChatService;
use Illuminate\Support\Facades\Auth;

class ChatManager extends Component
{
    public $activeSessionId = null;
    public $message = '';
 
    /**
     * =========================
     * REALTIME LISTENERS (LEVEL 2 CLEAN)
     * =========================
     */
    public function getListeners()
    {
        return [
            'refresh-chat' => '$refresh',
            'refresh-widget' => '$refresh',
        ];
    }

    /**
     * =========================
     * SELECT SESSION
     * =========================
     */
    public function selectSession($id)
    {
        $this->activeSessionId = $id;

        $session = ChatSession::find($id);

        if ($session && !$session->admin_id) {
            $session->update([
                'admin_id' => Auth::id(),
            ]);
        }

        $this->dispatch('scroll-chat-to-bottom');
    }

    /**
     * =========================
     * SEND MESSAGE
     * =========================
     */
    public function send(ChatService $chatService)
    {
        if (!$this->activeSessionId || empty(trim($this->message))) {
            return;
        }

        $chatService->sendMessage([
            'session_id'  => $this->activeSessionId,
            'sender_id'   => Auth::id(),
            'sender_type' => 'admin',
            'message'     => $this->message,
        ]);

        $this->message = '';

        $this->dispatch('scroll-chat-to-bottom');
    }

    /**
     * =========================
     * DELETE MESSAGE
     * =========================
     */
    public function delete($id, ChatService $service)
    {
        $service->deleteMessage($id);

        $this->dispatch('refresh-chat');
    }

    /**
     * =========================
     * CLEAR SESSION MESSAGES
     * =========================
     */
    public function clearSessionMessages($sessionId, ChatService $service)
    {
        $service->deleteAllMessages($sessionId);

        if ($this->activeSessionId == $sessionId) {
            $this->dispatch('refresh-chat');
        }
    }

    /**
     * =========================
     * RENDER VIEW (FINAL FIX)
     * =========================
     */
    public function render()
    {
        return view('Chat::livewire.chat.chat-manager', [
            /**
             * SESSION LIST (SIDEBAR)
             * + eager load latest message + user
             */
            'sessions' => ChatSession::with([
                    'user',
                    'latestMessage'
                ])
                ->orderBy('last_message_at', 'desc')
                ->limit(30)
                ->get()
                ->map(function ($session) {

                    // 👉 FIX: hiển thị tên frontend chat
                    $session->display_name = $this->resolveDisplayName($session);

                    return $session;
                }),

            /**
             * ACTIVE SESSION (CHAT WINDOW)
             */
            'activeSession' => $this->activeSessionId
                ? ChatSession::with([
                        'messages' => fn($q) => $q->orderBy('created_at', 'asc')
                    ])
                    ->find($this->activeSessionId)
                : null,
        ]);
    }

    /**
     * =========================
     * RESOLVE FRONTEND CHAT NAME (IMPORTANT FIX)
     * =========================
     */
    private function resolveDisplayName($session): string
    {
        // 1. User login
        //dd($session);
        if ($session->user) {
            return $session->user->name ?? 'User';
        }

        // 2. Guest chat name (frontend)
        if (!empty($session->guest_name)) {
            return $session->guest_name;
        }

        // 3. fallback
        return 'Guest #' . substr($session->session_token, -5);
    }
}