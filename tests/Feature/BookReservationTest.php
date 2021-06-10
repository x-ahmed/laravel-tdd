<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();
    }

    /**
     * a_book_can_be_added_to_the_library
     *
     * @test void
     */
    public function a_book_can_be_added_to_the_library(): void
    {
        $response = $this->post('/book', $this->validData());

        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /**
     * a_title_is_required
     *
     * @test void
     */
    public function a_title_is_required():void
    {
        $response = $this->postNewBookWithEmptyValueFor('title');
        $response->assertSessionHasErrors('title');
    }

    /**
     * a_author_is_required
     *
     * @test void
     */
    public function a_author_is_required():void
    {
        $response = $this->postNewBookWithEmptyValueFor('author');
        $response->assertSessionHasErrors('author');
    }

    /**
     * a_book_can_be_updated
     *
     * @test void
     */
    public function a_book_can_be_updated(): void
    {
        $this->post('/book', $this->validData());

        $book = Book::first();

        $response = $this->patch("/book/{$book->id}", [
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        $response->assertOk();
        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
    }

    /**
     * validData
     *
     * @return array
     */
    private function validData(): array
    {
        return [
            'title' => 'Cool Book',
            'author' => 'Ahmed',
        ];
    }

    /**
     * postNewBookWithInValidData
     *
     * @param  string $attribute
     * @return TestResponse
     */
    private function postNewBookWithEmptyValueFor(string $attribute): TestResponse
    {
        return $this->post(
            '/book',
            \array_merge($this->validData(), [$attribute => ''])
        );
    }
}
