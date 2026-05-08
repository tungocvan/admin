<div class="space-y-8">

    <h2 class="text-xl font-semibold text-gray-800">
        Xác nhận & Hoàn tất
    </h2>

    {{-- Chọn lớp --}}
    <div>
        <label class="text-sm font-medium">Lớp đăng ký *</label>
        <select wire:model="form.LoaiLopDangKy"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500">
            <option value="">Chọn lớp</option>
            <option value="Lớp thường">Lớp thường</option>
            <option value="Tăng cường Tiếng Anh">Tăng cường Tiếng Anh</option>
            <option value="Tích hợp">Tích hợp</option>
            <option value="Tăng cường TA + Toán và Khoa học">Tăng cường TA + Toán & Khoa học</option>
        </select>
    </div>

    {{-- Cam kết --}}
    {{-- <div class="grid md:grid-cols-2 gap-4">

        @foreach ([
        'CK_GocHocTap' => 'Có góc học tập',
        'CK_SachVo' => 'Đầy đủ sách vở',
        'CK_HopPH' => 'Tham gia họp phụ huynh',
        'CK_GanGui' => 'Quan tâm con',
    ] as $key => $label)

            <label class="flex items-center gap-3 p-4 border rounded-xl">
                <input type="checkbox" wire:model="form.{{ $key }}">
                <span>{{ $label }}</span>
            </label>

        @endforeach

    </div> --}}

    {{-- Người làm đơn --}}
    <div>
        <label class="text-sm font-medium">Người làm đơn *</label>
        <input wire:model="form.NguoiLamDon" class="w-full rounded-xl border border-gray-300 px-4 py-3">
    </div>
    {{-- Sắp xếp lớp --}}
    @if ($form['Status'] === 'approved')   
    <h2 class="text-xl font-semibold text-gray-800">
        Sắp xếp vào lớp
    </h2>
    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="text-sm font-medium">Lớp</label>
            <input wire:model="form.Lop" class="w-full rounded-xl border border-gray-300 px-4 py-3">
        </div>
        <div>
            <label class="text-sm font-medium">Giáo viên chủ nhiệm</label>
            <input wire:model="form.Gvcn" class="w-full rounded-xl border border-gray-300 px-4 py-3">
        </div>
        <div>
            <label class="text-sm font-medium">Bảo mẫu</label>
            <input wire:model="form.BaoMau" class="w-full rounded-xl border border-gray-300 px-4 py-3">
        </div>
    </div>
    @endif

</div>
