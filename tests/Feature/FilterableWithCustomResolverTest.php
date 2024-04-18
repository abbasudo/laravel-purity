<?php

use Abbasudo\Purity\Tests\Models\Comment;
use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\Models\DummyTag;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\assertEquals;

class FilterableWithCustomResolverTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();

    Route::get('/dummyTags', function () {
      return DummyTag::with('posts')->filter()->get();
    });

    DummyTag::create([
      'name' => 'laravel'
    ])
    ->posts()->create([
      'title' => 'laravel is the best',
    ]);

    DummyTag::create([
      'name' => 'purity'
    ])->posts()->create([
      'title' => 'purity is great',
    ]);
  }

  /** @test */
  public function it_can_process_a_basic_request_without_any_filter(): void
  {
    $response = $this->getJson('/dummyTags');

    $response->assertOk();
    $response->assertJsonCount(2);
  }

  /** @test */
  public function it_can_process_a_request_without_any_matches(): void
  {
    $response = $this->getJson('/dummyTags?filters[name][$eq]=nothing');

    $response->assertOk();
    $response->assertJsonCount(0);
  }

  /** @test */
  public function it_can_filter_with_a_basic_eq_operator(): void
  {
    $response = $this->getJson('/dummyTags?filters[name][$eq]=purity');

    $response->assertOk();
    $response->assertJsonCount(1);
  }

  /** @test */
  public function it_can_process_custom_operator(): void
  {
    $response = $this->getJson('/dummyTags?filters[name][$customOp]=ignore');

    $response->assertOk();
    $response->assertJsonCount(2);
  }

  /** @test */
  public function it_can_process_with_grouped_filters()
  {
    $post = Post::query()->create(['title' => 'title']);
    $dummyTag = DummyTag::query()->create(['name' => 'tag']);
    $dummyTag->posts()->save($post);

    $response = $this->getJson('/dummyTags?filters[$or][0][name][$eq]=tag&filters[posts][title][$eq]=title');
    $response
      ->assertOk()
      ->assertJsonCount(1);

    assertEquals('tag', $response->json()[0]['name']);
  }
}
