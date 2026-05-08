<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wp_post_tag', function (Blueprint $table) {

        
                            $table->primary(['post_id', 'tag_id']);
                            $table->foreignId('post_id')->constrained('wp_posts')->cascadeOnDelete();
                            $table->foreignId('tag_id')->constrained('wp_tags')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wp_post_tag');
    }
};