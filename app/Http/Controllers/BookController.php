<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = Book::latest()
            ->when($request->has('title'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->query('title') . '%');
            })
            ->when($request->has('author'), function ($query) use ($request) {
                $query->whereHas('authors', function ($query) use ($request) {
                    $query->where('fk_author', $request->query('author'));
                });
            })
            ->when($request->has('category'), function ($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('fk_category', $request->query('category'));
                });
            })
            ->get(['id', 'title', 'created_at']);

        return $records;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'authors' => 'required|array',
            'categories' => 'required|array',
            'title' => 'required|unique:books,title',
            'synopsis' => 'required|string',
        ]);

        $record = Book::create([
            'fk_created_by' => auth()->user()->id,
            'title' => $request->input('title'),
            'synopsis' => $request->input('synopsis'),
        ]);

        $record->authors()->attach($request->input('authors'));

        $record->categories()->attach($request->input('categories'));

        return $record;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return $book->load([
            'authors' => function ($query) {
                $query->select('authors.id', 'name');
            },
            'categories' => function ($query) {
                $query->select('categories.id', 'name');
            },
            'createdBy' => function ($query) {
                $query->select('id', 'name');
            },
            'updatedBy' => function ($query) {
                $query->select('id', 'name');
            },
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'authors' => 'required|array',
            'categories' => 'required|array',
            'title' => 'required|unique:books,title,' . $book->id,
            'synopsis' => 'required|string',
        ]);

        $book->update([
            'fk_updated_by' => auth()->user()->id,
            'title' => $request->input('title'),
            'synopsis' => $request->input('synopsis'),
        ]);

        $book->authors()->attach($request->input('authors'));

        $book->categories()->attach($request->input('categories'));

        return $book;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->formats()->each(function ($format) {
            $format->delete();
        });

        $book->delete();

        return response([], 204);
    }
}
