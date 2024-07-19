<?php

namespace ThirtyBitTech\Autoreply;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;
use Illuminate\Support\Facades\Artisan;
use Edalzell\Forma\Forma;
use Statamic\Events\FormSubmitted;
use ThirtyBitTech\Autoreply\Listeners\SendAutoreply;
use ThirtyBitTech\Autoreply\Fieldtypes\FormFields;


class ServiceProvider extends AddonServiceProvider
{

    protected $fieldtypes = [
        FormFields::class
    ];

    protected $listen = [
        FormSubmitted::class => [
            SendAutoreply::class,
        ],
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $vite = [
        'input' => ['resources/js/cp.js'],
        'publicDirectory' => 'dist',
        'hotFile' => __DIR__.'/../dist/hot',
    ];


    public function boot()
    {
        parent::boot();
        
        $this->createNavigation();
        $this->mergeConfigurations();
        $this->publishAssets();
    }


    private function createNavigation(): void
    {
        Forma::add('thirtybittech/autoreply');
    }

    private function mergeConfigurations() : void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/autoreply.php', 'autoreplyautoreply');
    }

    private function publishAssets() : void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/autoreply.php' => config_path('autoreply.php'),
            ], 'autoreply-config');


            $this->loadViewsFrom(__DIR__.'/../resources/views', 'autoreply');

            $this->publishes([
                __DIR__.'/../resources/views/emails' => resource_path('views/vendor/autoreply/emails'),
            ], 'autoreply-views');
            
        }

        Statamic::afterInstalled(function () {
            Artisan::call('vendor:publish', ['--tag' => 'autoreply-config']);
        });
    }
}