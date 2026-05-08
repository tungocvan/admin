<div
    x-data="toastManager()"
    x-init="init()"
    class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"
>
    <template x-for="item in items" :key="item.id">
        <div
            x-show="item.show"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-y-4 opacity-0 scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0 translate-x-4"
            class="w-full max-w-sm bg-white shadow-xl shadow-gray-200/50 rounded-2xl border border-gray-200 pointer-events-auto overflow-hidden ring-1 ring-black/[0.02]"
        >
            <div class="p-5 flex items-start gap-4">
                <!-- STATUS ICON -->
                <div class="flex-shrink-0">
                    <template x-if="item.type === 'success'">
                        <div class="p-1.5 bg-emerald-50 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </template>

                    <template x-if="item.type === 'error'">
                        <div class="p-1.5 bg-rose-50 rounded-lg">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </template>

                    <template x-if="item.type === 'warning'">
                        <div class="p-1.5 bg-amber-50 rounded-lg">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86l-8.38 14.5A1 1 0 002.8 20h18.4a1 1 0 00.86-1.5l-8.38-14.5a1 1 0 00-1.72 0z" />
                            </svg>
                        </div>
                    </template>
                </div>

                <!-- CONTENT -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 tracking-tight" x-text="item.title ?? 'Thông báo'"></p>
                    <p class="text-sm text-gray-500 mt-1.5 leading-relaxed" x-text="item.message"></p>
                </div>

                <!-- DISMISS -->
                <button @click="remove(item.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- INTERACTIVE ACTIONS -->
            <div x-show="item.confirm" class="px-5 pb-4 flex justify-end gap-3">
                <button
                    @click="handleCancel(item)"
                    class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors"
                >
                    Huỷ bỏ
                </button>

                <button
                    @click="handleConfirm(item)"
                    class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm shadow-blue-200 transition-colors"
                >
                    Xác nhận
                </button>
            </div>

            <!-- PROGRESS BAR (AUTO-CLOSE VISUAL) -->
            <div x-if="!item.confirm" class="h-1 bg-gray-50 w-full absolute bottom-0 left-0">
                <div 
                    class="h-full bg-gray-100 transition-all linear"
                    :style="`width: 100%; transition-duration: ${item.duration}ms; width: 0%`"
                ></div>
            </div>
        </div>
    </template>
</div>

<script>
function toastManager() {
    return {
        items: [],

        init() {
            window.addEventListener('notify', (e) => {
                this.push(e.detail);
            });
        },

        push(data) {
            const item = {
                id: Date.now() + Math.random(),
                title: data.title ?? null,
                message: data.content ?? data.message ?? 'Thao tác thành công',
                type: data.type ?? 'success',
                action: data.action ?? null,
                url: data.url ?? null,
                confirm: data.confirm ?? false,
                duration: data.duration ?? 4000,
                show: true
            };

            this.items.push(item);

            if (!item.confirm) {
                setTimeout(() => {
                    this.execute(item);
                    this.remove(item.id);
                }, item.duration);
            }
        },

        remove(id) {
            const index = this.items.findIndex(i => i.id === id);
            if (index > -1) {
                this.items[index].show = false;
                setTimeout(() => {
                    this.items = this.items.filter(i => i.id !== id);
                }, 300);
            }
        },

        handleConfirm(item) {
            this.execute(item);
            this.remove(item.id);
        },

        handleCancel(item) {
            this.remove(item.id);
        },

        execute(item) {
            switch (item.action) {
                case 'reload':
                    window.location.reload();
                    break;
                case 'redirect':
                    if (item.url) window.location.href = item.url;
                    break;
                case 'refresh':
                    if (window.Livewire) window.Livewire.dispatch('refresh');
                    break;
            }
        }
    }
}
</script>