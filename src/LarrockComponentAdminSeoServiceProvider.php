<?php

namespace Larrock\ComponentAdminSeo;

use Illuminate\Support\ServiceProvider;
use Larrock\ComponentAdminSeo\Middleware\GetSeo;

class LarrockComponentAdminSeoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('larrockseo', function() {
            $class = config('larrock.components.seo', SeoComponent::class);
            return new $class;
        });

        $this->app['router']->aliasMiddleware('GetSeo', GetSeo::class);
    }
}
