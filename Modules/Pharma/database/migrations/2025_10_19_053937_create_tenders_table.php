<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();

            $table->string('trang_thai_trung_thau')->nullable();

            $table->decimal('gia_trung_thau', 15, 2)->nullable();
            $table->integer('soluong_trung_thau')->nullable();

            $table->string('noi_trung_thau')->nullable();
            $table->string('congty_trung_thau')->nullable();

            $table->string('so_qdtt')->nullable();
            $table->date('tg_trung_thau')->nullable();

            $table->string('nhom_thuoc_tt')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
