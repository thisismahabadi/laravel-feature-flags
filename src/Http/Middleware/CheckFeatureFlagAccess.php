<?php

declare(strict_types=1);

namespace Thisismahabadi\FeatureFlags\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Thisismahabadi\FeatureFlags\Traits\FeatureFlag;

final class CheckFeatureFlagAccess
{
    use FeatureFlag;

    public function handle(Request $request, Closure $next, string $featureName)
    {
        if (!Auth::check()) {
            abort(401);
        }

        $accessorId = $request->user()->featureAccessor?->id ?? $request->user()->id;
        $canAccessFeature = $this->hasAccessToFeature($accessorId, $featureName);

        if ($canAccessFeature === false) {
            abort(403);
        }

        return $next($request);
    }
}
