<?php

namespace Database\Seeders;

//use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Role\database\seeders\RolesAndPermissionsSeeder;
use Modules\User\database\seeders\UserAdminSeeder;
use Modules\User\database\seeders\UserSeeder;
use Modules\Admin\database\seeders\MenuCategorySeeder;
//use Modules\Website\database\Seeders\WebsiteDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

             RolesAndPermissionsSeeder::class,
            // 1. Tạo người dùng trước
            UserAdminSeeder::class,
            UserSeeder::class,
             // 5. Tạo menu sidebar
            MenuCategorySeeder::class,
            // WebsiteDatabaseSeeder::class
        ]);

    }
}
