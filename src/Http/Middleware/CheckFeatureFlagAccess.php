<?php

declare(strict_types=1);

namespace Thisismahabadi\FeatureFlags\Http\Middleware;

use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Thisismahabadi\FeatureFlags\Traits\FeatureFlag;

final class CheckFeatureFlagAccess
{
    use FeatureFlag;

    public function handle(Request $request, Closure $next, string $featureName)
    {
        $isUserAllowedToAccessFeature = $this->hasAccessToFeature($request->user()->id, $featureName);

        if ($isUserAllowedToAccessFeature === false) {
            app()->abort(403);
        }

        return $next($request);
    }
}
