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
            return Post::filter()->get();
        });

        Post::create([
            'title' => 'laravel purity is good',
        ]);
    }

    public function it_can_process_a_basic_request_without_any_filter(): void
    {
        $response = $this->getJson('/posts');

        $response->assertOk();
        $response->assertJsonMissing(['message']);
        $response->assertJsonCount(1);
    }

    public function it_can_process_a_request_without_any_matches(): void
    {
        $response = $this->getJson('/posts?filters[title][$eq]=no matches');

        $response->assertOk();
        $response->assertJsonMissing(['message']);
        $response->assertJsonCount(0);
    }

    public function it_can_filter_with_a_basic_eq_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$eq]=laravel purity is good');

        $response->assertOk();
        $response->assertJsonMissing(['message']);
        $response->assertJsonCount(1);
    }
}
