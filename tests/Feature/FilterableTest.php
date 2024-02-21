<?php

use Abbasudo\Purity\Tests\Models\Comment;
use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\assertEquals;

class FilterableTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('/posts', function () {
            return Post::filter()->get();
        });

        Post::create([
            'title' => 'laravel purity is the best',
        ]);
    }

    /** @test */
    public function it_can_process_a_basic_request_without_any_filter(): void
    {
        $response = $this->getJson('/posts');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_process_a_request_without_any_matches(): void
    {
        $response = $this->getJson('/posts?filters[title][$eq]=no matches');

        $response->assertOk();
        $response->assertJsonCount(0);
    }

    /** @test */
    public function it_can_filter_with_a_basic_eq_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$eq]=laravel purity is the best');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_process_with_grouped_filters()
    {
        $post = Post::query()->create(['title' => 'title']);
        $comment = Comment::query()->create(['content' => 'comment']);

        $post->comments()->save($comment);

        $response = $this->getJson('/posts?filters[$or][0][title][$eq]=title&filters[comments][content][$eq]=comment')
            ->assertOk()
            ->assertJsonCount(1);

        assertEquals('title', $response->json()[0]['title']);
    }
}
