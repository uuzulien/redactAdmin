<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Zizaco\Entrust\MigrationCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer('layouts.app', 'App\Http\ViewComposers\LayoutComposer');

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->extend('command.entrust.migration', function () {
            return new class extends MigrationCommand
            {
                public function handle()
                {
                    parent::fire();
                }
            };
        });
    }
}
