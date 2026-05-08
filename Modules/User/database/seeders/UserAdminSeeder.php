<?php

namespace Modules\User\database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAdmin = User::factory()->create([
            'name' => 'Từ Ngọc Vân',
            'email' => 'tungocvan@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $role = Role::findByName('Super Admin');
        $userAdmin->assignRole($role);


    }
}
