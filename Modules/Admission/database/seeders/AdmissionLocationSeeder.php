<?php
namespace Modules\Admission\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Admission\Models\AdmissionLocation;

class AdmissionLocationSeeder extends Seeder
{
// Chạy lệnh: php artisan db:seed --class="Modules\Admission\database\seeders\AdmissionLocationSeeder"    
public function run()
    {
        // Xóa dữ liệu cũ để tránh trùng lặp khi chạy lại lệnh
        DB::table('admission_locations')->truncate();

        $filePath = base_path('storage/app/import/admission/dvhc.csv'); // Đường dẫn tới file CSV của bạn
        if (!file_exists($filePath)) return;

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Bỏ qua dòng đầu (header)

        $data = [];
        $batchSize = 500; // Nạp mỗi lần 500 dòng để tối ưu bộ nhớ

        while (($row = fgetcsv($file)) !== false) {
            // Mapping theo cấu trúc file: 
            // Cột 2 (index 1): Mã tỉnh, Cột 3 (index 2): Tên tỉnh, 
            // Cột 6 (index 5): Mã phường, Cột 7 (index 6): Tên phường
            $data[] = [
                'province_code' => $row[1],
                'province_name' => $row[2],
                'ward_code'     => $row[5],
                'ward_name'     => $row[6],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            if (count($data) >= $batchSize) {
                AdmissionLocation::insert($data);
                $data = [];
            }
        }

        if (count($data) > 0) {
            AdmissionLocation::insert($data);
        }

        fclose($file);
    }
}