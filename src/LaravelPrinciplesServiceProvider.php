<?php

namespace Andersiglebekk\LaravelPrinciples;

use Illuminate\Support\ServiceProvider;
use Andersiglebekk\LaravelPrinciples\Console\SyncLaravelPrinciplesCommand;

class LaravelPrinciplesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-prinsipper.php', 'laravel-prinsipper');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/docs/laravel-prinsipper.md' => base_path('docs/laravel-prinsipper.md'),
        ], 'laravel-prinsipper-docs');

        $this->publishes([
            __DIR__ . '/../config/laravel-prinsipper.php' => config_path('laravel-prinsipper.php'),
        ], 'laravel-prinsipper-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncLaravelPrinciplesCommand::class,
            ]);
        }
    }
}
