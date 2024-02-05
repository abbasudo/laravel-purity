<?php

use Abbasudo\Purity\Tests\Models\Post;
use Abbasudo\Purity\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

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
            'rank' => '$eq',
        ];

        $availableFields = $post->availableFields();

        assertEquals([
            'title',
            'status',
            'description',
            'rank',
        ], $availableFields);
    }
}

