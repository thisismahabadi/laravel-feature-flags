<?php

declare(strict_types=1);

namespace Thisismahabadi\FeatureFlags\Http\Middleware;

use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class CheckFeatureFlagAccess
{
    public function handle(Request $request, Closure $next, string $featureName)
    {
        $userId = $request->user()->id;

        if (Cache::has("user-$userId-$featureName")) {
            $isUserAccessibleToFeature = Cache::get("user-$userId-$featureName");
        } else {
            $isUserAccessibleToFeature = DB::table('feature_flags')
                ->join('feature_flag_permissions', 'feature_flag_permissions.feature_flag_id', '=', 'feature_flags.id')
                ->where('feature_flags.feature_name', $featureName)
                ->where('feature_flags.revokes_at', '>=', CarbonImmutable::now())
                ->where('feature_flag_permissions.user_id', $userId)
                ->exists();

            Cache::put("user-$userId-$featureName", $isUserAccessibleToFeature, config('feature_flags.cache_ttl'));
        }

        if ($isUserAccessibleToFeature === false) {
            app()->abort(403);
        }

        return $next($request);
    }
}
