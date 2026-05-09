<?php

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('category_types')->insert([
    
            [
                'type' => 'product',
                'title' => 'Danh mục Sản phẩm',
                'icon' => '🛍️'
            ],
            [
                'type' => 'post',
                'title' => 'Danh mục Bài viết',
                'icon' => '📝'
            ],
            [
                    'type' => 'menu',
                    'title' => 'Danh mục Menu',
                    'icon' => '📂',
            ]
        ]);
    }
}
