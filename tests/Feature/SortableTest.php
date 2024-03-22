<?php

use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\Models\User;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\assertEquals;
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

    protected function tearDown(): void
    {
        parent::tearDown();
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

    /** @test
     * @dataProvider directionProvider
     */
    public function it_can_sort_null_values_last($direction): void
    {
        config(['purity.null_last' => true]);

        Post::query()->truncate();

        Post::query()
            ->create(['title' => null])
            ->create(['title' => null])
            ->create(['title' => 'a'])
            ->create(['title' => 'b']);

        $response = $this->getJson("/posts?sort=title:{$direction}");

        if ($direction === 'asc') {
            assertEquals(['a', 'b', null, null], $response->collect()->pluck('title')->all());
        } else {
            assertEquals(['b', 'a', null, null], $response->collect()->pluck('title')->all());
        }
    }

    /**
     *@test
     *@dataProvider directionProvider
     */
    public function it_can_sort_by_belongs_to_relationship(string $direction)
    {
        Post::query()->truncate();
        User::query()->truncate();

        $user2 = User::query()->create(['name' => 'user2']);
        $post2 = Post::query()->create(['title' =>'post2']);
        $post2->user()->associate($user2);
        $post2->save();

        $user1 = User::query()->create(['name' => 'user1']);
        $post1 = Post::query()->create(['title' =>'post1']);
        $post1->user()->associate($user1);
        $post1->save();

        $user3 = User::query()->create(['name' => 'user3']);
        $post3 = Post::query()->create(['title' =>'post3']);
        $post3->user()->associate($user3);
        $post3->save();

        Route::get('/posts', function ()  {
            return Post::sort()->get();
        });

        $response = $this->getJson("/posts?sort=user.name:{$direction}");

        if ($direction === 'asc') {
            assertEquals(['post1', 'post2', 'post3'], $response->collect()->pluck('title')->all());
        } else {
            assertEquals(['post3', 'post2', 'post1'], $response->collect()->pluck('title')->all());
        }
    }

    public static function directionProvider(): array
    {
        return [['asc'], ['desc']];
    }
}
