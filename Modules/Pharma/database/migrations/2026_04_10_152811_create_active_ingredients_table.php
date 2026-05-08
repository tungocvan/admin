<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('active_ingredients', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('stt')->nullable(); // số thứ tự thông tư
            $table->string('name'); // tên hoạt chất
            $table->string('dosage_form')->nullable(); // đường dùng (tiêm, dạng hít...)
            $table->unsignedTinyInteger('hospital_level')->nullable(); // hạng bệnh viện
            $table->text('note')->nullable(); // ghi chú
            $table->string('drug_group')->nullable(); // nhóm thuốc

            $table->timestamps();

            $table->index(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_ingredients');
    }
};
