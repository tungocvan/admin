<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Category\Models\CategoryType;

class MenuCategorySeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {
            // 🔥 1. Ensure type tồn tại
            $type = CategoryType::firstOrCreate(
                ['type' => 'menu'],
                [
                    'title' => 'Menu',
                    'icon' => '📂',
                    'is_active' => true
                ]
            );

            // 🔥 2. Xóa dữ liệu cũ theo type
            Category::where('type', $type->type)->delete();

            // 🔥 3. Load JSON
            $items = $this->loadJson();

            // 🔥 4. Insert tree
            foreach ($items as $index => $item) {
                $this->createItem($item, null, $index, $type->type);
            }

            DB::commit();

            $this->command->info('✅ Seed Menu Category thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->command->error('❌ Seed lỗi: ' . $e->getMessage());
        }
    }

    // =============================
    // LOAD JSON
    // =============================
    private function loadJson(): array
    {
        $path = base_path('Modules/Admin/data/menus.json');

        if (!File::exists($path)) {
            $this->command->warn("⚠️ Không tìm thấy menus.json → dùng default data");
            return $this->defaultData();
        }

        $content = File::get($path);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON lỗi: ' . json_last_error_msg());
        }

        $this->command->info("📦 Load menu từ JSON");

        return $data;
    }

    // =============================
    // CREATE TREE
    // =============================
    private function createItem(array $item, $parentId, $sort, string $type)
    {
        $category = Category::create([
            'name' => $item['name'],
            'slug' => $item['slug'] ?? Str::slug($item['name']),

            'type' => $type,
            'parent_id' => $parentId,

            'url' => $item['url'] ?? null,
            'icon' => $item['icon'] ?? null,
            'can' => $item['can'] ?? null,

            'sort_order' => $sort,
            'is_active' => true,
        ]);

        if (!empty($item['children'])) {
            foreach ($item['children'] as $i => $child) {
                $this->createItem($child, $category->id, $i, $type);
            }
        }
    }

    // =============================
    // DEFAULT DATA
    // =============================
    private function defaultData(): array
    {
        return [
            [
                "name" => "Dashboard",
                "url" => "/admin",
                "icon" => "home",
                "can" => "view_dashboard"
            ]
        ];
    }
}