<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class RedisServiceProvider
 * @package App\Providers
 */
class RedisServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('redis', function ($app) {
            return $this->getRedisManagerInstance($app, 'predis', $app['config']['database.redis']);
        });
    }

    /**
     * @param Application $app
     * @param string $driver
     * @param array $config
     * @return RedisManager
     */
    public function getRedisManagerInstance(Application $app, string $driver, array $config): RedisManager
    {
        return new RedisManager($app, $driver, $config);
    }
}
