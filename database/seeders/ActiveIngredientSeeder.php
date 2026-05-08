<?php

namespace Database\Seeders;

use App\Models\ActiveIngredient;
use Illuminate\Database\Seeder;

class ActiveIngredientSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'stt' => 1,
                'name' => 'Acetylcysteine',
                'dosage_form' => 'Dung dich uong',
                'hospital_level' => 2,
                'note' => 'Ho tro long dom va dieu tri benh ho hap.',
                'drug_group' => 'Ho hap',
                'source_file' => 'seeder',
            ],
            [
                'stt' => 2,
                'name' => 'Amoxicillin',
                'dosage_form' => 'Vien nang',
                'hospital_level' => 1,
                'note' => 'Khang sinh beta-lactam pho bien.',
                'drug_group' => 'Khang sinh',
                'source_file' => 'seeder',
            ],
            [
                'stt' => 3,
                'name' => 'Paracetamol',
                'dosage_form' => 'Vien nen',
                'hospital_level' => 3,
                'note' => 'Giam dau, ha sot cho nhieu doi tuong benh nhan.',
                'drug_group' => 'Giam dau',
                'source_file' => 'seeder',
            ],
            [
                'stt' => 4,
                'name' => 'Salbutamol',
                'dosage_form' => 'Khi dung',
                'hospital_level' => 2,
                'note' => 'Thuoc gian phe quan cap cuu hen.',
                'drug_group' => 'Ho hap',
                'source_file' => 'seeder',
            ],
        ];

        foreach ($rows as $row) {
            $payload = ActiveIngredient::sanitizePayload($row);

            ActiveIngredient::updateOrCreate(
                ['row_hash' => $payload['row_hash']],
                $payload,
            );
        }
    }
}
