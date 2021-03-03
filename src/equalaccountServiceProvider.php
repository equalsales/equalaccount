<?php

namespace Equal\Account;

use Illuminate\Support\ServiceProvider;

class equalaccountServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        //$this->app->make('equal\account\TaskController');
        $this->app->make('Equal\Account\CalculatorController');
        $this->app->make('Equal\Account\jvmstController');

        $this->loadViewsFrom(__DIR__.'/views', 'calculator');
        $this->loadViewsFrom(__DIR__.'/views/jvmst', 'jvmst');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'account');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/equal/account'),
        ]);
    }
}
