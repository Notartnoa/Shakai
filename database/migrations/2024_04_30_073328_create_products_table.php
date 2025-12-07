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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('cover'); // Thumbnail image
            $table->json('detail_images')->nullable(); // Array of detail image paths
            $table->unsignedBigInteger('price')->default(0); // 0 = free
            $table->text('about');
            $table->string('file_url'); // Google Drive link (menggantikan path_file)
            $table->string('file_formats')->nullable(); // comma separated: figma,laravel,react_js
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
