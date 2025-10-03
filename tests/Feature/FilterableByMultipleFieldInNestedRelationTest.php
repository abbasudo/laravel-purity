<?php

namespace Abbasudo\Purity\Tests\Feature;

use Abbasudo\Purity\Tests\App\Models\User;
use Abbasudo\Purity\Tests\App\Models\Post;
use Abbasudo\Purity\Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class FilterableByMultipleFieldInNestedRelationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // user
        $user = User::create([
            'name' => 'Alice',
        ]);
        // post
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'title'
        ])->comments()->create([
            'content' => 'comment',
            'is_approved' => true,
        ]);
    }

    /** @test */
    public function it_can_filter_by_multiple_fields_in_nested_relation(): void
    {
        $originalSilentMode = $this->app['config']->get('purity.silent');
        $this->app['config']->set('purity.silent', false);

        $filters = [
            'post' => [
                'comments' => [
                    'content' => [
                        '$contains' => [
                            'comment',
                        ],
                    ],
                    'is_approved' => [
                        '$eq' => true,
                    ],
                ],
            ]
        ];

        $results = User::with(['post.comments'])
            ->filter($filters)
            ->get();

        assertEquals(1, $results->count());

        $this->app['config']->set('purity.silent', $originalSilentMode);
    }
}
