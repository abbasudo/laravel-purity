<?php

use Abbasudo\Purity\Tests\Models\Comment;
use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\Models\Project;
use Abbasudo\Purity\Tests\Models\Tag;
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

    /**
     *@test
     *@dataProvider directionProvider
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
        $this->truncateAll();

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

    /**
     *@test
     *@dataProvider directionProvider
     */
    public function it_can_sort_by_has_one_relationship(string $direction)
    {
        $this->truncateAll();

        $user2 = User::query()->create(['name' => 'user2']);
        $project2 = Project::query()->create(['name' =>'project2']);
        $project2->user()->associate($user2);
        $project2->save();

        $user1 = User::query()->create(['name' => 'user1']);
        $project1 = Project::query()->create(['name' =>'project1']);
        $project1->user()->associate($user1);
        $project1->save();

        $user3 = User::query()->create(['name' => 'user3']);
        $project3 = Project::query()->create(['name' =>'project3']);
        $project3->user()->associate($user3);
        $project3->save();

        Route::get('/users', function ()  {
            return User::sort()->get();
        });

        $response = $this->getJson("/users?sort=project.name:{$direction}");

        if ($direction === 'asc') {
            assertEquals(['user1', 'user2', 'user3'], $response->collect()->pluck('name')->all());
        } else {
            assertEquals(['user3', 'user2', 'user1'], $response->collect()->pluck('name')->all());
        }
    }

    /**
     *@test
     *@dataProvider directionProvider
     */
    public function it_can_sort_by_has_many_relationship(string $direction)
    {
        $this->truncateAll();

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

        Route::get('/users', function ()  {
            return User::sort()->get();
        });

        $response = $this->getJson("/users?sort=post.title:{$direction}");

        if ($direction === 'asc') {
            assertEquals(['user1', 'user2', 'user3'], $response->collect()->pluck('name')->all());
        } else {
            assertEquals(['user3', 'user2', 'user1'], $response->collect()->pluck('name')->all());
        }
    }

    /**
     *@test
     *@dataProvider directionProvider
     */
    public function it_can_sort_by_belongs_to_many_relationship(string $direction)
    {
        $this->truncateAll();

        $post2 = Post::query()->create(['title' => 'title2']);
        $post1 = Post::query()->create(['title' => 'title1']);
        $post3 = Post::query()->create(['title' => 'title3']);

        $tag1 = Tag::query()->create(['name' => 'tag1']);
        $tag2 = Tag::query()->create(['name' => 'tag2']);
        $tag3 = Tag::query()->create(['name' => 'tag3']);
        $tag4 = Tag::query()->create(['name' => 'tag4']);
        $tag5 = Tag::query()->create(['name' => 'tag5']);
        $tag6 = Tag::query()->create(['name' => 'tag6']);

        $post1->tags()->attach([$tag4->getKey(), $tag1->getKey()]);
        $post2->tags()->attach([$tag5->getKey(), $tag2->getKey()]);
        $post3->tags()->attach([$tag6->getKey(), $tag4->getKey()]);

        Route::get('/posts', function ()  {
            return Post::sort()->get();
        });

        $response = $this->getJson("/posts?sort=tags.name:{$direction}");

        if ($direction === 'asc') {
            assertEquals(['title1', 'title2', 'title3'], $response->collect()->pluck('title')->all());
        } else {
            assertEquals(['title3', 'title2', 'title1'], $response->collect()->pluck('title')->all());
        }
    }

    public function truncateAll(): void
    {
        Post::query()->truncate();
        Tag::query()->truncate();
        Comment::query()->truncate();
        User::query()->truncate();
    }

    public static function directionProvider(): array
    {
        return [['asc'], ['desc']];
    }
}
