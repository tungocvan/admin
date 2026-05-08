<?php

namespace App\Modules\Migration\Services;

class ModuleDependencyResolver
{
    public function resolve($module)
    {
        $path = base_path("Modules/{$module}/config/module.php");

        if (!file_exists($path)) {
            return [$module];
        }

        $config = include $path;

        $deps = $config['depends'] ?? [];

        return array_merge($deps, [$module]);
    }
}
