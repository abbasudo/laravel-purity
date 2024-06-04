<?php

namespace Abbasudo\Purity\Tests;

use Abbasudo\Purity\PurityServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/App/Migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            PurityServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        //
    }
}
