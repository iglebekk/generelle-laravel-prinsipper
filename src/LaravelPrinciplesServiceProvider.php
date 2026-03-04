<?php

namespace Andersiglebekk\LaravelPrinciples;

use Illuminate\Support\ServiceProvider;

class LaravelPrinciplesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/docs/laravel-prinsipper.md' => base_path('docs/laravel-prinsipper.md'),
        ], 'laravel-prinsipper-docs');
    }
}
