<?php

namespace Abbasudo\Purity\Tests;

use Abbasudo\Purity\PurityServiceProvider;
use Abbasudo\Purity\Tests\App\Models\Author;
use Abbasudo\Purity\Tests\App\Models\Post;
use Abbasudo\Purity\Tests\App\Models\Tag;
use Abbasudo\Purity\Tests\App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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
