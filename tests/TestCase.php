<?php

namespace Andersiglebekk\LaravelPrinciples\Tests;

use Andersiglebekk\LaravelPrinciples\LaravelPrinciplesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelPrinciplesServiceProvider::class,
        ];
    }
}
