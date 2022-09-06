<?php

declare(strict_types=1);

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class CheckFeatureFlagAccess
{
    public function handle(Request $request, Closure $next, string $featureName)
    {
        $validFeature = DB::table('feature_flags')
            ->where('feature_name', $featureName)
            ->where('revokes_at', '>=', Carbon::now())
            ->first();

        if ($validFeature->exists()) {
            app()->abort(403);
        }

        $isUserAccessibleToFeature = DB::table('feature_flag_permissions')
            ->where('feature_flag_id', $validFeature->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if (!$isUserAccessibleToFeature) {
            app()->abort(403);
        }

        return $next($request);
    }
}