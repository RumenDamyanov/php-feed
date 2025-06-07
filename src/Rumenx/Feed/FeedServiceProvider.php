<?php
// FeedServiceProvider.php
// Laravel service provider for Feed package.

namespace Rumenx\Feed;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;
use Rumenx\Feed\Laravel\LaravelCacheAdapter;
use Rumenx\Feed\Laravel\LaravelConfigAdapter;
use Rumenx\Feed\Laravel\LaravelResponseAdapter;
use Rumenx\Feed\Laravel\LaravelViewAdapter;

/**
 * Laravel service provider for the Feed package.
 * Handles registration and publishing of views and config.
 */
class FeedServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../../views', 'feed');

        $this->publishes([
      __DIR__ . '/../../../views' => base_path('resources/views/vendor/feed')
    ], 'views');

        $config_file = __DIR__ . '/../../../config/config.php';
        // Only merge if file exists and returns array
        if (file_exists($config_file)) {
            $config = require $config_file;
            if (is_array($config)) {
                $this->mergeConfigFrom($config_file, 'feed');
            }
        }

        $this->publishes([
      $config_file => config_path('feed.php')
    ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('feed', function (Container $app) {
            $params = [
                'cache' => new LaravelCacheAdapter($app['Illuminate\\Cache\\Repository']),
                'config' => new LaravelConfigAdapter($app['config']),
                'response' => new LaravelResponseAdapter($app[ResponseFactory::class]),
                'view' => new LaravelViewAdapter($app['view'])
            ];

            return new Feed($params);
        });

        $this->app->alias('feed', Feed::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['feed', Feed::class];
    }
}
