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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();

            $table->string('stt_thong_tu')->nullable();
            $table->string('phan_nhom_thong_tu')->nullable();

            $table->string('ten_hoat_chat');
            $table->string('nong_do_ham_luong')->nullable();
            $table->string('ten_biet_duoc')->nullable();

            $table->string('dang_bao_che')->nullable();
            $table->string('duong_dung')->nullable();
            $table->string('don_vi_tinh')->nullable();
            $table->string('quy_cach_dong_goi')->nullable();

            $table->string('giay_phep_luu_hanh')->nullable();
            $table->date('han_dung')->nullable();

            $table->string('co_so_san_xuat')->nullable();
            $table->string('nuoc_san_xuat')->nullable();

            $table->decimal('gia_ke_khai', 15, 2)->nullable();
            $table->string('nha_phan_phoi')->nullable();
            $table->decimal('don_gia', 15, 2)->nullable();
            $table->decimal('gia_von', 15, 2)->nullable();

            $table->string('link_hinh_anh')->nullable();
            $table->string('link_hssp')->nullable();

            $table->date('han_dung_visa')->nullable();
            $table->date('han_dung_gmp')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
