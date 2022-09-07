<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::prefix('api/' . config('feature_flags.api_version') . '/')->group(static function (): void {
    Route::get('feature_flags', static function () {
        return DB::table('feature_flag_permissions')
            ->join('feature_flags', 'feature_flags.id', '=', 'feature_flag_permissions.feature_flag_id')
            ->select(['feature_flags.id', 'feature_flags.feature_name', 'feature_flags.revokes_at'])
            ->get();
    });
});
