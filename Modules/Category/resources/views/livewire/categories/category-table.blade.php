<div>
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Quản lý Danh mục</h2>
            <p class="mt-1 text-sm text-gray-500">Phân loại dữ liệu cho hệ thống website.</p>
        </div>

        <a href="{{ route('admin.category.create') }}"
           class="inline-flex items-center gap-x-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
            <svg class="-ml-0.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                      clip-rule="evenodd"/>
            </svg>
            Thêm danh mục
        </a>
    </div>

    <!-- TYPE TABS (DYNAMIC FROM DB) -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">

            @foreach($types as $t)
                <button
                    wire:click="setType('{{ $t->type }}')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition flex items-center gap-2
                        {{ $type === $t->type
                            ? 'border-indigo-500 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }}"
                >
                    <span class="text-base">{{ $t->icon }}</span>
                    {{ $t->title }}
                </button>
            @endforeach

        </nav>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden relative">

        <!-- LOADING -->
        <div wire:loading.flex wire:target="setType, delete, toggleStatus"
             class="absolute inset-0 bg-white/60 z-10 items-center justify-center backdrop-blur-[1px]">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">

                <!-- HEADER -->
                <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                        Tên danh mục
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">
                        Thứ tự
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">
                        Trạng thái
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">
                        Hành động
                    </th>
                </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-200 bg-white">

                @forelse($categories as $parent)

                    <!-- PARENT -->
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                <img class="h-10 w-10 rounded-lg object-cover border"
                                     src="{{ $parent->image ? asset('storage/'.$parent->image) : 'https://placehold.co/100' }}">

                                <div>
                                    <div class="font-bold text-gray-900 group-hover:text-indigo-600">
                                        {{ $parent->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">/{{ $parent->slug }}</div>
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4 text-center text-sm font-bold text-gray-500">
                            {{ $parent->sort_order }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleStatus({{ $parent->id }})"
                                    class="h-5 w-9 rounded-full transition
                                    {{ $parent->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                                <span class="block h-4 w-4 bg-white rounded-full transition
                                    {{ $parent->is_active ? 'translate-x-4' : '' }}"></span>
                            </button>
                        </td>

                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.category.edit', $parent->id) }}"
                               class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>

                            <button wire:click="delete({{ $parent->id }})"
                                    class="text-red-600 hover:text-red-900">
                                Xóa
                            </button>
                        </td>
                    </tr>

                    <!-- CHILD -->
                    @foreach ($parent->children as $child)
                        <tr class="bg-slate-50 hover:bg-slate-100 transition">

                            <td class="px-6 py-3 pl-16">
                                <div class="flex items-center gap-3">

                                    <span class="text-gray-400">↳</span>

                                    <span class="text-sm text-gray-700 font-medium">
                                        {{ $child->name }}
                                    </span>

                                </div>
                            </td>

                            <td class="px-6 py-3 text-center text-sm text-gray-500">
                                {{ $child->sort_order }}
                            </td>

                            <td class="px-6 py-3 text-center">
                                <span class="text-xs font-semibold
                                    {{ $child->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $child->is_active ? 'Hiện' : 'Ẩn' }}
                                </span>
                            </td>

                            <td class="px-6 py-3 text-right text-sm">
                                <a href="{{ route('admin.category.edit', $child->id) }}"
                                   class="text-indigo-600 mr-2">Sửa</a>

                                <button wire:click="delete({{ $child->id }})"
                                        class="text-red-600">Xóa</button>
                            </td>
                        </tr>

                    @endforeach

                @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-500 italic">
                            Không có danh mục
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>