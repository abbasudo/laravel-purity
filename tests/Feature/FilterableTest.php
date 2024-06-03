<?php

use Abbasudo\Purity\Tests\App\Models\Comment;
use Abbasudo\Purity\Tests\App\Models\Post;
use Abbasudo\Purity\Tests\App\Models\Product;
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

        Route::get('/products', function () {
            return Product::filter()->get();
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
        $post    = Post::query()->create(['title' => 'title']);
        $comment = Comment::query()->create(['content' => 'comment']);

        $post->comments()->save($comment);

        $response = $this->getJson('/posts?filters[$or][0][title][$eq]=title&filters[comments][content][$eq]=comment')
            ->assertOk()
            ->assertJsonCount(1);

        assertEquals('title', $response->json()[0]['title']);
    }

    /** @test */
    public function it_can_filter_by_decimal_values_with_results()
    {
        $product = Product::factory()->create([
            'price' => 1.66,
        ]);

        $response = $this->getJson('/products?filters[price][$eq]=1.66')
            ->assertOk()
            ->assertJsonCount(1);

        assertEquals($product->id, $response->json()[0]['id']);
    }

    /** @test */
    public function it_can_filter_by_decimal_values_without_matches()
    {
        $product = Product::factory()->create([
            'price' => 1.66,
        ]);

        $response = $this->getJson('/products?filters[price][$eq]=1.47')
            ->assertOk()
            ->assertJsonCount(0);
    }

    /** @test */
    public function it_can_filter_by_float_values_with_results()
    {
        $product = Product::factory()->create([
            'rate' => 859.77,
        ]);

        $response = $this->getJson('/products?filters[rate][$eq]=859.77')
            ->assertOk()
            ->assertJsonCount(1);

        assertEquals($product->id, $response->json()[0]['id']);
    }

    /** @test */
    public function it_can_filter_by_float_values_without_matches()
    {
        $product = Product::factory()->create([
            'rate' => 49.79,
        ]);

        $response = $this->getJson('/products?filters[rate][$eq]=458.57')
            ->assertOk()
            ->assertJsonCount(0);
    }

    /** @test */
    public function it_can_filter_by_boolean_values_with_results()
    {
        $product = Product::factory()->create([
            'is_available' => true,
        ]);

        $response = $this->getJson('/products?filters[is_available][$eq]=1')
            ->assertOk()
            ->assertJsonCount(1);

        assertEquals($product->id, $response->json()[0]['id']);
    }

    /** @test */
    public function it_can_filter_by_boolean_values_without_matches()
    {
        $product = Product::factory()->create([
            'is_available' => true,
        ]);

        $response = $this->getJson('/products?filters[is_available][$eq]=0')
            ->assertOk()
            ->assertJsonCount(0);
    }

    /** @test */
    public function it_can_filter_with_eqc_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$eqc]=laravel purity is the best');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_ne_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$ne]=no matches');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_lt_operator(): void
    {
        $product = Product::factory()->create([
            'price' => 1.66,
        ]);

        $response = $this->getJson('/products?filters[price][$lt]=2.00')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_lte_operator(): void
    {
        $product = Product::factory()->create([
            'price' => 1.66,
        ]);

        $response = $this->getJson('/products?filters[price][$lte]=1.66')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_gt_operator(): void
    {
        $product = Product::factory()->create([
            'price' => 3.50,
        ]);

        $response = $this->getJson('/products?filters[price][$gt]=2.00')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_gte_operator(): void
    {
        $product = Product::factory()->create([
            'price' => 3.50,
        ]);

        $response = $this->getJson('/products?filters[price][$gte]=3.50')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_in_operator(): void
    {
        Product::factory()->create([
            'price' => 1.66,
        ]);

        Product::factory()->create([
            'price' => 2.50,
        ]);

        Product::factory()->create([
            'price' => 3.50,
        ]);

        $response = $this->getJson('/products?filters[price][$in][0]=1.66&filters[price][$in][1]=2.50')
            ->assertOk()
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_can_filter_with_notIn_operator(): void
    {
        $product = Product::factory()->create([
            'price' => 1.66,
        ]);

        $response = $this->getJson('/products?filters[price][$notIn]=[2.50]')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_contains_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$contains]=LARAVEL');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_notContains_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$notContains]=complexity');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_containsc_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$containsc]=lara');
        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_notContainsc_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$notContainsc]=complexity');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_null_operator(): void
    {
        $product = Product::factory()->create([
            'description' => null,
        ]);

        $response = $this->getJson('/products?filters[description][$null]=true')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_notNull_operator(): void
    {
        $product = Product::factory()->create([
            'description' => 'A great product',
        ]);

        $response = $this->getJson('/products?filters[description][$notNull]=true')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_between_operator(): void
    {
        $product = Product::factory()->create([
            'price' => 2.50,
        ]);

        $response = $this->getJson('/products?filters[price][$between][0]=1&filters[price][$between][1]=3')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_notBetween_operator(): void
    {
        $product = Product::factory()->create([
            'price' => 1.50,
        ]);

        $response = $this->getJson('/products?filters[price][$notBetween]=[2,3]')
            ->assertOk()
            ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_startsWith_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$startsWith]=laravel');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_startsWithc_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$startsWithc]=Laravel');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_endsWith_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$endsWith]=best');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_endsWithc_operator(): void
    {
        $response = $this->getJson('/posts?filters[title][$endsWithc]=best');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_filter_with_or_operator(): void
    {
        $post1 = Post::create(['title' => 'laravel purity']);
        $post2 = Post::create(['title' => 'laravel eloquent']);

        $response = $this->getJson(
            '/posts?filters[$or][0][title][$eq]=laravel purity&filters[$or][1][title][$eq]=laravel eloquent'
        )
            ->assertOk()
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_can_filter_with_and_operator(): void
    {
        $post = Product::factory()->create([
            'name'        => 'laravel purity',
            'description' => 'laravel purity is the best'
        ]);

        $response = $this->getJson(
            '/posts?filters[$and][0][name][$eq]=laravel purity is the best&filters[$and][1][description][$eq]=laravel purity is the best'
        )
            ->assertOk()
            ->assertJsonCount(1);
    }
}
