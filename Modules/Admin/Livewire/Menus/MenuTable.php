<?php

namespace Modules\Admin\Livewire\Menus;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Admin\Models\Category;

class MenuTable extends Component
{
    use WithFileUploads;

    // ======================
    // STATE
    // ======================

    public $search = '';
    public $filterStatus = 'active';

    public $selectedMenus = [];
    public $selectAll = false;

    // Import
    public $showImportModal = false;
    public $importFile;

    // Bulk permission
    public $showBulkPermissionsModal = false;
    public $bulkPermission;

    // menu path
    public $menuPath = '';

    protected $queryString = ['search', 'filterStatus'];

    protected function rules()
    {
        return [
            'importFile' => 'nullable|file|mimes:' . implode(',', config('menu.import.allowed_mimes', ['json'])) . '|max:' . config('menu.import.max_file_size', 2048),
            'bulkPermission' => 'nullable|exists:permissions,name'
        ];
    }

    public function mount()
    {
        $this->menuPath = base_path('Modules/Admin/data/menus.json');
    }

    // ======================
    // COMPUTED
    // ======================
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedMenus = $this->baseQuery()      
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedMenus = [];
        }
    }
    public function getImportFileNameProperty()
    {
        return $this->importFile?->getClientOriginalName();
    }

    public function restoreDefaultMenu()
    {
        try {


            if (!File::exists($this->menuPath)) {

                throw new \Exception('File khôi phục mặc định không tồn tại: ' . $this->menuPath);
            }

            $content = File::get($this->menuPath);
            $json = json_decode($content, true);

            if (!is_array($json)) {
                throw new \Exception('File khôi phục mặc định không hợp lệ.');
            }

            // Xóa toàn bộ menu hiện tại trước khi khôi phục
            Category::menu()->delete();
            $result = $this->processImport($json);
            //$this->dispatch('notify', content: "Khôi phục menu mặc định hoàn tất.", type: 'success',action:'reload');
        } catch (\Exception $e) {
            Log::error('Menu restore failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            $this->dispatch('notify', content: 'Lỗi khi khôi phục menu mặc định: ' . $e->getMessage(), type: 'error');
        }
    }
    // ======================
    // UI ACTIONS
    // ======================

    public function closeImportModal()
    {
        $this->reset(['showImportModal', 'importFile']);
        $this->resetValidation();
    }

    // ======================
    // SINGLE ACTIONS
    // ======================

    public function delete($id)
    {
        if (!$menu = Category::find($id)) return;

        $menu->delete();

       
        $this->dispatch('notify', content: 'Đã xóa thành công.', type: 'success', action:'reload');
    }

    public function toggleStatus($id)
    {
        
        if ($menu = Category::find($id)) {
            $menu->update(['is_active' => !$menu->is_active]);
            $this->dispatch('notify', content: 'Đã cập nhật trạng thái.', type: 'success');
        }
    }

    public function duplicate($id)
    {
        $original = Category::with('children')->find($id);

        if (!$original) {
            return $this->notify('Menu không tồn tại.', 'warning');
        }

        DB::transaction(function () use ($original) {
            $this->duplicateRecursive($original, $original->parent_id);
        });

      
        $this->dispatch('notify', content: 'Đã nhân bản menu thành công.', type: 'success', action:'reload');
    }

    private function duplicateRecursive($node, $parentId)
    {
        $new = $node->replicate();

        $new->name = $node->name . ' (Copy)';
        $new->parent_id = $parentId;
        $new->slug = $this->generateUniqueSlug($node);
        $new->sort_order = $node->sort_order + 1;
        $new->save();

        foreach ($node->children as $child) {
            $this->duplicateRecursive($child, $new->id);
        }
    }

    private function generateUniqueSlug($original): string
    {
        $base = $original->slug
            ? $original->slug . '-copy'
            : Str::slug($original->name . ' copy');

        $slug = $base;
        $i = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // ======================
    // BULK ACTIONS
    // ======================

    public function bulkDelete()
    {
        if (empty($this->selectedMenus)) {
            $this->dispatch('notify', content: 'Vui lòng chọn menu cần xóa.', type: 'warning');
            return;
        }

        $count = count($this->selectedMenus);

        Category::whereIn('id', $this->selectedMenus)
            ->get()
            ->each
            ->delete(); // 🔥 clean + readable

        $this->selectedMenus = [];
        $this->selectAll = false;

        $this->dispatch('notify', content: "Đã xóa {$count} menu thành công.", type: 'success',action:'reload');
    }

    public function bulkToggleStatus($status)
    {
        if (empty($this->selectedMenus)) {
            return $this->notify('Vui lòng chọn menu.', 'warning');
        }

        $count = count($this->selectedMenus);

        Category::whereIn('id', $this->selectedMenus)
            ->get()
            ->each(function ($item) use ($status) {
                $item->update(['is_active' => $status]);
            });

        $this->resetSelection();

        $this->notify("Đã cập nhật {$count} menu.");
    }

    public function openBulkPermissionsModal()
    {
        if (empty($this->selectedMenus)) {
            return $this->notify('Vui lòng chọn menu.', 'warning');
        }

        $this->showBulkPermissionsModal = true;
    }

    public function bulkAssignPermissions()
    {
        if (empty($this->selectedMenus)) {
            $this->dispatch('notify', content: 'Vui lòng chọn menu cần cập nhật.', type: 'warning');
            return;
        }

        $this->validate([
            'bulkPermission' => 'nullable|exists:permissions,name'
        ]);

        $count = count($this->selectedMenus);
        $permissionName = $this->bulkPermission ?: 'không có';

        Category::whereIn('id', $this->selectedMenus)
            ->get()
            ->each
            ->update(['can' => $this->bulkPermission]); // 🔥 trigger event

        $this->selectedMenus = [];
        $this->selectAll = false;
        $this->showBulkPermissionsModal = false;
        $this->bulkPermission = null;

        $this->dispatch('notify', content: "Đã cập nhật quyền cho {$count} menu thành '{$permissionName}'.", type: 'success',action:'reload');
    }

    private function resetSelection()
    {
        $this->selectedMenus = [];
        $this->selectAll = false;
    } 

    // ======================
    // DRAG & DROP
    // ======================

    public function updateMenuOrder($list)
    {
        DB::transaction(fn() => $this->updateRecursive($list, null));

        //$this->notify('Đã cập nhật thứ tự menu.'); 
        DB::afterCommit(function () {
                Category::clearMenuCache(); // 🔥 chỉ chạy khi commit thành công
                $this->dispatch('notify', content: "Đã cập nhật thứ tự menu.", type: 'success',action:'reload',duration:100);
        });
    }

    private function updateRecursive($items, $parentId)
    {
        foreach ($items as $index => $item) {
            Category::where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'sort_order' => $index
            ]);

            if (!empty($item['children'])) {
                $this->updateRecursive($item['children'], $item['id']);
            }
        }
    }

    // ======================
    // EXPORT
    // ======================

    public function export()
    {
        
        try {
            $menus = $this->baseQuery()
                ->with('children')
                ->whereNull('parent_id')
                ->get();

            if ($menus->isEmpty()) {
                return $this->notify('Không có dữ liệu.', 'warning');
            }

            $data = $this->buildTree($menus);

            $json = json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );

            // 🔥 PATH

            $dir = dirname($this->menuPath);
       
            // 🔥 ensure folder tồn tại
            \Illuminate\Support\Facades\File::ensureDirectoryExists($dir);

            // 🔥 CHECK PERMISSION TRƯỚC
            if (!is_writable($dir)) {
                     
                return $this->notify(
                    'Thư mục không có quyền ghi: ' . $dir,
                    'error'
                );
            }
            
            // 🔥 TEST WRITE (quan trọng hơn is_writable)
            try {
                $testFile = $dir . '/.permission_test';
                file_put_contents($testFile, 'test');
                @unlink($testFile);
            } catch (\Throwable $e) {
                return $this->notify(
                    'Không thể ghi file (permission bị từ chối).',
                    'error'
                );
            }

            // 🔥 ghi file thật
            \Illuminate\Support\Facades\File::put($this->menuPath, $json);

            // 🔥 download
            return response()->streamDownload(
                function () use ($json) {
                    echo $json;
                },
                'menus_' . now()->format('Ymd_His') . '.json',
                ['Content-Type' => 'application/json']
            );
        } catch (\Throwable $e) {
            report($e);

            $this->notify('Lỗi export.', 'error');
        }
    }

    private function buildTree($nodes)
    {
        return $nodes->map(fn($n) => [
            'name' => $n->name,
            'url' => $n->url,
            'icon' => $n->icon,
            'can' => $n->can,
            'is_active' => $n->is_active,
            'children' => $this->buildTree($n->children)
        ])->toArray();
    }

    // ======================
    // IMPORT
    // ======================

    public function import()
    {
        $this->validate();

        try {
            $content = $this->getImportContent();

            $json = json_decode($content, true);

            if (!is_array($json)) {
                throw new \Exception('JSON không hợp lệ');
            }

            [$success, $skip] = $this->processImport($json);

            $this->reset(['showImportModal', 'importFile']);

            $this->notify("Import: {$success} mới, {$skip} bỏ qua.");
        } catch (\Throwable $e) {
            $this->addError('importFile', $e->getMessage());
        }
    }

    private function getImportContent()
    {
        return $this->importFile
            ? file_get_contents($this->importFile->getRealPath())
            : File::get(config('menu.seeder_path'));
    }

    private function processImport($json)
    {
        $success = 0;
        $skip = 0;


        DB::transaction(function () use ($json, &$success, &$skip) {
            foreach ($json as $index => $item) {
                $this->importRecursive($item, null, $index, $success, $skip);
            }
            DB::afterCommit(function () {
                Category::clearMenuCache(); // 🔥 chỉ chạy khi commit thành công
                $this->dispatch('notify', content: "Khôi phục menu mặc định hoàn tất.", type: 'success',action:'reload',duration:100);
            });
        });

        return [$success, $skip];
    }

    private function importRecursive($item, $parentId, $sort, &$success, &$skip)
    {
        $criteria = [
            'type' => 'menu',
            'parent_id' => $parentId,
            'name' => $item['name'],
            'url' => $item['url'] ?? null,
        ];

        $menu = Category::where($criteria)->first();

        if ($menu) {
            $skip++;
        } else {
            $menu = Category::create($criteria + [
                'icon' => $item['icon'] ?? null,
                'can' => $item['can'] ?? null,
                'is_active' => $item['is_active'] ?? true,
                'sort_order' => $sort,
            ]);

            $success++;
        }

        foreach ($item['children'] ?? [] as $i => $child) {
            $this->importRecursive($child, $menu->id, $i, $success, $skip);
        }
    }

    // ======================
    // QUERY LAYER
    // ======================

    private function baseQuery()
    {
        $query = Category::menu();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('url', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('is_active', false);
        }
        // 🔥 all = không filter 

        return $query->orderBy('sort_order');
    }

    // ======================
    // RENDER
    // ======================
    public function render()
    {
        $query = $this->baseQuery();

        // 🔥 LUÔN query theo filter (KHÔNG cache trong admin)
        $menus = $query
            ->with('children') // đảm bảo có tree
            ->whereNull('parent_id')
            ->get();

        return view('Admin::livewire.menus.menu-table', [
            'menus' => $menus,
            'totalMenus' => (clone $query)->count(),
            'activeMenus' => (clone $query)->where('is_active', true)->count(),
        ]);
    }


    // ======================
    // HELPER
    // ======================

    private function notify($message, $type = 'success')
    {

        $this->dispatch('notify', content: $message, type: $type);
    }
}
