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
        Schema::create('feature_flags', static function (Blueprint $table): void {
            $table->increaments();
            $table->string('feature_name', 50);
            $table->dateTime('revokes_at')->nullale();
            $table->timestamps();

            $table->index(['feature_name', 'revokes_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};
