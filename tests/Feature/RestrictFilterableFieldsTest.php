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

    /** @test */
    public function it_can_return_restricted_filters_when_defined_inside_filter_fields()
    {
        $post = new Post();
        $post->filterFields = [
            'title : $gt,$lt',
            'status',
            'description' => ['$ne', '$ecs'],
            'rank' => '$eq',
        ];

        $restrictedFilters = $post->getRestrictedFilters();

        assertEquals([
            'title' => ['$gt', '$lt'],
            'description' => ['$ne', '$ecs'],
            'rank' => ['$eq'],
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
            'rank' => '$eq',
        ];

        $post->restrictedFilters = [
            'title : $gte,$lte',
            'status',
            'description' => ['$ne'],
            'rank' => ['$eq', 'lt'],
        ];

        $restrictedFilters = $post->getRestrictedFilters();

        assertEquals([
            'title' => ['$gte', '$lte'],
            'description' => ['$ne'],
            'rank' => ['$eq', 'lt'],
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
}

