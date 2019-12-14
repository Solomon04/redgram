<?php

namespace App\Providers;

use App\Contracts\Filesystem\Credentials;
use App\Contracts\Instagram\Authentication;
use App\Services\Filesystem\CredentialsManager;
use App\Services\Instagram\AuthenticationService;
use Illuminate\Support\ServiceProvider;
use InstagramAPI\Instagram;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind Credential Contract with Service
        $this->app->bind(Credentials::class, function ($app){
            return new CredentialsManager($app->make('filesystem.disk'), $app->make('encrypter'));
        });

        // Inject dependencies into Auth Service.
        $this->app->singleton(Authentication::class, function ($app){
            $instagram = new Instagram(true, true);
            $filesystem = new CredentialsManager($app->make('filesystem.disk'), $app->make('encrypter'));
            return new AuthenticationService($instagram, $filesystem);
        });
    }
}
