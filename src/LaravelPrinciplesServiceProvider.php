<?php

namespace Andersiglebekk\LaravelPrinciples;

use Andersiglebekk\LaravelPrinciples\Console\SyncLaravelPrinciplesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPrinciplesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-prinsipper')
            ->hasConfigFile('laravel-prinsipper')
            ->hasCommand(SyncLaravelPrinciplesCommand::class);
    }

    public function packageBooted(): void
    {
        $this->publishes([
            __DIR__.'/../resources/docs/laravel-prinsipper.md' => base_path('docs/laravel-prinsipper.md'),
        ], 'laravel-prinsipper-docs');
    }
}
