<div>
    {{-- DANH SÁCH VAI TRÒ --}}
    @if(!$formVisible)
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-left align-items-center">
                <h4 class="mb-0">Danh sách vai trò</h4>
                <button wire:click.prevent="create" class="btn btn-primary btn-sm mx-2">
                    <i class="fas fa-plus-circle"></i> Thêm vai trò
                </button>
                <button wire:click.prevent="permission" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Thêm module để phân quyền
                </button>
            </div>

            <div class="card-body">
                <input type="text" class="form-control mb-2" placeholder="Tìm kiếm..." wire:model.live="search">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tên vai trò</th>
                            <th>Số quyền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->permissions->count() }}</td>
                                <td>
                                    <button wire:click.prevent="edit({{ $role->id }})" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $roles->links() }}
            </div>
        </div>
    @endif

    {{-- FORM TẠO / SỬA --}}
    @if($formVisible)
        <div class="card card-primary">
            <div class="card-header">
                <h4 class="mb-0">
                    {{ $isEditMode ? 'Chỉnh sửa vai trò' : 'Tạo vai trò mới' }}
                </h4>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label>Tên vai trò</label>
                    <input type="text" wire:model="name" class="form-control" placeholder="Nhập tên vai trò...">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="mb-0"><strong>Phân quyền theo module</strong></label>
                    <div class="form-check">
                        <input type="checkbox" id="check_all" class="form-check-input" wire:model.live="selectAll">
                        <label for="check_all" class="form-check-label text-primary">Chọn tất cả quyền</label>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>Module</th>
                                <th>LIST</th>
                                <th>CREATE</th>
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissionsByModule as $module => $perms)
                                @php
                                    $basic = ['list','create','edit','delete'];
                                    $hasAll = collect($perms)->every(fn($p) => in_array($p['name'], $selectedPermissions));
                                @endphp
                                <tr>
                                    <td class="fw-bold text-primary align-middle">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                wire:click="toggleModule('{{ $module }}')"
                                                {{ $hasAll ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ strtoupper($module) }}</label>
                                        </div>
                                    </td>

                                    @foreach($basic as $action)
                                        @php
                                            $permName = "{$module}-{$action}";
                                            $exists = collect($perms)->contains('name', $permName);
                                        @endphp
                                        <td class="text-center">
                                            @if($exists)
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    wire:click="togglePermission('{{ $permName }}')"
                                                    {{ in_array($permName, $selectedPermissions) ? 'checked' : '' }}>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <button wire:click.prevent="save" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ $isEditMode ? 'Cập nhật' : 'Tạo mới' }}
                </button>
                <button wire:click.prevent="cancel" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
            </div>
        </div>
    @endif

    @if($isModuleMode)
    <div class="card card-outline card-info mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Tạo quyền mặc định cho Module</h5>
        </div>

        <div class="card-body">
            @if(empty($modules))
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-info-circle"></i>
                    Tất cả các module đã được phân quyền đầy đủ.
                </div>
            @else
                <div class="form-group">
                    <label>Chọn module</label>
                    <select wire:model.live="module" class="form-control">
                        <option value="">-- Chọn module --</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}">{{ $mod }}</option>
                        @endforeach
                    </select>
                    @error('module')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            @endif
        </div>

        <div class="card-footer d-flex justify-content-between">
            @if(!empty($modules))
                <button wire:click="storePermission" class="btn btn-success">
                    <i class="fa fa-plus-circle"></i> Tạo quyền
                </button>
            @endif
            <button wire:click="$set('isModuleMode', false)" class="btn btn-secondary">
                <i class="fa fa-times"></i> Hủy
            </button>
        </div>
    </div>
    @endif


</div>
<script>
 
    document.addEventListener("notify", () => {
        const message = event.message ?? 'Thành công!';
        $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Thông báo',
            body: message,
            autohide: true,
            delay: 2000,
        });
    });
</script>