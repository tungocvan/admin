<?php

namespace Modules\User\Livewire\System;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffForm extends Component
{
    // ========================
    // CONST (tránh hardcode)
    // ========================
    const ROLE_SUPER_ADMIN = 'Super Admin';

    // ========================
    // STATE
    // ========================
    public $userId;
    public $isEdit = false;

    public $name, $email, $password, $is_active = true;
    public $selectedRoles = [];

    // ========================
    // MOUNT
    // ========================
    public function mount($id = null)
    {
        if ($id) {
            $this->isEdit = true;
            $this->userId = $id;

            $user = User::with('roles')->findOrFail($id);

            $this->name = $user->name;
            $this->email = $user->email;
            $this->is_active = (bool) $user->is_active;

            // 👉 dùng name (đúng với syncRoles)
            $this->selectedRoles = $user->roles->pluck('name')->toArray();
        }
    }

    // ========================
    // SAVE
    // ========================
    public function save()
    {
        // ---------- VALIDATE ----------
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'selectedRoles' => 'required|array|min:1',
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $currentUser = Auth::guard('admin')->user();

        // ---------- LẤY ROLE CŨ (nếu edit) ----------
        $existingRoles = [];

        if ($this->isEdit) {
            $existingRoles = User::find($this->userId)
                ->roles
                ->pluck('name')
                ->toArray();
        }

        // ---------- SECURITY: CHẶN GÁN SUPER ADMIN ----------
        if (!$currentUser->hasRole(self::ROLE_SUPER_ADMIN)) {

            $isTryingAssignSuperAdmin =
                in_array(self::ROLE_SUPER_ADMIN, $this->selectedRoles) &&
                !in_array(self::ROLE_SUPER_ADMIN, $existingRoles);

            if ($isTryingAssignSuperAdmin) {
                $this->addError('selectedRoles', 'Bạn không có quyền gán vai trò Super Admin.');
                return;
            }
        }

        // ---------- SAVE USER ----------
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(
            ['id' => $this->userId],
            $data
        );

        // ---------- SAFE ROLE SYNC ----------
        $rolesToSync = $this->selectedRoles;

        if (!$currentUser->hasRole(self::ROLE_SUPER_ADMIN)) {
            $rolesToSync = array_filter(
                $rolesToSync,
                fn($r) => $r !== self::ROLE_SUPER_ADMIN
            );
        }

        $user->syncRoles($rolesToSync);

        // ---------- RESPONSE ----------
        session()->flash(
            'success',
            $this->isEdit
                ? 'Cập nhật nhân viên thành công.'
                : 'Tạo nhân viên thành công.'
        );

        return redirect()->route('admin.staff.index');
    }

    // ========================
    // RENDER
    // ========================
    public function render()
    {
        $currentUser = Auth::guard('admin')->user();

        $roles = Role::query()
            ->select('id', 'name', 'guard_name')

            // 🔥 Admin không thấy Super Admin
            ->when(!$currentUser->hasRole(self::ROLE_SUPER_ADMIN), function ($q) {
                $q->where('name', '!=', self::ROLE_SUPER_ADMIN);
            })

            ->orderBy('name')
            ->get();

        return view('User::livewire.system.staff-form', [
            'roles' => $roles
        ]);
    }
}
