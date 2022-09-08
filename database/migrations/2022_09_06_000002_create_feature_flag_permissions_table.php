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
            $table->id();
            $table->unsignedBigInteger('feature_flag_id');
            $table->unsignedBigInteger('accessor_id');
            $table->string('accessor_type', 50);
            $table->timestamps();

            $table->index(['feature_flag_id', 'accessor_id']);
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
