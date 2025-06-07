<?php

use Orchestra\Testbench\TestCase;
use Rumenx\Feed\FeedServiceProvider;
use Rumenx\Feed\Feed;

uses(TestCase::class);

it('registers the feed binding and alias in a Laravel app', function () {
    // Debug config value before registration
    fwrite(STDERR, '\nConfig before: ' . var_export($this->app['config']->get('feed'), true) . "\n");
    $this->app->register(FeedServiceProvider::class);
    fwrite(STDERR, '\nConfig after: ' . var_export($this->app['config']->get('feed'), true) . "\n");
    expect($this->app->bound('feed'))->toBeTrue();
    expect($this->app->make('feed'))->toBeInstanceOf(Feed::class);
    expect($this->app->make(Feed::class))->toBeInstanceOf(Feed::class);
    $provider = $this->app->getProvider(FeedServiceProvider::class);
    expect($provider->provides())->toContain('feed');
    expect($provider->provides())->toContain(Feed::class);
});

function getPackageProviders($app)
{
    return [FeedServiceProvider::class];
}
