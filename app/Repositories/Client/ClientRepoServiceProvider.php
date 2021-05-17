<?php
namespace App\Repositories\Client;


use Illuminate\Support\ServiceProvider;


class ClientRepoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Client\ClientInterface', 'App\Repositories\Client\ClientRepository');
    }
}