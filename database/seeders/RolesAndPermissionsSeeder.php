<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Danh sách quyền
        $actions = [
            'view_dashboard',
            'view_product', 'create_product', 'edit_product', 'delete_product',
            'view_order', 'edit_order', 'delete_order',
            'view_customer', 'create_customer', 'edit_customer', 'delete_customer',
            'view_role', 'create_role', 'edit_role', 'delete_role',
            'view_staff', 'create_staff', 'edit_staff', 'delete_staff',
            'view_coupon', 'create_coupon', 'edit_coupon', 'delete_coupon'
        ];

        // 1. Tạo Permissions (cho cả web và admin để an toàn)
        foreach ($actions as $action) {
            Permission::firstOrCreate(['name' => $action, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $action, 'guard_name' => 'admin']);
        }

        // 2. Tạo Role Super Admin chuẩn cho guard 'admin'
        $roleAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        // Gán full quyền (lấy các quyền thuộc guard admin)
        $roleAdmin->givePermissionTo(Permission::where('guard_name', 'admin')->get());

        // 3. Tạo Role Super Admin cho guard 'web' (nếu cần fallback)
        $roleWeb = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $roleWeb->givePermissionTo(Permission::where('guard_name', 'web')->get());
    }
}