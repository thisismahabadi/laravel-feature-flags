<?php

declare(strict_types=1);

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
        Schema::create('feature_flag_permissions', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('feature_flag_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->index(['feature_flag_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_flag_permissions');
    }
};
