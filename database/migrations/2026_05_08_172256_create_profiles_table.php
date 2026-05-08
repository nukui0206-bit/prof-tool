<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('slug', 50)->unique();
            $table->string('nickname', 40);
            $table->string('avatar_path')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedBigInteger('theme_id')->nullable(); // FK は Phase 7 で themes 作成時に追加
            $table->string('theme_color', 7)->nullable();        // #RRGGBB
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_published');
            $table->index(['is_published', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
