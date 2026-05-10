<div class="flex h-[calc(100vh-120px)] bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">

    {{-- SIDEBAR --}}
    <div class="w-1/3 border-r border-gray-100 flex flex-col bg-gray-50/50">
        <div class="p-5 border-b border-gray-100 bg-white">
            <h3 class="font-bold text-gray-800 text-lg">Hỗ trợ trực tuyến</h3>
        </div>

        <div class="overflow-y-auto flex-1 custom-scrollbar">
            @forelse($sessions as $session)
                <div
                    wire:key="session-{{ $session->id }}"
                    wire:click="selectSession({{ $session->id }})"
                    onclick="joinSession({{ $session->id }})"
                    class="group p-4 cursor-pointer border-b border-gray-50 transition-all hover:bg-white
                        {{ $activeSessionId == $session->id ? 'bg-white shadow-md border-l-4 border-l-blue-600' : '' }}"
                >
                    <div class="flex justify-between items-start">
                        <span class="font-semibold text-gray-700">
                            {{ $session->display_name }}
                        </span>

                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-gray-400">
                                {{ $session->last_message_at?->diffForHumans() }}
                            </span>

                            <button
                                wire:click.stop="clearSessionMessages({{ $session->id }})"
                                wire:confirm="Xóa toàn bộ tin nhắn?"
                                class="opacity-0 group-hover:opacity-100 p-1 text-red-400 hover:text-red-600 transition"
                            >
                                🗑
                            </button>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 truncate mt-1">
                        {{ $session->latestMessage?->message ?? 'Chưa có tin nhắn' }}
                    </p>
                </div>
            @empty
                <div class="p-10 text-center text-gray-400 text-sm italic">
                    Chưa có khách nhắn tin
                </div>
            @endforelse
        </div>
    </div>

    {{-- CHAT AREA --}}
    <div class="w-2/3 flex flex-col bg-white">

        @if ($activeSession)

            {{-- HEADER --}}
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-white/80 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                        {{ substr($activeSession->display_name, 0, 1) }}
                    </div>

                    <div>
                        <p class="font-bold text-gray-800 text-sm">
                            {{ $activeSession->display_name }}
                        </p>
                        <p class="text-[10px] text-green-500">● Đang hoạt động</p>
                    </div>
                </div>

                <div class="text-[11px] text-gray-400">
                    ID: #{{ $activeSession->session_token }}
                </div>
            </div>

            {{-- MESSAGES --}}
            <div
                id="chat-window"
                class="flex-1 p-6 overflow-y-auto space-y-4 bg-gray-50/30 custom-scrollbar"
            >
                @foreach ($activeSession->messages as $msg)
                    <div
                        wire:key="msg-{{ $msg->id }}"
                        class="flex items-end gap-2 mb-4 {{ $msg->sender_type == 'admin' ? 'flex-row-reverse' : '' }}"
                    >
                        <div class="max-w-[75%] p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed
                            {{ $msg->sender_type == 'admin'
                                ? 'bg-blue-600 text-white rounded-tr-none'
                                : 'bg-white text-gray-700 border border-gray-100 rounded-tl-none' }}"
                        >
                            {{ $msg->message }}

                            <div class="text-[9px] mt-1 opacity-70 text-right">
                                {{ $msg->created_at->format('H:i') }}
                            </div>
                        </div>

                        @if ($msg->sender_type == 'admin')
                            <button
                                wire:click="delete({{ $msg->id }})"
                                wire:confirm="Xóa tin nhắn này?"
                                class="text-gray-400 hover:text-red-500 text-xs"
                            >
                                🗑
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- INPUT --}}
            <div class="p-4 border-t border-gray-100 bg-white">
                <form wire:submit.prevent="send" class="flex gap-3 items-center">
                    <input
                        type="text"
                        wire:model="message"
                        class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none"
                        placeholder="Nhập tin nhắn..."
                    >

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold"
                    >
                        Gửi
                    </button>
                </form>
            </div>

        @else
            <div class="flex-1 flex items-center justify-center text-gray-400">
                Chọn hội thoại để bắt đầu
            </div>
        @endif
    </div>
</div>

{{-- ========================= --}}
{{-- REALTIME SCRIPT (FIXED)   --}}
{{-- ========================= --}}
@push('scripts')
<script>
document.addEventListener('livewire:init', () => {

    const chatWindow = document.getElementById('chat-window');

    const scrollToBottom = () => {
        if (chatWindow) {
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }
    };

    window.scrollToBottom = scrollToBottom;

    /**
     * JOIN SESSION ROOM
     */
    window.joinSession = (sessionId) => {
        window.activeSessionId = sessionId;
        window.Echo.connector.socket.emit('join-session', sessionId);
    };

    /**
     * LEAVE SESSION (optional)
     */
    window.leaveSession = (sessionId) => {
        window.Echo.connector.socket.emit('leave-session', sessionId);
    };

    /**
     * REALTIME LISTENER (FIXED - NO onAny)
     */
    window.Echo.connector.socket.on('MessageSent', (data) => {

        // chỉ update nếu đúng session
        if (!window.activeSessionId || data.session_id != window.activeSessionId) {
            return;
        }

        console.log("📡 MessageSent:", data);

        Livewire.dispatch('refresh-chat');

        setTimeout(() => {
            scrollToBottom();
        }, 80);
    });

    /**
     * INIT SCROLL
     */
    scrollToBottom();
});
</script>
@endpush