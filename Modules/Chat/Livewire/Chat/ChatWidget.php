<?php
namespace Modules\Chat\Livewire\Chat;

use Livewire\Component;
use Modules\Chat\Services\ChatService;
use Modules\Admin\Models\ChatSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatWidget extends Component
{
    public $isOpen = false;
    public $step = 'auth'; // auth, chat
    public $message = '';
    public $sessionToken;
    public $sessionId = null;
    public $activeSessionId = null;

    
    

    public function getListeners()
    {
        return [
            // Lắng nghe tin nhắn mới từ Echo
            "echo:chat,MessageSent" => 'handleIncoming',
            'refresh-widget' => '$refresh',
            'refresh-chat' => '$refresh'
        ];
    }

    public function mount()
    {
        // Ưu tiên User đã đăng nhập
        if (Auth::check()) {
            $this->step = 'chat';
            $this->sessionToken = 'user_' . Auth::id();
            dd($this->sessionToken);
        } else {
            // Cấp token định danh cho Guest
            $this->sessionToken = session()->get('chat_token', Str::random(32));
            session(['chat_token' => $this->sessionToken]);

            // Kiểm tra nếu Guest này đã có phiên chat cũ chưa kết thúc
            $exists = ChatSession::where('session_token', $this->sessionToken)->exists();
            if ($exists) { $this->step = 'chat';
            //dd($this->sessionToken, $exists);
           }
        }
    }

    public function appendMessage($newMessage)
    {
        // Ép mảng thành Object (stdClass) để Blade đọc mượt hơn nếu cần
        $msgObject = (object) $newMessage;
        
        // Nếu thiếu thời gian, tự bổ sung
        if (!isset($msgObject->created_at)) {
            $msgObject->created_at = now();
        }

        $this->messages[] = $msgObject;
        
        // Phát sự kiện cuộn chuột xuống
        $this->dispatch('scroll-bottom');
    }

    public function handleIncoming($data)
    {
        // Chỉ refresh nếu tin nhắn thuộc về chính khách hàng này
        $session = ChatSession::where('session_token', $this->sessionToken)->first();
        
        if ($session && isset($data['session_id']) && $data['session_id'] == $session->id) {
            $this->dispatch('refresh-widget');
            $this->dispatch('scroll-bottom');
        }
    }

    // public function startChat(ChatService $chatService)
    // {
    //     // Khởi tạo session thông qua Service
    //     $chatService->getOrCreateSession($this->sessionToken);
    //     $this->step = 'chat';
    //     $this->dispatch('scroll-bottom'); 
    // }

    public function startChat(ChatService $chatService)
    {
        $session = $chatService->getOrCreateSession($this->sessionToken);
        $this->sessionId = $session->id; // Gán ID tại đây
        $this->step = 'chat';
        $this->dispatch('scroll-bottom'); 
        
    }

    public function send(ChatService $chatService)
    {
        if (empty(trim($this->message))) return;

        $session = $chatService->getOrCreateSession($this->sessionToken);
        $infoChat =[
            'session_id'   => $session->id,
            'sender_id'    => Auth::id(),
            'sender_type'  => Auth::check() ? 'user' : 'guest',
            'message'      => $this->message,
        ];
       // dd($infoChat); // 'sender_id' == null nếu là guest
        $chatService->sendMessage($infoChat);

        $this->message = '';
        $this->dispatch('scroll-bottom');
    }

    public function render()
    {
        $messages = [];
        if ($this->step === 'chat') {
            $session = ChatSession::where('session_token', $this->sessionToken)
                ->with(['messages' => fn($q) => $q->orderBy('created_at', 'asc')])
                ->first();
            $messages = $session ? $session->messages : [];
        }

        return view('Chat::livewire.chat.chat-widget', [
            'messages' => $messages
        ]);
    }
}
