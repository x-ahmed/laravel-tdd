<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BookManagementTest extends TestCase
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
     * @test
     * @return void
     */
    public function a_book_can_be_added_to_the_library(): void
    {
        $response = $this->post(route('book.store'), $this->validData());
        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->show_route);
    }

    /**
     * a_title_is_required
     *
     * @test
     * @return void
     */
    public function a_title_is_required(): void
    {
        $response = $this->postNewBookWithEmptyValueFor('title');
        $response->assertSessionHasErrors('title');
    }

    /**
     * a_author_is_required
     *
     * @test
     * @return void
     */
    public function a_author_is_required(): void
    {
        $response = $this->postNewBookWithEmptyValueFor('author');
        $response->assertSessionHasErrors('author');
    }

    /**
     * a_book_can_be_updated
     *
     * @test
     * @return void
     */
    public function a_book_can_be_updated(): void
    {
        $this->post(route('book.store'), $this->validData());

        $book = Book::first();

        $response = $this->patch($book->update_route, [
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
        $response->assertRedirect($book->fresh()->show_route);
    }

    /**
     * a_book_can_be_deleted
     *
     * @test
     * @return void
     */
    public function a_book_can_be_deleted()
    {
        $this->post(route('book.store'), $this->validData());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->destroy_route);

        $this->assertCount(0, Book::all());
        $response->assertRedirect(route('book.index'));
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
            route('book.store'),
            \array_merge($this->validData(), [$attribute => ''])
        );
    }
}
