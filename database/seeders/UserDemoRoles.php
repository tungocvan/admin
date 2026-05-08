<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserDemoRoles extends Seeder
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
