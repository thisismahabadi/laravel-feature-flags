<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait FeatureFlag
{
    public function hasAccessToFeature(int $userId, string $featureName): bool
    {
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
            return false;
        }

        return true;
    }
}