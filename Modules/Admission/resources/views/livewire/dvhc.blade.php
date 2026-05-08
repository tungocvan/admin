<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                Quản lý Đơn vị Hành chính
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Chỉnh sửa Tỉnh / Phường nhanh chóng
            </p>
        </div>

        <div class="text-sm text-gray-500">
            Tổng: {{ count($rows) }} phường
        </div>
    </div>

    {{-- FILTER + UPDATE PROVINCE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-4">
        <h3 class="text-lg font-semibold text-gray-800">
            Cập nhật Tỉnh / Thành phố
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            {{-- SELECT --}}
            <div>
                <label class="text-sm font-medium text-gray-600 mb-1 block">
                    Chọn Tỉnh / TP
                </label>

                <x-select-search
                    id="province_select"
                    wire:model.live="selectedProvince"
                    placeholder="Chọn tỉnh..."
                    class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">-- Chọn --</option>

                    @foreach ($provinces as $p)
                        <option value="{{ $p['province_name'] }}">
                            {{ $p['province_name'] }}
                        </option>
                    @endforeach
                </x-select-search>
            </div>

            {{-- INPUT --}}
            <div>
                <label class="text-sm font-medium text-gray-600 mb-1 block">
                    Tên Tỉnh
                </label>

                <input type="text" 
                       wire:model.live="editingProvinceName"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 mt-1 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            </div>

            {{-- ACTION --}}
         <div>
    <label class="invisible block text-sm mb-1">.</label>

    <button wire:click="updateProvinceName"
            class="w-full inline-flex items-center justify-center
                   rounded-xl bg-blue-600 hover:bg-blue-700
                   text-white font-semibold px-4 py-3
                   transition-colors">
        Cập nhật
    </button>
</div>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        {{-- SEARCH --}}
        <div class="p-4 border-b border-gray-100">
            <input type="text"
                   wire:model.live.debounce.500ms="search"
                   placeholder="Tìm kiếm phường..."
                   class="w-full rounded-xl border-gray-300 px-3 py-2 text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="overflow-x-auto max-h-[520px]">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-50/75 sticky top-0 z-10">
                    <tr class="text-left text-gray-600">
                        <th class="px-6 py-4 font-medium">Tỉnh</th>
                        <th class="px-6 py-4 font-medium">Mã</th>
                        <th class="px-6 py-4 font-medium">Tên Phường</th>
                        <th class="px-6 py-4 w-20 text-center"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($rows as $index => $row)
                        <tr class="hover:bg-gray-50/50 transition-colors">

                            {{-- PROVINCE --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $row['province_name'] }}
                                </div>
                            </td>

                            {{-- CODE --}}
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">
                                    {{ $row['ward_code'] }}
                                </span>
                            </td>

                            {{-- EDIT --}}
                            <td class="px-6 py-3">
                                <input
                                    wire:model.live="rows.{{ $index }}.ward_name"
                                    wire:blur="updateRow({{ $index }})"
                                    class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm
                                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full
                                             border border-emerald-200 text-emerald-600 text-xs font-medium">
                                    Đã lưu
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                Không có dữ liệu
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>