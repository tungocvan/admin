<?php

namespace Modules\Role\database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modulesPath = base_path('Modules');

        // Các action mặc định
        $baseActions = ['view', 'create', 'edit', 'delete'];

        $actions = [];

        // Lấy danh sách thư mục Modules
        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = strtolower(basename($modulePath)); // Product → product

            foreach ($baseActions as $action) {
                $actions[] = "{$action}_{$moduleName}";
            }
        }

        // 1. Tạo permissions
        foreach ($actions as $action) {
            Permission::firstOrCreate(['name' => $action, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $action, 'guard_name' => 'admin']);
        }

        // 2. Role admin
        $roleAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        $roleAdmin->givePermissionTo(
            Permission::where('guard_name', 'admin')->get()
        );

        // 3. Role web
        $roleWeb = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);

        $roleWeb->givePermissionTo(
            Permission::where('guard_name', 'web')->get()
        );

    }
}
