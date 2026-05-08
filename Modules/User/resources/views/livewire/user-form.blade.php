<div wire:ignore.self class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-sm">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-user me-2"></i>
                    {{ $isEdit ? 'Cập nhật người dùng' : 'Thêm người dùng mới' }}
                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Alert --}}
                @if ($message)
                    <div class="alert alert-success">{{ $message }}</div>
                @endif

                <form>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label">Tên người dùng</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               wire:model="name"
                               placeholder="Nhập tên">

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               wire:model.blur="email"
                               placeholder="Nhập email">

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password (chỉ hiển thị khi thêm) --}}
                    @unless ($isEdit)
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   wire:model="password"
                                   placeholder="Nhập mật khẩu">

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endunless

                    {{-- Role --}}
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select wire:model="role"
                                class="form-control @error('role') is-invalid @enderror">
                            <option value="">-- Chọn role --</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>

                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> Hủy
                </button>

                <button class="btn btn-primary"
                        wire:click="{{ $isEdit ? 'update' : 'store' }}">
                    <i class="fa fa-save me-1"></i>
                    {{ $isEdit ? 'Cập nhật' : 'Lưu mới' }}
                </button>
            </div>

        </div>
    </div>
</div>


@push('js')
    {{-- JS để show/hide modal + fix perPage select --}}
    <script>
        document.addEventListener('livewire:init', () => {
            // Show / hide modal
            document.addEventListener('openModalUserCreate', () => {
                $('#userModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                }).modal('show');
            });
            document.addEventListener('refreshUsers', () => {
                $('#userModal').modal('hide');
            });
            document.addEventListener('show-modal-role', () => {
                $('#modalRole').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
            });
            document.addEventListener('modalRole', () => {
                $('#modalRole').modal('hide');
            });

            $('[data-dismiss="modal"]').on('click', function() {
                $(this).closest('.modal').modal('hide');          
                Livewire.dispatch('reset-form');    
            });

            document.addEventListener('open-print-window', event => {
                let newWindow = window.open('', '_blank');
                if (newWindow) {
                    let decodedHtml = atob(event.detail[0].url.split(',')[1]); // Giải mã base64
                    newWindow.document.open();
                    newWindow.document.write(decodedHtml);
                    newWindow.document.close();
                    newWindow.print();
                    setTimeout(() => newWindow.close(), 1000); // Đóng sau khi in
                } else {
                    alert('Trình duyệt chặn popup! Hãy kiểm tra cài đặt.');
                }
            });

            

        });
    </script>
@endpush