<?php

namespace Abbasudo\LaravelPurity\Tests\Feature\Filter;

use Abbasudo\LaravelPurity\Tests\Models\Post;
use Abbasudo\LaravelPurity\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class FilterableTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        Route::get('/posts', function () {
            Post::filter()->get();
        });

        Post::create([
            'title' => 'hi',
        ]);
    }

    /**
     * A basic test example.
     */
    public function test_a_basic_request(): void
    {
        $response = $this->get('/posts');

        $response->assertStatus(200);
    }
}
