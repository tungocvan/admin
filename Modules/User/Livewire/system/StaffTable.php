<?php

namespace Modules\User\Livewire\System;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffTable extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    // ========================
    // FILTER
    // ========================
    public $search = '';
    public $perPage = 10;
    public $filterRole = '';

    // ========================
    // SELECT
    // ========================
    public $selected = [];
    public $selectAll = false;

    // ========================
    // IMPORT
    // ========================
    public $showImportModal = false;
    public $importFile;

    // ========================
    // RESET LOGIC
    // ========================
    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function resetSelection()
    {
        $this->selected = [];
        $this->selectAll = false;
    }

    // ========================
    // SELECT ALL (ONLY CURRENT PAGE)
    // ========================
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getQuery()
                ->paginate($this->perPage)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    // ========================
    // DELETE MULTIPLE
    // ========================
    public function deleteSelected()
    {
        if (in_array(auth()->id(), $this->selected)) {
            $this->dispatch('notify', content: 'Bảo mật: Không thể xóa tài khoản đang đăng nhập!', type: 'error');
            return;
        }

        User::whereIn('id', $this->selected)->delete();

        $this->resetSelection();

        $this->dispatch('notify', content: 'Đã xóa các nhân viên đã chọn.', type: 'success');
    }

    // ========================
    // DELETE SINGLE
    // ========================
    public function delete($id)
    {
        if ($id == auth()->id()) {
            $this->dispatch('notify', content: 'Không thể xóa chính mình!', type: 'error');
            return;
        }

        User::whereKey($id)->delete();

        $this->dispatch('notify', content: 'Đã xóa nhân viên.', type: 'success');
    }

    // ========================
    // EXPORT JSON
    // ========================
    public function export()
    {
        $users = $this->getQuery()->get()->map(function ($user) {
            return [
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'is_active'  => (bool) $user->is_active,
                'roles'      => $user->roles->pluck('name')->toArray(),
                'created_at' => $user->created_at->toDateTimeString(),
            ];
        });

        $fileName = 'staff_export_' . now()->format('Y_m_d_His') . '.json';

        return response()->streamDownload(function () use ($users) {
            echo $users->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $fileName);
    }

    // ========================
    // IMPORT JSON
    // ========================
    public function import()
    {
        $this->validate([
            'importFile' => 'required|mimes:json,txt|max:2048'
        ]);

        try {
            $content = file_get_contents($this->importFile->getRealPath());
            $json = json_decode($content, true);

            if (!is_array($json)) {
                throw new \Exception("File không đúng định dạng JSON.");
            }

            $countSuccess = 0;
            $countSkip = 0;

            DB::transaction(function () use ($json, &$countSuccess, &$countSkip) {

                foreach ($json as $item) {

                    // Skip nếu email đã tồn tại
                    if (User::where('email', $item['email'])->exists()) {
                        $countSkip++;
                        continue;
                    }

                    $user = User::create([
                        'name'      => $item['name'],
                        'email'     => $item['email'],
                        'password'  => Hash::make('12345678'),
                        'phone'     => $item['phone'] ?? null,
                        'is_active' => $item['is_active'] ?? true,
                    ]);

                    // Assign roles
                    if (!empty($item['roles'])) {
                        $roles = Role::whereIn('name', $item['roles'])
                            ->where('guard_name', 'admin')
                            ->pluck('name')
                            ->toArray();

                        if (!empty($roles)) {
                            $user->assignRole($roles);
                        }
                    }

                    $countSuccess++;
                }
            });

            $this->reset(['importFile', 'showImportModal']);

            $this->dispatch(
                'notify',
                content: "Import xong: {$countSuccess} thành công, {$countSkip} bỏ qua.",
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->addError('importFile', 'Lỗi: ' . $e->getMessage());
        }
    }

    // ========================
    // QUERY CORE (IMPORTANT)
    // ========================
    private function getQuery()
    {
        $user = Auth::guard('admin')->user();

        return User::query()
            ->select('id', 'name', 'email', 'phone', 'is_active', 'created_at')
            ->with('roles:id,name,guard_name')
            ->whereHas('roles')

            // 🔥 FIX CHUẨN
            ->when(!$user->hasRole('Super Admin'), function ($q) {
                $q->whereDoesntHave('roles', function ($q) {
                    $q->whereRaw('LOWER(name) = ?', ['super admin']);
                });
            })

            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })

            ->when($this->filterRole, function ($q) {
                $q->whereHas('roles', fn($r) => $r->where('id', $this->filterRole));
            })

            ->latest();
    }

    // ========================
    // RENDER
    // ========================
    public function render()
    {
        return view('User::livewire.system.staff-table', [
            'users' => $this->getQuery()->paginate($this->perPage),
            'roles' => Role::select('id', 'name')->get(),
        ]);
    }
}
