<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('owner_user_id')->nullable()->after('id')
                ->constrained('users')->cascadeOnDelete();
            $table->index('owner_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['owner_user_id']);
            $table->dropIndex(['owner_user_id']);
            $table->dropColumn('owner_user_id');
        });
    }
};
