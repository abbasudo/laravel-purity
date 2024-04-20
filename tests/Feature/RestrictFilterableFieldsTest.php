<?php

use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertStringContainsString;

class RestrictFilterableFieldsTest extends TestCase
{
    /** @test */
    public function it_return_all_available_fields_when_filter_fields_not_defined(): void
    {
        $post = new Post();

        $availableFields = $post->availableFields();
        $expectedFields = ['title', 'created_at', 'updated_at'];

        foreach ($expectedFields as $field) {
            assertTrue(in_array($field, $availableFields));
        }
    }

    /** @test */
    public function it_return_all_available_fields_when_filter_fields_defined(): void
    {
        $post = new Post();
        $post->filterFields = [
            'title :$gt, $lt',
            'status',
            'description' => ['$ne', '$ecs'],
            'rank'        => '$eq',
        ];

        $availableFields = $post->availableFields();

        assertEquals([
            'title',
            'status',
            'description',
            'rank',
        ], $availableFields);
    }

    /** @test */
    public function it_can_return_restricted_filters_when_defined_inside_filter_fields()
    {
        $post = new Post();
        $post->filterFields = [
            'title : $gt,$lt',
            'status',
            'description' => ['$ne', '$ecs'],
            'rank'        => '$eq',
        ];

        $restrictedFilters = $post->getRestrictedFilters();

        assertEquals([
            'title'       => ['$gt', '$lt'],
            'description' => ['$ne', '$ecs'],
            'rank'        => ['$eq'],
        ], $restrictedFilters);
    }

    /** @test */
    public function it_gives_priority_to_restricted_filters_property_when_defined_to_return_restricted_filters()
    {
        $post = new Post();
        $post->filterFields = [
            'title : $gt,$lt',
            'status',
            'description' => ['$ne', '$ecs'],
            'rank'        => '$eq',
        ];

        $post->restrictedFilters = [
            'title : $gte,$lte',
            'status',
            'description' => ['$ne'],
            'rank'        => ['$eq', 'lt'],
        ];

        $restrictedFilters = $post->getRestrictedFilters();

        assertEquals([
            'title'       => ['$gte', '$lte'],
            'description' => ['$ne'],
            'rank'        => ['$eq', 'lt'],
        ], $restrictedFilters);
    }

    /** @test */
    public function it_return_empty_array_when_no_restricted_property_is_defined()
    {
        $post = new Post();
        $post->filterFields = [
            'title',
            'status',
            'description',
            'rank',
        ];

        $restrictedFilters = $post->getRestrictedFilters();

        assertEquals([], $restrictedFilters);
    }

    /** @test */
    public function it_can_process_a_request_without_restricted_filters(): void
    {
        Route::get('/posts', function () {
            return Post::filter()->get();
        });

        Post::create([
            'title' => 'laravel purity is the best',
        ]);

        $response = $this->getJson('/posts?filters[title][$eq]=no matches');

        $response->assertOk();
        $response->assertJsonCount(0);
    }

    /** @test */
    public function it_can_process_when_a_valid_filter_is_applied(): void
    {
        $post = new Post();
        $post->restrictedFilters = ['title' => ['$eq']];

        $post->create([
            'title' => 'this is valid operator',
        ]);

        Route::get('/posts', function () use ($post) {
            return $post->filter()->get();
        });

        $response = $this->getJson('/posts?filters[title][$eq]=no matches');

        $response->assertOk();
        $response->assertJsonCount(0);
    }

    /** @test */
    public function it_can_avoid_when_invalid_filter_is_applied(): void
    {
        $post = new Post();
        $post->restrictedFilters = ['title' => ['$ecq']];

        $post->create([
            'title' => 'this is invalid operator',
        ]);

        Route::get('/posts', function () use ($post) {
            return $post->filter()->get();
        });

        $response = $this->getJson('/posts?filters[title][$eq]=no matches');

        $response->assertOk();
        // it must return all the post as $eq operator is omitted
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_throws_error_when_invalid_filter_is_applied_in_non_silent_mode(): void
    {
        $originalSilentMode = $this->app['config']->get('purity.silent');
        $this->app['config']->set('purity.silent', false);

        $post = new Post();
        $post->restrictedFilters = ['title' => ['$ecq']];

        $post->create([
            'title' => 'this is invalid operator',
        ]);

        Route::get('/posts', function () use ($post) {
            return $post->filter()->get();
        });

        $response = $this->getJson('/posts?filters[title][$eq]=no matches');
        $content = $response->getOriginalContent();

        $response->assertServerError();

        assertStringContainsString('OperatorNotSupported', $content['exception']);
        assertStringContainsString('$eq', $content['message']);
        assertStringContainsString('$ecq', $content['message']);

        $this->app['config']->set('purity.silent', $originalSilentMode);
    }

    /** @test */
    public function it_can_set_restricted_filters_on_eloquent_builder(): void
    {
        $post = new Post();
        $post->restrictedFilters = ['title' => ['$ecq']];

        $post->create([
            'title' => 'this is invalid operator',
        ]);

        Route::get('/posts', function () use ($post) {
            return $post->restrictedFilters(
                ['title' => ['$eq']]
            )->filter()->get();
        });

        $response = $this->getJson('/posts?filters[title][$eq]=no matches');

        $response->assertOk();
        // It returns o records as builder level filters take priority
        $response->assertJsonCount(0);
    }
}
