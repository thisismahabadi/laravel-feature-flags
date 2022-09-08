<?php

declare(strict_types=1);

namespace Thisismahabadi\FeatureFlags\Traits;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait FeatureFlag
{
    public function hasAccessToFeature(int $accessorId, string $featureName): bool
    {
        if (Cache::has("feature-$featureName-$accessorId")) {
            $canAccessFeature = Cache::get("feature-$featureName-$accessorId");
        } else {
            $canAccessFeature = DB::table('feature_flags')
                ->join('feature_flag_permissions', 'feature_flag_permissions.feature_flag_id', '=', 'feature_flags.id')
                ->where('feature_flags.feature_name', $featureName)
                ->where('feature_flags.revokes_at', '>=', CarbonImmutable::now())
                ->where('feature_flag_permissions.accessor_id', $accessorId)
                ->exists();

            Cache::put("feature-$featureName-$accessorId", $canAccessFeature, config('feature_flags.cache_ttl'));
        }

        if ($canAccessFeature === false) {
            return false;
        }

        return true;
    }
}