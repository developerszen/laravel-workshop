<?php

namespace Tests\Feature;

use App\Book;
use App\Format;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use DatabaseTransactions;
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

    function testUnauthorizedUsersCannotGetListBooks()
    {
        $this->getJson(route('books.index'))->assertUnauthorized();
    }

    function testCreateBook() {
        $this->withoutExceptionHandling();

        $payload = [
            'title' => 'New Book title 2',
            'synopsis' => 'Synopsis book',
            'authors' => [1, 2],
            'categories' => [4],
        ];

        $response =  $this->withHeaders($this->headers())
            ->postJson(route('books.store'), $payload)
            ->assertCreated();

        $book = $response->getOriginalContent();

        $this->assertDatabaseHas('books', [
           'title' =>  $book->title,
        ]);

        $this->assertDatabaseHas('author_book', [
            'fk_book' => $book->id,
            'fk_author' => 1,
        ]);

        $this->assertDatabaseHas('author_book', [
            'fk_book' => $book->id,
            'fk_author' => 2,
        ]);

        $this->assertDatabaseHas('book_category', [
            'fk_book' => $book->id,
            'fk_category' => 4,
        ]);
    }

    function testCreateBookWithoutTitle() {
        $payload = [
            'title' => '',
            'synopsis' => 'Synopsis book',
            'authors' => [1, 2],
            'categories' => [4],
        ];

        $this->withHeaders($this->headers())
            ->postJson(route('books.store'), $payload)
            ->assertJsonValidationErrors(['title'])
            ->assertStatus(422);
    }

    function testGetBookDetail()
    {
        $this->withoutExceptionHandling();
        $book = Book::find(1);

        $this->withHeaders($this->headers())
            ->json('get', route('books.show', $book->id))
            ->assertOk();
    }

    function testUpdateBook() {
        $this->withoutExceptionHandling();

        $book = Book::find(1);

        $payload = [
            'title' => 'Update Book',
            'synopsis' => 'Synopsis update',
            'authors' => [2],
            'categories' => [1, 4],
        ];

        $response =  $this->withHeaders($this->headers())
            ->putJson(route('books.update', $book->id), $payload)
            ->assertOk();

        $book = $response->getOriginalContent();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' =>  $book->title,
        ]);
    }

    function testDeleteBook()
    {
        $book = Book::create([
            'fk_created_by' => 1,
            'title' => 'New title',
            'synopsis' => 'New Synopsis'
        ]);

        $format = Format::find(1);

        $book->formats()->save($format);

        $this->withHeaders($this->headers())
            ->deleteJson(route('books.destroy', $book->id))
            ->assertNoContent();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'deleted_at' => now(),
        ]);

        $this->assertDatabaseHas('formats', [
            'id' => $format->id,
            'deleted_at' => now(),
        ]);
    }
}
