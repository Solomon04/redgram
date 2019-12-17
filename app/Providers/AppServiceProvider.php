<?php

namespace App\Providers;

use App\Contracts\Filesystem\InstagramCredentials;
use App\Contracts\Instagram\Authentication;
use App\Contracts\Instagram\Credentials;
use App\Contracts\Reddit\Configuration;
use App\Contracts\Reddit\Scraper;
use App\Services\Filesystem\InstagramCredentialsManager;
use App\Services\Instagram\AuthenticationService;
use App\Services\Instagram\CredentialsManger;
use App\Services\Reddit\ConfigurationManager;
use App\Services\Reddit\ScraperService;
use GuzzleHttp\Client;
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
            return new CredentialsManger($app->make('filesystem.disk'), $app->make('encrypter'));
        });

        // Bind Caption Contract with Service
        $this->app->bind(Configuration::class, function ($app){
            return new ConfigurationManager($app->make('filesystem.disk'));
        });

        // Inject dependencies into Auth Service.
        $this->app->singleton(Authentication::class, function ($app){
            return new AuthenticationService(new Instagram(true, true), $app->make(Credentials::class));
        });

        // Inject Guzzle Into Reddit Service
        $this->app->singleton(Scraper::class, function ($app){
            return new ScraperService(new Client(['base_uri' => 'http://www.reddit.com']), $app->make(Authentication::class), $app->make('filesystem.disk'));
        });
    }
}
