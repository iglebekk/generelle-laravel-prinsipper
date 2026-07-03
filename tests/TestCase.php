<?php

namespace Iglebekk\LaravelPrinciples\Tests;

use Iglebekk\LaravelPrinciples\LaravelPrinciplesServiceProvider;
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
