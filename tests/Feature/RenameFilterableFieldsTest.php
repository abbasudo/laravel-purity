<?php

use Abbasudo\Purity\Tests\Models\Book;
use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\assertEquals;

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
        $post->renamedFilterFields = ['title' => 'post_title'];

        assertEquals('title', $post->getField('post_title'));
    }

    /** @test */
    public function it_can_process_without_renamed_fields(): void
    {
        Post::create([
            'title' => 'this is renamed field',
        ]);

        Route::get('/posts', function () {
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
        $post->renamedFilterFields = ['title' => 'post_title'];

        $post->create([
            'title' => 'title_1',
        ])->create([
            'title' => 'title_2',
        ]);

        Route::get('/posts', function () use ($post) {
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
        $post->renamedFilterFields = ['invalid_column' => 'post_title']; // invalid column name

        $post->create([
            'title' => 'title_1',
        ])->create([
            'title' => 'title_2',
        ]);

        Route::get('/posts', function () use ($post) {
            // reset with valid column name
            return $post->renamedFilterFields(['title' => 'post_title'])->filter()->get();
        });

        $response = $this->getJson('/posts?filters[post_title][$eq]=title_1');

        $response->assertOk();
        // It returns o records as builder level filters take priority
        $response->assertJsonCount(1);
    }

    /** @test */
    public function available_filter_fields_work_with_renamed_filter_fields(): void
    {
        $book = new Book();

        $book->create([
            'name'        => 'name_1',
            'description' => 'description_1',
        ])->create([
            'name'        => 'name_1',
            'description' => 'description_2',
        ]);

        Route::get('/books', function () use ($book) {
            return $book::filterFields(['book_title', 'description'])
                ->renamedFilterFields(['name' => 'book_title'])
                ->filter()
                ->get();
        });

        $response = $this->getJson('/books?filters[description][$eq]=description_1');

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    /** @test */
    public function rename_filter_fields_works_when_filter_fields_not_set(): void
    {
        $post = new Post();

        $post->create([
            'title' => 'title_1',
        ])->create([
            'title' => 'title_2',
        ]);

        Route::get('/posts', function () use ($post) {
            // reset with valid column name
            return $post->renamedFilterFields(['title' => 'post_title'])->filter()->get();
        });

        $response = $this->getJson('/posts?filters[post_title][$eq]=title_1');

        $response->assertOk();
        $response->assertJsonCount(1);
    }
}
