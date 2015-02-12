<?php

namespace mBear\Drive\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class DriveServiceProvider
 *
 * @author  Nam Hoang Luu <nam@mbearvn.com>
 * @package mBear\mDrive\Providers
 *
 */
class DriveServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('storage.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.

    }
}
