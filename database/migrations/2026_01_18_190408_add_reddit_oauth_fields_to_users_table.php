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
        Schema::table('users', function (Blueprint $table) {
            $table->string('reddit_id')->nullable()->unique();
            $table->string('reddit_username')->nullable();
            $table->string('reddit_token')->nullable();
            $table->string('reddit_refresh_token')->nullable();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reddit_id', 'reddit_username', 'reddit_token', 'reddit_refresh_token']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
