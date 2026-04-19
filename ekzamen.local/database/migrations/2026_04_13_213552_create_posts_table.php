<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id('post_id'); // первичный ключ post_id
            $table->string('title');
            $table->string('slug')->index();
            $table->string('description');
            $table->text('content');
            $table->unsignedInteger('views')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_approved')->default(false)->index();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);

            // внешние ключи (если нужны)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};