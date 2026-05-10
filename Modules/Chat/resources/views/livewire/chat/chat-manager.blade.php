<div
    x-data="chatManager()"
    x-init="init()"
    class="flex h-[calc(100vh-120px)] bg-white rounded-2xl overflow-hidden border border-gray-200"
>

    {{-- SIDEBAR --}}
    <div class="w-[340px] border-r border-gray-100 bg-gray-50">

        <div class="p-5 border-b bg-white">
            <h2 class="font-bold text-lg">
                Hỗ trợ trực tuyến
            </h2>
        </div>

        <div class="overflow-y-auto h-full">

            @foreach ($this->sessions as $session)

                <button
                    wire:key="session-{{ $session->id }}"
                    wire:click="selectSession({{ $session->id }})"
                    class="w-full text-left p-4 border-b hover:bg-white transition"
                >

                    <div class="flex justify-between items-center">

                        <div>
                            <div class="font-semibold text-sm">
                                {{ $session->guest_name ?: 'Guest' }}
                            </div>

                            <div class="text-xs text-gray-400 truncate">
                                {{ $session->latestMessage?->message }}
                            </div>
                        </div>

                        <div class="text-[10px] text-gray-400">
                            {{ $session->last_message_at?->diffForHumans() }}
                        </div>

                    </div>

                </button>

            @endforeach

        </div>

    </div>

    {{-- CHAT --}}
    <div class="flex-1 flex flex-col">

        @if ($this->activeSession)

            {{-- HEADER --}}
            <div class="p-4 border-b bg-white">
                <div class="font-bold">
                    {{ $this->activeSession->guest_name ?: 'Guest' }}
                </div>
            </div>

            {{-- MESSAGES --}}
            <div
                id="chat-window"
                class="flex-1 overflow-y-auto p-6 bg-gray-50 space-y-4"
            >

                @foreach ($messages as $msg)

                    <div
                        wire:key="msg-{{ $msg['id'] }}"
                        class="flex {{ $msg['sender_type'] === 'admin' ? 'justify-end' : 'justify-start' }}"
                    >

                        <div
                            class="max-w-[70%] px-4 py-3 rounded-2xl text-sm shadow-sm
                            {{ $msg['sender_type'] === 'admin'
                                ? 'bg-blue-600 text-white'
                                : 'bg-white border border-gray-100' }}"
                        >

                            {{ $msg['message'] }}

                            <div class="text-[10px] opacity-70 mt-1">
                                {{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- INPUT --}}
            <div class="p-4 border-t bg-white">

                <form
                    wire:submit.prevent="send"
                    class="flex gap-3"
                >

                    <input
                        type="text"
                        wire:model.live="message"
                        x-on:input="typing"
                        placeholder="Nhập tin nhắn..."
                        class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm"
                    >

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-5 py-3 bg-blue-600 text-white rounded-xl"
                    >
                        Gửi
                    </button>

                </form>

            </div>

        @else

            <div class="flex-1 flex items-center justify-center text-gray-400">
                Chọn hội thoại
            </div>

        @endif

    </div>

</div>

@push('scripts')
<script>

function chatManager() {

    return {

        typingTimeout: null,

        init() {

            /**
             * JOIN SESSION
             */
            Livewire.on('chat-session-selected', (event) => {

                const sessionId = event[0].sessionId;

                window.joinSession(sessionId);

                /**
                 * Listen realtime
                 */
                window.socket.off('MessageSent');

                window.socket.on('MessageSent', (message) => {

                    if (message.session_id != sessionId) {
                        return;
                    }

                    @this.call('appendMessage', message);

                    this.scrollBottom();
                });

                this.scrollBottom();
            });

            /**
             * LOCAL SEND
             */
            Livewire.on('message-sent', () => {
                this.scrollBottom();
            });

            /**
             * RECEIVE
             */
            Livewire.on('message-received', () => {
                this.scrollBottom();
            });
        },

        scrollBottom() {

            setTimeout(() => {

                const el = document.getElementById('chat-window');

                if (!el) {
                    return;
                }

                el.scrollTop = el.scrollHeight;

            }, 50);
        },

        typing() {

            clearTimeout(this.typingTimeout);

            window.socket.emit('typing', {
                session_id: @this.activeSessionId,
            });

            this.typingTimeout = setTimeout(() => {

                window.socket.emit('stop-typing', {
                    session_id: @this.activeSessionId,
                });

            }, 1000);
        }
    }
}
</script>
@endpush