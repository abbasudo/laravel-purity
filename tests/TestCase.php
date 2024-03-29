<?php

namespace Abbasudo\Purity\Tests;

use Abbasudo\Purity\PurityServiceProvider;
use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\Models\Tag;
use Abbasudo\Purity\Tests\Models\User;
use Illuminate\Database\Schema\Blueprint;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $schema = $app['db']->connection()->getSchemaBuilder();

        $schema->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $schema->create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->string('name');
            $table->timestamps();
        });

        $schema->create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->string('title')->nullable();
            $table->timestamps();
        });

        $schema->create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->string('name');
            $table->timestamps();
        });

        $schema->create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Post::class)->nullable();
            $table->foreignIdFor(Tag::class)->nullable();
            $table->timestamps();
        });

        $schema->create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Post::class)->nullable();
            $table->string('content');
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app): array
    {
        return [
            PurityServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', true);
        $app['config']->set('app.env', 'local');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
