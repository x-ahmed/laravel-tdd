<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * an_author_id_is_required
     *
     * @test
     */
    public function an_author_id_is_required()
    {
        Book::create([
            'title' => 'Book Title',
            'author_id' => 1,
        ]);

        $this->assertCount(1, Book::all());
    }
}
