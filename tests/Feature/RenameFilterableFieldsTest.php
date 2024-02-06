<?php

use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class RenameFilterableFieldsTest extends TestCase
{
    public function it_can_return_relevant_table_column(): void
    {
        $post = new Post();
        assertEquals('title', $post->getField('title'));
    }

    /** @test */
    public function it_can_return_relevant_table_column_after_renamed(): void
    {
        $post = new Post();
        $post->renamedFilterFields = ['post_title' => 'title'];

        assertEquals('title', $post->getField('post_title'));
    }

    /** @test */
    public function it_can_process_without_renamed_fields(): void
    {
        Post::create([
            'title' => 'this is renamed field',
        ]);

        Route::get('/posts', function (){
            return Post::filter()->get();
        });

        $response = $this->getJson('/posts?filters[title][$eq]=no matches');

        $response->assertOk();
        // It returns o records as builder level filters take priority
        $response->assertJsonCount(0);
    }

    /** @test */
    public function it_can_process_with_renamed_fields(): void
    {
        $post = new Post();
        $post->filterFields = ['post_title'];
        $post->renamedFilterFields = ['post_title' => 'title'];

        $post->create([
            'title' => 'title_1',
        ])->create([
            'title' => 'title_2',
        ]);

        Route::get('/posts', function () use($post) {
            return $post->filter()->get();
        });

        $response = $this->getJson('/posts?filters[post_title][$eq]=title_1');

        $response->assertOk();
        // It returns o records as builder level filters take priority
        $response->assertJsonCount(1);
    }

    /** @test */
    public function it_can_overwrite_rename_filter_fields_in_eloquent_builder(): void
    {
        $post = new Post();
        $post->filterFields = ['post_title'];
        $post->renamedFilterFields = ['post_title' => 'invalid_column']; // invalid column name

        $post->create([
            'title' => 'title_1',
        ])->create([
            'title' => 'title_2',
        ]);

        Route::get('/posts', function () use($post) {
            // reset with valid column name
            return $post->renamedFilterFields(['post_title' => 'title'])->filter()->get();
        });

        $response = $this->getJson('/posts?filters[post_title][$eq]=title_1');

        $response->assertOk();
        // It returns o records as builder level filters take priority
        $response->assertJsonCount(1);
    }

}

