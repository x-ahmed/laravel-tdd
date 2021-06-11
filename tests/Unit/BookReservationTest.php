<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function a_book_can_be_checked_out()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);

        $this->assertCount(1, Reservation::all());

        $reservation = Reservation::first();
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(now(), $reservation->checked_out_at);
        $this->assertNull($reservation->checked_in_at);
    }

    /**
     * a_book_can_be_checked_in
     *
     * @test
     */
    public function a_book_can_be_checked_in()
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);
        $book->checkin($user);

        $this->assertCount(1, Reservation::all());

        $reservation = Reservation::first();
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNotNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_in_at);
    }

    /**
     * a_user_can_check_out_a_book_twice
     *
     * @test
     */
    public function a_user_can_check_out_a_book_twice()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);
        $book->checkin($user);
        $book->checkout($user);

        $this->assertCount(2, Reservation::all());

        $reservation = Reservation::find(2);
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_out_at);

        $book->checkin($user);

        $reservation = Reservation::find(2);
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNotNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_in_at);
    }

    /**
     * if_not_checked_out_an_exception_is_thrown
     *
     * @test
     */
    public function if_not_checked_out_an_exception_is_thrown()
    {
        $this->expectException(\Exception::class);

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkin($user);
    }
}
