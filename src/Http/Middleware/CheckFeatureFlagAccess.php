<?php

declare(strict_types=1);

namespace Thisismahabadi\FeatureFlags\Http\Middleware;

use Carbon\Carbon;
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
            ->select('id')
            ->first();

        if (!$validFeature) {
            app()->abort(403);
        }

        $isUserAccessibleToFeature = DB::table('feature_flag_permissions')
            ->where('feature_flag_id', $validFeature->id)
            ->where('user_id', $request->user()->id)
            ->select('id')
            ->first();

        if (!$isUserAccessibleToFeature) {
            app()->abort(403);
        }

        return $next($request);
    }
}