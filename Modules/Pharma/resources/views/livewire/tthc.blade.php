@php
    $sortIcon = function (string $column) use ($sortColumn, $sortDirection) {
        if ($sortColumn !== $column) {
            return 'text-slate-300';
        }

        return $sortDirection === 'asc' ? 'text-emerald-500' : 'text-amber-500';
    };
@endphp

<div class="mx-auto max-w-[1600px] space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-950 text-white shadow-[0_24px_80px_-32px_rgba(15,23,42,0.85)]">
        <div class="flex flex-col gap-8 px-6 py-7 lg:flex-row lg:items-end lg:justify-between lg:px-8">
            <div class="max-w-3xl space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-100">
                    Data Platform
                </div>
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Danh mục thông tư hoạt chất</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 sm:text-base">
                        Quản trị danh mục hoạt chất theo chuẩn admin dashboard: import dữ liệu, chỉnh sửa inline, bulk actions và kiểm soát bộ lọc theo tuyến bệnh viện, nhóm thuốc, dạng bào chế.
                    </p>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-300">Tổng bản ghi</p>
                    <p class="mt-2 text-3xl font-semibold">{{ number_format($data->total()) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-300">Đang hiển thị</p>
                    <p class="mt-2 text-3xl font-semibold">{{ number_format($data->count()) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 backdrop-blur-xl">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-300">Đã chọn</p>
                    <p class="mt-2 text-3xl font-semibold">{{ number_format(count($selected)) }}</p>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 shadow-sm">
            <svg class="mt-0.5 h-5 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.792-1.803-1.804a.75.75 0 00-1.06 1.06l2.424 2.425a.75.75 0 001.137-.09l3.999-5.5z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-sm font-semibold">Thao tác đã hoàn tất</p>
                <p class="text-sm">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_280px]">
        <div class="space-y-6">
            <div class="rounded-[26px] border border-slate-200 bg-white p-4 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.35)] sm:p-5">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Bộ lọc & thao tác nhanh</h2>
                        <p class="mt-1 text-sm text-slate-500">Tìm nhanh theo tên hoạt chất, ghi chú hoặc kết hợp nhiều điều kiện lọc.</p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            wire:click="openCreateModal"
                            class="inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 4a.75.75 0 01.75.75v4.5h4.5a.75.75 0 010 1.5h-4.5v4.5a.75.75 0 01-1.5 0v-4.5h-4.5a.75.75 0 010-1.5h4.5v-4.5A.75.75 0 0110 4z" /></svg>
                            Thêm mới
                        </button>
                        <button
                            type="button"
                            wire:click="$set('showImportModal', true)"
                            class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a.75.75 0 01.75.75v6.19l1.72-1.72a.75.75 0 111.06 1.06l-3 3a.75.75 0 01-1.06 0l-3-3a.75.75 0 111.06-1.06l1.72 1.72V3.75A.75.75 0 0110 3z" /><path d="M4.75 13a.75.75 0 01.75.75v.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25v-.5a.75.75 0 011.5 0v.5A2.75 2.75 0 0113.25 17h-6.5A2.75 2.75 0 014 14.25v-.5a.75.75 0 01.75-.75z" /></svg>
                            Import
                        </button>
                        <button
                            type="button"
                            wire:click="exportCurrentView"
                            class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a.75.75 0 01.75.75v6.19l1.72-1.72a.75.75 0 111.06 1.06l-3 3a.75.75 0 01-1.06 0l-3-3a.75.75 0 111.06-1.06l1.72 1.72V3.75A.75.75 0 0110 3z" /><path d="M4.75 13a.75.75 0 01.75.75v.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25v-.5a.75.75 0 011.5 0v.5A2.75 2.75 0 0113.25 17h-6.5A2.75 2.75 0 014 14.25v-.5a.75.75 0 01.75-.75z" /></svg>
                            Export CSV
                        </button>
                    </div>
                </div>

                <div class="mt-5 grid gap-3 xl:grid-cols-[minmax(0,1.25fr)_repeat(3,minmax(0,0.65fr))_140px]">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 104.473 8.7l2.664 2.663a.75.75 0 101.06-1.06l-2.663-2.664A5.5 5.5 0 009 3.5zM5 9a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd" /></svg>
                        </span>
                        <input
                            type="text"
                            wire:model.live.debounce.400ms="search"
                            placeholder="Tìm theo tên, ghi chú, nhóm thuốc..."
                            class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-10 text-sm text-slate-700 shadow-inner outline-none transition placeholder:text-slate-400 focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                        >
                        @if ($search !== '')
                            <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-rose-500">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.22 4.22a.75.75 0 011.06 0L10 8.94l4.72-4.72a.75.75 0 111.06 1.06L11.06 10l4.72 4.72a.75.75 0 11-1.06 1.06L10 11.06l-4.72 4.72a.75.75 0 11-1.06-1.06L8.94 10 4.22 5.28a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                            </button>
                        @endif
                    </div>

                    <select wire:model.live="hospitalLevel" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">
                        <option value="">Tất cả tuyến BV</option>
                        @foreach ($hospitalOptions as $level)
                            <option value="{{ $level }}">{{ $hospitalLabels[$level] ?? ('Tuyến ' . $level) }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="drugGroup" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">
                        <option value="">Tất cả nhóm thuốc</option>
                        @foreach ($groupOptions as $group)
                            <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="dosageForm" class="h-12 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">
                        <option value="">Tất cả dạng dùng</option>
                        @foreach ($dosageOptions as $formOption)
                            <option value="{{ $formOption }}">{{ $formOption }}</option>
                        @endforeach
                    </select>

                    <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3">
                        <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Per page</span>
                        <select wire:model.live="perPage" class="h-12 flex-1 bg-transparent text-sm font-medium text-slate-700 outline-none">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3 border-t border-dashed border-slate-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                        <span class="rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">Sort: {{ $sortColumn }} / {{ $sortDirection }}</span>
                        @if ($search !== '' || $hospitalLevel !== '' || $drugGroup !== '' || $dosageForm !== '')
                            <span class="rounded-full bg-cyan-50 px-3 py-1 font-medium text-cyan-700">Bộ lọc đang bật</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="resetFilters" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600">
                            Reset filter
                        </button>
                        @if (count($selected) > 0)
                            <button type="button" wire:click="deleteSelected" wire:confirm="Bạn có chắc muốn xóa các dòng đã chọn?" class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                Xóa {{ count($selected) }} mục
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_24px_60px_-35px_rgba(15,23,42,0.45)]">
                <div wire:loading.flex class="absolute inset-0 z-20 items-center justify-center bg-white/70 backdrop-blur-sm">
                    <div class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 shadow-lg">
                        <svg class="h-5 w-5 animate-spin text-cyan-500" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" class="opacity-20" stroke="currentColor" stroke-width="4"></circle>
                            <path d="M22 12A10 10 0 0012 2" class="opacity-90" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                        </svg>
                        Đang tải dữ liệu...
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/90">
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                <th class="px-5 py-4">
                                    <input type="checkbox" wire:model.live="selectAll" class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                </th>
                                <th class="px-5 py-4">
                                    <button type="button" wire:click="sortBy('stt')" class="inline-flex items-center gap-2 transition hover:text-slate-800">
                                        STT
                                        <svg class="h-4 w-4 {{ $sortIcon('stt') }}" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5l4 5H6l4-5z" /><path d="M10 15l-4-5h8l-4 5z" /></svg>
                                    </button>
                                </th>
                                <th class="px-5 py-4">
                                    <button type="button" wire:click="sortBy('name')" class="inline-flex items-center gap-2 transition hover:text-slate-800">
                                        Hoạt chất
                                        <svg class="h-4 w-4 {{ $sortIcon('name') }}" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5l4 5H6l4-5z" /><path d="M10 15l-4-5h8l-4 5z" /></svg>
                                    </button>
                                </th>
                                <th class="px-5 py-4">
                                    <button type="button" wire:click="sortBy('dosage_form')" class="inline-flex items-center gap-2 transition hover:text-slate-800">
                                        Dạng dùng
                                        <svg class="h-4 w-4 {{ $sortIcon('dosage_form') }}" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5l4 5H6l4-5z" /><path d="M10 15l-4-5h8l-4 5z" /></svg>
                                    </button>
                                </th>
                                <th class="px-5 py-4">
                                    <button type="button" wire:click="sortBy('hospital_level')" class="inline-flex items-center gap-2 transition hover:text-slate-800">
                                        Tuyến BV
                                        <svg class="h-4 w-4 {{ $sortIcon('hospital_level') }}" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5l4 5H6l4-5z" /><path d="M10 15l-4-5h8l-4 5z" /></svg>
                                    </button>
                                </th>
                                <th class="px-5 py-4">
                                    <button type="button" wire:click="sortBy('drug_group')" class="inline-flex items-center gap-2 transition hover:text-slate-800">
                                        Nhóm thuốc
                                        <svg class="h-4 w-4 {{ $sortIcon('drug_group') }}" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5l4 5H6l4-5z" /><path d="M10 15l-4-5h8l-4 5z" /></svg>
                                    </button>
                                </th>
                                <th class="px-5 py-4">Ghi chú</th>
                                <th class="px-5 py-4 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white text-sm text-slate-700">
                            @forelse ($data as $item)
                                <tr class="transition hover:bg-cyan-50/60 {{ in_array($item->id, $selected, true) ? 'bg-cyan-50/80' : '' }}">
                                    <td class="px-5 py-4 align-top">
                                        <input type="checkbox" value="{{ $item->id }}" wire:model.live="selected" class="mt-2 h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <input type="number" value="{{ $item->stt }}" wire:change="updateField({{ $item->id }}, 'stt', $event.target.value)" class="w-20 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none transition focus:border-cyan-400 focus:bg-white">
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <input type="text" value="{{ $item->name }}" wire:change="updateField({{ $item->id }}, 'name', $event.target.value)" class="w-full min-w-[220px] rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 font-semibold text-slate-800 outline-none transition focus:border-cyan-400 focus:bg-white">
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <input type="text" value="{{ $item->dosage_form }}" wire:change="updateField({{ $item->id }}, 'dosage_form', $event.target.value)" class="w-full min-w-[170px] rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 outline-none transition focus:border-cyan-400 focus:bg-white">
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <select wire:change="updateField({{ $item->id }}, 'hospital_level', $event.target.value)" class="min-w-[140px] rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 outline-none transition focus:border-cyan-400 focus:bg-white">
                                            <option value="">Chọn tuyến</option>
                                            @foreach ($hospitalLabels as $level => $label)
                                                <option value="{{ $level }}" @selected((int) $item->hospital_level === (int) $level)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <div class="space-y-2">
                                            <input type="text" value="{{ $item->drug_group }}" wire:change="updateField({{ $item->id }}, 'drug_group', $event.target.value)" class="w-full min-w-[170px] rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 outline-none transition focus:border-cyan-400 focus:bg-white">
                                            @if ($item->drug_group)
                                                <span class="inline-flex rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700">{{ $item->drug_group }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <textarea wire:change="updateField({{ $item->id }}, 'note', $event.target.value)" rows="2" class="min-w-[220px] rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 outline-none transition focus:border-cyan-400 focus:bg-white">{{ $item->note }}</textarea>
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" wire:click="openEditModal({{ $item->id }})" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700">
                                                S
                                            </button>
                                            <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Bạn có chắc muốn xóa hoạt chất này?" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                                                X
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-400 shadow-inner">
                                                <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v9a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 16.5v-9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 9h10M7 13h5" />
                                                </svg>
                                            </div>
                                            <h3 class="mt-5 text-lg font-semibold text-slate-900">Chưa có dữ liệu phù hợp</h3>
                                            <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">Hãy thử nới bộ lọc, import file mẫu hoặc tạo mới một hoạt chất để khởi tạo danh mục.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col gap-4 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-slate-500">
                        Hiển thị <span class="font-semibold text-slate-700">{{ $data->firstItem() ?? 0 }}</span> đến <span class="font-semibold text-slate-700">{{ $data->lastItem() ?? 0 }}</span> trên tổng <span class="font-semibold text-slate-700">{{ $data->total() }}</span> bản ghi.
                    </div>
                    <div>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-[26px] border border-slate-200 bg-white p-5 shadow-[0_20px_60px_-35px_rgba(15,23,42,0.35)]">
                <h3 class="text-base font-semibold text-slate-900">Hướng dẫn import</h3>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-500">
                    <p>Hỗ trợ file <span class="font-semibold text-slate-700">CSV, TXT, XLSX</span> với đúng header:</p>
                    <code class="block rounded-2xl bg-slate-950 px-4 py-3 text-xs text-cyan-100">stt,name,dosage_form,hospital_level,note,drug_group</code>
                </div>
            </div>
        </aside>
    </div>

    @if ($showFormModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-3xl overflow-hidden rounded-[28px] bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">{{ $editingId ? 'Cập nhật hoạt chất' : 'Tạo hoạt chất mới' }}</h3>
                        <p class="mt-1 text-sm text-slate-500">Biểu mẫu chuẩn hóa cho dữ liệu TTHC.</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">X</button>
                </div>

                <div class="grid gap-5 px-6 py-6 md:grid-cols-2">
                    <label class="space-y-2">
                        <span class="text-sm font-semibold text-slate-700">STT</span>
                        <input type="number" wire:model.defer="form.stt" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white">
                    </label>

                    <label class="space-y-2">
                        <span class="text-sm font-semibold text-slate-700">Tên hoạt chất <span class="text-rose-500">*</span></span>
                        <input type="text" wire:model.defer="form.name" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white">
                        @error('form.name') <span class="text-sm text-rose-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="space-y-2">
                        <span class="text-sm font-semibold text-slate-700">Dạng dùng</span>
                        <input type="text" wire:model.defer="form.dosage_form" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white">
                    </label>

                    <label class="space-y-2">
                        <span class="text-sm font-semibold text-slate-700">Tuyến bệnh viện</span>
                        <select wire:model.defer="form.hospital_level" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white">
                            <option value="">Chọn tuyến</option>
                            @foreach ($hospitalLabels as $level => $label)
                                <option value="{{ $level }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="space-y-2 md:col-span-2">
                        <span class="text-sm font-semibold text-slate-700">Nhóm thuốc</span>
                        <input type="text" wire:model.defer="form.drug_group" class="h-11 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white">
                    </label>

                    <label class="space-y-2 md:col-span-2">
                        <span class="text-sm font-semibold text-slate-700">Ghi chú</span>
                        <textarea rows="4" wire:model.defer="form.note" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-cyan-400 focus:bg-white"></textarea>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">
                    <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-white">Hủy</button>
                    <button type="button" wire:click="save" class="rounded-2xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                        {{ $editingId ? 'Lưu cập nhật' : 'Tạo bản ghi' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showImportModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-xl overflow-hidden rounded-[28px] bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Import dữ liệu hoạt chất</h3>
                        <p class="mt-1 text-sm text-slate-500">Upload file CSV hoặc XLSX đúng header chuẩn.</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">X</button>
                </div>

                <div class="space-y-5 px-6 py-6">
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5 text-sm text-slate-500">
                        <p class="font-semibold text-slate-700">Header bắt buộc</p>
                        <code class="mt-2 block rounded-xl bg-slate-950 px-4 py-3 text-xs text-cyan-100">stt,name,dosage_form,hospital_level,note,drug_group</code>
                    </div>

                    <div>
                        <input type="file" wire:model="importFile" accept=".csv,.txt,.xlsx" class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
                        @error('importFile') <span class="mt-2 block text-sm text-rose-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">
                    <button type="button" wire:click="closeModal" class="rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-white">Hủy</button>
                    <button type="button" wire:click="import" class="rounded-2xl bg-cyan-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-cyan-700">
                        <span wire:loading.remove wire:target="import">Bắt đầu import</span>
                        <span wire:loading wire:target="import">Đang import...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
