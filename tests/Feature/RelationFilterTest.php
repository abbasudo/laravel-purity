<?php

use Abbasudo\Purity\Tests\App\Models\Post;
use Abbasudo\Purity\Tests\App\Models\Product;
use Abbasudo\Purity\Tests\App\Models\User;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function it_can_filter_by_has_one_relation(): void
    {
        $product = Product::factory([
            'name' => 'Laravel Purity',
        ])->create();

        $product->book()->create([
            'name'        => 'book',
            'description' => 'book for product',
            'page_count'  => 100,
        ]);

        $response = $this->getJson('/products?filters[book][name][$eq]=book');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_by_multiple_fields_on_one_relationship(): void
    {
        $product1 = Product::factory([
            'name' => 'Laravel Purity',
        ])->create();

        $product2 = Product::factory([
            'name' => 'Laravel Purity',
        ])->create();

        //exact match, should return
        $product1->book()->create([
            'name'        => 'book',
            'description' => 'book for product',
            'page_count'  => 100,
        ]);

        //only matching name should not return
        $product2->book()->create([
            'name'        => 'book',
            'description' => 'book for product',
            'page_count'  => 200,
        ]);

        //only matching page_count should not return
        $product2->book()->create([
            'name'        => 'book2',
            'description' => 'book2 for product2',
            'page_count'  => 100,
        ]);

        $response = $this->getJson('/products?filters[book][name][$eq]=book&filters[book][page_count][$eq]=100');

        $response->assertOk();
        $response->assertJsonCount(1);
    }
}
