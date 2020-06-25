<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BooksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    function testGetListBooks()
    {
        $this->withHeaders($this->headers())
            ->json('get', route('books.index'))
            ->assertJsonCount(10)
            ->assertOk();
    }

    function testCreateBook() {
        $this->withoutExceptionHandling();

        $payload = [
            'title' => 'New Book title',
            'synopsis' => 'Synopsis book',
            'authors' => [1, 2],
            'categories' => [4],
        ];

        $response =  $this->withHeaders($this->headers())
            ->postJson(route('books.store'), $payload)
            ->assertCreated();
    }

    function testGetBookDetail()
    {
        $this->withoutExceptionHandling();
        $book = Book::find(1);

        $this->withHeaders($this->headers())
            ->json('get', route('books.show', $book->id))
            ->assertOk();
    }
}
