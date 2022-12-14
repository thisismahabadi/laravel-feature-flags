<?php

declare(strict_types=1);

namespace Thisismahabadi\FeatureFlags;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Thisismahabadi\FeatureFlags\Http\Middleware\CheckFeatureFlagAccess;

final class FeatureFlagServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('feature_flag', CheckFeatureFlagAccess::class);

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes(
            [
                __DIR__ . '/../config/feature_flags.php' => config_path('feature_flags.php'),
            ],
            'feature-flags-config',
        );
    }
}