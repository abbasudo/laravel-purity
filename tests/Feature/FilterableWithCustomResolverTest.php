<?php

use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\Models\Tag;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\assertEquals;

class FilterableWithCustomResolverTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('/tags', function () {
            return Tag::with(['posts'])->filter()->get();
        });

        Tag::create([
            'name' => 'laravel',
        ])
        ->posts()->create([
            'title' => 'laravel is the best',
        ]);

        Tag::create([
            'name' => 'purity',
        ])->posts()->create([
            'title' => 'purity is great',
        ]);

        Tag::create([
            'name' => 'pure_tag',
        ]);
    }

    /** @test */
    public function it_can_process_a_basic_request_without_any_filter(): void
    {
        $response = $this->getJson('/tags');

        $response->assertOk();
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_process_a_request_without_any_matches(): void
    {
        $response = $this->getJson('/tags?filters[name][$eq]=nothing');

        $response->assertOk();
        $response->assertJsonCount(0);
    }

    /** @test */
    public function it_can_filter_with_a_basic_eq_operator(): void
    {
        $response = $this->getJson('/tags?filters[name][$eq]=laravel');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_process_custom_operator1(): void
    {
        $response = $this->getJson('/tags?filters[name][$pure]=true');

        $response->assertOk();
        $response->assertJsonCount(1); // since we have 1 tag with name 'pure_tag'
    }

    /** @test */
    public function it_can_process_with_grouped_filters()
    {
        $originalSilentMode = $this->app['config']->get('purity.silent');
        $this->app['config']->set('purity.silent', false);

        $newPost = Post::query()->create(['title' => 'title']);
        $newTag = Tag::query()->create(['name' => 'tag']);
        $newPost->tags()->save($newTag);

        $response = $this->getJson('/tags?filters[$or][0][name][$eq]=tag&filters[$or][1][name][$eq]=tags&filters[posts][title][$eq]=title');
        $response
          ->assertOk()
          ->assertJsonCount(1);

        assertEquals('tag', $response->json()[0]['name']);

        $this->app['config']->set('purity.silent', $originalSilentMode);
    }
}
