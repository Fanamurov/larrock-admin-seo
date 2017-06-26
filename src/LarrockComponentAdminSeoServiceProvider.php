<?php

namespace Larrock\ComponentAdminSeo;

use Illuminate\Support\ServiceProvider;

class LarrockComponentAdminSeoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(){}

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make(SeoComponent::class);
    }
}
