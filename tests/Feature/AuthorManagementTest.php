<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Author;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * an_author_can_be_created
     *
     * @test
     * @return void
     */
    public function an_author_can_be_created()
    {
        $response = $this->post('/author', [
            'name' => 'Author Name',
            'dob' => '05/14/1988',
        ]);

        $authors = Author::all();

        $this->assertCount(1, $authors);
        $this->assertInstanceOf(Carbon::class, $authors->first()->dob);
        $this->assertEquals('1988/14/05', $authors->first()->dob->format('Y/d/m'));
    }
}
