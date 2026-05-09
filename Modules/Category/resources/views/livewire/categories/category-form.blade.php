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

                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- SLUG -->
                <div>
                    <label class="text-sm font-medium text-gray-900">Slug</label>

                    <input type="text" wire:model="slug"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1 bg-gray-50
                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                    @error('slug') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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

                    <select wire:model.live="type"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                        @foreach($this->types as $t)
                            <option value="{{ $t->type }}">
                                {{ $t->icon }} {{ $t->title }}
                            </option>
                        @endforeach
                    </select>
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

                        @foreach($this->parents as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->view_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('parent_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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
            <x-image-upload
                label="Ảnh danh mục"
                wire:model="newImage"
                :oldImage="$oldImage"
                :newImage="$newImage"
            />

            <!-- SUBMIT -->
            <button type="submit"
                class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold
                hover:bg-indigo-500 transition">

                <span wire:loading.remove>Lưu danh mục</span>
                <span wire:loading>Đang lưu...</span>
            </button>

        </div>
    </form>
</div>