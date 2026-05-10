<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                {{ $categoryId ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới' }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Quản lý danh mục theo từng loại hệ thống
            </p>
        </div>

        <a href="{{ route('admin.category.index') }}"
            class="px-4 py-3 rounded-xl border border-gray-300 text-sm bg-white hover:bg-gray-50">
            Hủy
        </a>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- LEFT -->
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">

                <!-- NAME -->
                <div>
                    <label class="text-sm font-medium text-gray-900">
                        Tên danh mục *
                    </label>

                    <input type="text" wire:model.live="name"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1
                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SLUG -->
                <div>
                    <label class="text-sm font-medium text-gray-900">Slug</label>

                    <input type="text" wire:model="slug"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1 bg-gray-50
                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">

                <!-- TYPE -->
                <div>
                    <label class="text-sm font-medium text-gray-900">
                        Loại đối tượng
                    </label>

                    <div class="flex gap-2 mt-1">
                        <select wire:model.live="type"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                            @foreach ($this->types as $t)
                                <option value="{{ $t->type }}">
                                    {{ $t->icon }} {{ $t->title }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" wire:click="openTypeModal"
                            class="px-4 py-3 rounded-xl border border-gray-300 bg-white hover:bg-gray-50">
                            +
                        </button>
                    </div>
                </div>

                <!-- PARENT -->
                <div>
                    <label class="text-sm font-medium text-gray-900">
                        Danh mục cha
                    </label>

                    <select wire:model="parent_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                        <option value="">-- Root --</option>

                        @foreach ($this->parents as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->view_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('parent_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SORT -->
                <div>
                    <label class="text-sm font-medium text-gray-900">
                        Thứ tự
                    </label>

                    <input type="number" wire:model="sort_order"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1">
                </div>

                <!-- ACTIVE -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">Hiển thị</span>

                    <input type="checkbox" wire:model="is_active">
                </div>

            </div>

            <!-- IMAGE -->
            <x-image-upload label="Ảnh danh mục" wire:model="newImage" :oldImage="$oldImage" :newImage="$newImage" />

            <!-- SUBMIT -->
            <button type="submit"
                class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold
                hover:bg-indigo-500 transition">

                <span wire:loading.remove>Lưu danh mục</span>
                <span wire:loading>Đang lưu...</span>
            </button>

        </div>
    </form>
    @if ($showTypeModal)

        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4 overflow-y-auto">

            <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6 space-y-6 max-h-[90vh] overflow-y-auto">

                <!-- HEADER -->
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">
                        Quản lý loại đối tượng
                    </h3>

                    <button wire:click="$set('showTypeModal', false)" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <!-- SELECT TYPE -->
                <div>
                    <label class="text-sm font-medium text-gray-900">
                        Chọn loại để chỉnh sửa
                    </label>

                    <select wire:model.live="selectedType"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-2
                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                        <option value="">➕ Tạo mới</option>

                        @foreach ($this->types as $t)
                            <option value="{{ $t->type }}">
                                {{ $t->icon }} {{ $t->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- CREATE NEW -->
                @if (!$selectedType)
                    <div class="space-y-4 border-t pt-4">

                        <div>
                            <label class="text-sm font-medium">Type</label>
                            <input wire:model="newType" class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Title</label>
                            <input wire:model="newTypeTitle"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1">
                        </div>

                        <!-- ICON PICKER (compact horizontal) -->
                        <div>
                            <label class="text-sm font-medium">Icon</label>

                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach (['📦', '🛍️', '📝', '📂', '🏷️', '⚙️', '⭐', '📊', '🚀', '🔥', '💡', '🎯'] as $icon)
                                    <button type="button" wire:click="$set('newTypeIcon', '{{ $icon }}')"
                                        class="w-9 h-9 text-base flex items-center justify-center rounded-lg border
                                           hover:bg-gray-50 transition
                                           {{ $newTypeIcon === $icon ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                        {{ $icon }}
                                    </button>
                                @endforeach
                            </div>

                            <input wire:model="newTypeIcon"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2 mt-2 text-sm"
                                placeholder="hoặc nhập icon">
                        </div>

                        <button wire:click="createType"
                            class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700">
                            + Tạo mới
                        </button>
                    </div>
                @endif

                <!-- EDIT -->
                @if ($selectedType)

                    <div class="space-y-4 border-t pt-4">

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-900">
                                Chỉnh sửa
                            </span>

                            <button wire:click="deleteType" class="text-red-500 text-sm hover:underline">
                                Xóa
                            </button>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Title</label>
                            <input wire:model="editTitle"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1">
                        </div>

                        <!-- ICON EDIT (LIVE SYNC + PREVIEW) -->
                        <div>
                            <label class="text-sm font-medium">Icon</label>

                            <div class="flex items-center gap-3 mt-2">

                                <!-- preview -->
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-xl">
                                    {{ $editIcon ?? '📦' }}
                                </div>

                                <input wire:model.live="editIcon"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3" placeholder="icon">
                            </div>

                            <!-- picker horizontal -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach (['📦', '🛍️', '📝', '📂', '🏷️', '⚙️', '⭐', '📊', '🚀', '🔥', '💡', '🎯'] as $icon)
                                    <button type="button" wire:click="$set('editIcon', '{{ $icon }}')"
                                        class="w-8 h-8 text-sm flex items-center justify-center rounded-lg border
                                           hover:bg-gray-50 transition
                                           {{ $editIcon === $icon ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                        {{ $icon }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- ACTIVE -->
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" wire:model="editActive">
                            Active
                        </label>

                        <!-- UPDATE -->
                        <button wire:click="updateType"
                            class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700">
                            Cập nhật
                        </button>

                    </div>

                @endif

            </div>
        </div>

    @endif
</div>
