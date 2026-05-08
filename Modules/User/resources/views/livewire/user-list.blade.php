<div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
            <div class="col-md-10">
                <h5 class="mb-0"><i class="fas fa-users mr-2"></i>Quản lý người dùng</h5>
            </div>
            <div class="col-md-2 d-flex justify-content-end">
                <button wire:click="openModal" class="btn btn-light btn-sm">
                    <i class="fa fa-plus mr-1"></i> Thêm mới
                </button>
            </div>
        </div>

        <div class="card-body">
            {{-- Search & Filter --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">

                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                            placeholder="Tìm kiếm...">

                        @if (!$search)
                            <div class="input-group-append">
                                <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            </div>
                        @else
                            <div class="input-group-append">
                                <button class="input-group-text bg-light" type="button"
                                    wire:click="$set('search', '')"><span><i class="fas fa-times"></i></span></button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-8 d-flex justify-content-end">
                    <div class="mr-2 text-right">
                        <button wire:click="openModalRole" class="btn btn-light btn-sm">
                            <i class="fa fa-user-shield mr-1"></i> Cập nhật Role
                        </button>
                        <button
                            onclick="if(confirm('Bạn có chắc muốn xóa các user đã chọn?')) { @this.deleteSelectedUsers() }"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-trash mr-1"></i> Xóa chọn
                        </button>
                    </div>
                    <div class="text-right">
                        <button wire:click="printUsers" class="btn btn-outline-secondary btn-sm" title="In danh sách">
                            <i class="fas fa-print"></i>
                        </button>
                        <button wire:click="exportSelected" class="btn btn-outline-success btn-sm" title="Xuất Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button wire:click="exportToPDF" class="btn btn-outline-danger btn-sm" title="Xuất PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if ($message)
                <div class="alert alert-success mb-2">
                    {{ $message }}
                </div>
            @endif
            @if (session('message'))
                <div class="alert alert-info">{{ session('message') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table-hover table-bordered mb-0 table">
                    <thead class="thead-light">
                        <tr>
                            <th width="40"><input type="checkbox" wire:model.live="selectAll"
                                    wire:click="toggleSelectAll"></th>
                            <th wire:click="sortBy('id')" style="cursor:pointer;">ID <i
                                    class="fas fa-sort text-muted"></i></th>
                            <th wire:click="sortBy('name')" style="cursor:pointer;">Tên <i
                                    class="fas fa-sort text-muted"></i></th>
                            <th wire:click="sortBy('email')" style="cursor:pointer;">Email <i
                                    class="fas fa-sort text-muted"></i></th>
                            <th>Role</th>
                            <th>Xác thực</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}">
                                </td>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->getRoleNames() as $role)
                                        @if ($superAdmin === true )
                                            <span class="badge badge-danger">{{ $role }}</span>
                                        @endif
                                        @if ($superAdmin === false)
                                                {{-- <span class="badge badge-info">{{ $role }}</span> --}}
                                        @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if ($user->email_verified_at)
                                        <button wire:click="approveUser({{ $user->id }})" class="btn btn-sm">
                                            <span class="badge badge-success">Đã duyệt</span>
                                        </button>
                                    @else
                                        <button wire:click="approveUser({{ $user->id }})"
                                            class="btn btn-outline-success btn-sm">
                                            <i class="fa fa-check"></i> Duyệt
                                        </button>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button wire:click="editUser({{ $user->id }})"
                                            class="btn btn-outline-primary btn-sm"><i class="fa fa-edit"></i></button>
                                        <button wire:click="deleteUser({{ $user->id }})"
                                            class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted text-center">Không có người dùng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
    <div class="row mt-2">
        <div class="col-md-1">
            <select wire:key="per-page-select" wire:model.live="perPage" class="form-control form-control-sm"
                wire:ignore.self>
                <option value="5">Hiển thị 5</option>
                <option value="10">Hiển thị 10</option>
                <option value="50">Hiển thị 50</option>
            </select>
        </div>
        <div class="col-md-11">
            {{ $users->links() }}
        </div>

    </div>
        </div>
    </div>
    {{-- @include('livewire.users.user-form')
    @include('livewire.users.user-form-role')  --}}

</div>

@push('js')
    {{-- JS để show/hide modal + fix perPage select --}}
    <script>
        document.addEventListener('livewire:init', () => {
            // Show / hide modal
            document.addEventListener('show-modal-user', () => {
                $('#modalUser').modal({
                    backdrop: 'static',
                    keyboard: false,
                }).modal('show');
            });
            document.addEventListener('refreshUsers', () => {
                $('#modalUser').modal('hide');
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
