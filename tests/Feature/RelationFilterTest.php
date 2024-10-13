<?php

use Abbasudo\Purity\Tests\App\Models\Post;
use Abbasudo\Purity\Tests\App\Models\Product;
use Abbasudo\Purity\Tests\App\Models\User;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RelationFilterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('/posts', function () {
            return Post::filter()->get();
        });

        Route::get('/products', function () {
            return Product::filter()->get();
        });

        Post::create([
            'title' => 'laravel purity is the best',
        ]);
    }

    /** @test */
    public function it_can_filter_by_has_many_relation(): void
    {
        $post = Post::first();

        $post->comments()->create([
            'content' => 'first comment',
        ]);

        $post->comments()->create([
            'content' => 'second comment',
        ]);

        $response = $this->getJson('/posts?filters[comments][content][$eq]=first comment');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_by_belongs_to_relation(): void
    {
        $user = User::create([
            'name' => 'Test',
        ]);

        $post = Post::create([
            'title'   => 'laravel purity is the best',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/posts?filters[user][name][$eq]=Test');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_by_belongs_to_many_relation(): void
    {
        $post = Post::first();

        $post->tags()->create([
            'name' => 'Laravel',
        ]);

        $response = $this->getJson('/posts?filters[tags][name][$eq]=Laravel');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_by_has_one_relation(): void
    {
        $product = Product::factory([
            'name' => 'Laravel Purity',
        ])->create();

        $product->book()->create([
            'name'        => 'book',
            'description' => 'book for product',
        ]);

        $response = $this->getJson('/products?filters[book][name][$eq]=book');

        $response->assertOk();
        $response->assertJsonCount(1);
    }
}
