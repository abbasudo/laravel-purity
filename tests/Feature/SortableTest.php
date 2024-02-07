<?php

use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertTrue;

class SortableTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $post = new Post();
        $post->create(['title' => 'b'])->create(['title' => 'a']);

        Route::get('/posts', function () use ($post) {
            return $post->sort()->get();
        });

        $this->post = $post;
    }

    /** @test */
    public function it_can_sort(): void
    {
        $response = $this->getJson('/posts?sort=title:asc');
        assertTrue(['a', 'b'] === $response->collect()->pluck('title')->toArray());

        $response->assertOk();
    }

    /** @test */
    public function it_can_sort_limit_to_certain_fields(): void
    {
        $this->post->sortFields = ['title'];

        $response = $this->getJson('/posts?sort=title:desc');

        assertEquals(['b', 'a'], $response->collect()->pluck('title')->toArray());

        $response->assertOk();
    }

    /** @test */
    public function it_can_sort_with_renamed_fields(): void
    {
        $this->post->sortFields = ['title' => 'post_title'];

        $response = $this->getJson('/posts?sort=post_title:asc');

        assertEquals(['a', 'b'], $response->collect()->pluck('title')->toArray());

        $response->assertOk();
    }
}
