<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Category;


class MenuCategorySeeder extends Seeder
{
    // Chạy lệnh: php artisan db:seed --class="Modules\Website\Database\Seeders\MenuCategorySeeder"
    public function run()
    {
        // 1. Xóa menu cũ để tránh trùng lặp
        Category::where('type', 'menu')->delete();

        // 2. Xác định đường dẫn file JSON (Cùng thư mục với file Seeder này)
        $jsonPath = base_path('Modules/Admin/data/menus.json');

        $items = [];

        // 3. Kiểm tra file tồn tại và lấy dữ liệu
        if (file_exists($jsonPath)) {
            $jsonContent = file_get_contents($jsonPath);
            $items = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->command->error("Lỗi định dạng JSON trong file menu.json: " . json_last_error_msg());
                return;
            }

            $this->command->info("Đang import menu từ file: menu.json");
        } else {
            // Fallback: Nếu không tìm thấy file json thì dùng dữ liệu mặc định (Optional)
            $this->command->warn("Không tìm thấy file menu.json tại: $jsonPath. Đang sử dụng dữ liệu mẫu mặc định.");
            $items = $this->getDefaultMenu();
        }

        // 4. Tiến hành tạo menu
        $sort = 0;
        foreach ($items as $item) {
            $this->createItem($item, null, $sort++);
        }

        $this->command->info("Đã khởi tạo Menu Admin thành công!");
    }

    private function createItem($item, $parentId, $sort)
    {
        // Tạo Category
        $cat = Category::create([
            'name' => $item['name'],
            'url' => $item['url'] ?? null,
            'icon' => $item['icon'] ?? null, // Lưu ý: Model Category phải có field này trong $fillable
            'can' => $item['can'] ?? null,   // Lưu ý: Model Category phải có field này trong $fillable
            'type' => 'menu',
            'type_title' => 'Danh mục Menu',
            'parent_id' => $parentId,
            'sort_order' => $sort,
            'is_active' => true,
        ]);

        // Đệ quy tạo con (nếu có)
        if (!empty($item['children'])) {
            $childSort = 0;
            foreach ($item['children'] as $child) {
                $this->createItem($child, $cat->id, $childSort++);
            }
        }
    }

    // Dữ liệu dự phòng (Phòng khi lỡ tay xóa file json)
    private function getDefaultMenu()
    {
        return [
            [ "name" => "Dashboard (Default)", "url" => "/admin", "icon" => "home", "can" => "view_dashboard" ],
            // ... có thể thêm dữ liệu cứng ở đây nếu muốn
        ];
    }
}
