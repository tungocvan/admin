<?php

namespace Modules\Admin\Livewire\Settings;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class ModulesForm extends Component
{
    public $modules = [];

    public function mount()
    {
        $this->loadModules();
    }

    public function loadModules()
    {
        $registry = config('modules.registry', []);
        $this->modules = collect($registry)->map(function ($module, $name) {
            return [
                'name' => $name,
                'type' => $module['type'],
                'enabled' => $module['enabled'],
                'path' => $module['path'],
                'source' => $module['source'],
            ];
        })->sortBy(['type', 'name'])->values()->all();
    }

    public function toggleModule($moduleName)
    {
        $module = collect($this->modules)->firstWhere('name', $moduleName);
        if (!$module) return;

        $newEnabled = !$module['enabled'];

        // Update manifest file
        $success = $this->updateModuleManifest($module['path'], $newEnabled);

        if ($success) {
            // Update in-memory config
            config(['modules.registry.' . $moduleName . '.enabled' => $newEnabled]);

            // Reload modules
            $this->loadModules();

            session()->flash('message', 'Module ' . $moduleName . ' đã được ' . ($newEnabled ? 'bật' : 'tắt'));
        }
        // If not successful, error message is already set in updateModuleManifest
    }

    private function updateModuleManifest($modulePath, $enabled)
    {
        $manifestPaths = [
            $modulePath . '/config/module.php',
            $modulePath . '/Config/module.php',
        ];

        foreach ($manifestPaths as $manifestPath) {
            if (File::exists($manifestPath)) {
                try {
                    // Check if file is writable
                    if (!File::isWritable($manifestPath)) {
                        throw new \Exception("File không có quyền ghi: {$manifestPath}");
                    }

                    $manifest = require $manifestPath;

                    if (is_array($manifest)) {
                        $manifest['enabled'] = $enabled;

                        $content = "<?php\n\nreturn " . var_export($manifest, true) . ";\n";
                        File::put($manifestPath, $content);
                    }
                } catch (\Exception $e) {
                    // Log error and show user-friendly message
                    \Log::error("Không thể cập nhật manifest module: " . $e->getMessage());
                    session()->flash('error', 'Không thể cập nhật module: ' . $e->getMessage());
                    return false;
                }
                break;
            }
        }

        return true;
    }

    public function render()
    {
        return view('Admin::livewire.settings.modules-form');
    }
}
