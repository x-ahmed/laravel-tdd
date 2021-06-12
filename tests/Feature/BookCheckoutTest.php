<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * a_book_can_be_checked_out_by_a_signed_in_user
     *
     * @test
     */
    public function a_book_can_be_checked_out_by_a_signed_in_user()
    {
        $book = Book::factory()->create();
        $this->actingAs($user = User::factory()->create())
            ->post("checkout/{$book->id}");

        $this->assertCount(1, Reservation::all());

        $reservation = Reservation::first();
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(now(), $reservation->checked_out_at);
        $this->assertNull($reservation->checked_in_at);
    }

    /**
     * only_signed_in_users_can_checkout_a_book
     *
     * @test
     */
    public function only_signed_in_users_can_checkout_a_book()
    {
        $book = Book::factory()->create();
        $this->post("checkout/{$book->id}")
            ->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());
    }

    /**
     * only_db_existed_books_can_be_checked_out
     *
     * @test
     */
    public function only_db_existed_books_can_be_checked_out()
    {
        $this->actingAs(User::factory()->create())->post('/checkout/0')
            ->assertNotFound();

        $this->assertCount(0, Reservation::all());
    }

    /**
     * a_book_can_be_checked_in_by_a_signed_in_user
     *
     * @test
     */
    public function a_book_can_be_checked_in_by_a_signed_in_user()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("checkout/{$book->id}");
        $this->actingAs($user)
            ->post("checkin/{$book->id}");

        $this->assertCount(1, Reservation::all());

        $reservation = Reservation::first();
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(now(), $reservation->checked_out_at);
        $this->assertEquals(now(), $reservation->checked_in_at);
        $this->assertNotNull($reservation->checked_in_at);
    }

    /**
     * only_signed_in_users_can_checkin_a_book
     *
     * @test
     */
    public function only_signed_in_users_can_checkin_a_book()
    {
        $book = Book::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post("checkout/{$book->id}");

        Auth::logout();

        $this->post("checkin/{$book->id}")
            ->assertRedirect('/login');

        $this->assertCount(1, Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }

    /**
     * only_db_existed_books_can_be_checked_in
     *
     * @test
     */
    public function only_db_existed_books_can_be_checked_in()
    {
        $this->actingAs(User::factory()->create())
            ->post('/checkin/0')
            ->assertNotFound();

        $this->assertCount(0, Reservation::all());
    }

    /**
     * a_404_will_be_thrown_if_the_checked_in_book_did_not_checked_out_first
     *
     * @test
     */
    public function a_404_will_be_thrown_if_the_checked_in_book_did_not_checked_out_first()
    {
        $book = Book::factory()->create();
        $this->actingAs($user = User::factory()->create())
            ->post("checkin/{$book->id}")
            ->assertNotFound();

        $this->assertCount(0, Reservation::all());
    }
}
