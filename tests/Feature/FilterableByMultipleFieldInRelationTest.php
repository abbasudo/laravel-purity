<?php

namespace Abbasudo\Purity\Tests\Feature;

use Abbasudo\Purity\Tests\Models\Author;
use Abbasudo\Purity\Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class FilterableByMultipleFieldInRelationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // author
        $author = Author::create([
            'name' => 'George Raymond Richard Martin',
        ]);

        // books
        $author->books()->create([
            'name'        => 'A Game of Thrones',
            'description' => 'A Game of Thrones is the first novel in A Song of Ice and Fire, a series of fantasy novels by the American author George R. R. Martin.',
        ]);
        $author->books()->create([
            'name'        => 'A Clash of Kings',
            'description' => 'A Clash of Kings is the second novel in A Song of Ice and Fire, a series of fantasy novels by the American author George R. R. Martin.',
        ]);
        $author->books()->create([
            'name'        => 'A Storm of Swords',
            'description' => 'A Storm of Swords is the third novel in A Song of Ice and Fire, a series of fantasy novels by the American author George R. R. Martin.',
        ]);
        $author->books()->create([
            'name'        => 'A Feast for Crows',
            'description' => 'A Feast for Crows is the fourth novel in A Song of Ice and Fire, a series of fantasy novels by the American author George R. R. Martin.',
        ]);
        $author->books()->create([
            'name'        => 'A Dance with Dragons',
            'description' => 'A Dance with Dragons is the fifth novel in A Song of Ice and Fire, a series of fantasy novels by the American author George R. R. Martin.',
        ]);
        $author->books()->create([
            'name'        => 'The Winds of Winter',
            'description' => 'The Winds of Winter is the planned sixth novel in the epic fantasy series A Song of Ice and Fire by American writer George R. R. Martin.',
        ]);
        $author->books()->create([
            'name'        => 'A Dream of Spring',
            'description' => 'A Dream of Spring is the planned seventh novel in the epic fantasy series A Song of Ice and Fire by American writer George R. R. Martin.',
        ]);

        // author
        $author = Author::create([
            'name' => 'J. R. R. Tolkien',
        ]);

        // books
        $author->books()->create([
            'name'        => 'The Hobbit',
            'description' => 'The Hobbit, or There and Back Again is a children\'s fantasy novel by English author J. R. R. Tolkien.',
        ]);
        $author->books()->create([
            'name'        => 'The Lord of the Rings',
            'description' => 'The Lord of the Rings is an epic high-fantasy novel by the English author and scholar J. R. R. Tolkien.',
        ]);
        $author->books()->create([
            'name'        => 'The Silmarillion',
            'description' => 'The Silmarillion is a collection of mythopoeic works by English writer J. R. R. Tolkien.',
        ]);
    }

    /** @test */
    public function it_can_filter_by_multiple_fields_in_relation(): void
    {
        $originalSilentMode = $this->app['config']->get('purity.silent');
        $this->app['config']->set('purity.silent', false);

        $filters = [
            'name'  => [
                '$contains' => [
                    'George',
                ],
            ],
            'books' => [
                'name'        => [
                    '$contains' => [
                        'Game',
                        'Thrones',
                    ],
                ],
                'description' => [
                    '$contains' => [
                        'Game',
                        'Thrones',
                    ],
                ],
            ],
        ];

        $results = Author::with(['books'])
            ->filter($filters)
            ->get();

        assertEquals(1, $results->count());

        $this->app['config']->set('purity.silent', $originalSilentMode);
    }
}
