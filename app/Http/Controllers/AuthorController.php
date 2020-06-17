<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Author::get(['id', 'name', 'avatar', 'created_at']);

        return $records;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:authors,name'
            ],
            'avatar' => [
                'nullable',
                'image'
            ]
        ]);

        $record = Author::create([
            'fk_created_by' => 1,
            'name' => $request->input('name'),
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('images/authors', 'public');
            $record->update(['avatar' => $path]);
        }

        return $record;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return $author->load([
            'createdBy' => function($query) {
                $query->select('id', 'name');
            },
            'updatedBy' => function($query) {
                $query->select('id', 'name');
            },
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:authors,name,' . $author->id,
            ],
            'avatar' => [
                'nullable',
                'image'
            ]
        ]);

        $author->update([
            'fk_updated_by' => 1,
            'name' => $request->input('name'),
        ]);

        if ($request->hasFile('avatar')) {
            Storage::disk('public')->delete($author->avatar);

            $path = $request->file('avatar')->store('images/authors', 'public');
            $author->update(['avatar' => $path]);
        }

        return $author;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        Storage::disk('public')->delete($author->avatar);

        $author->delete();

        return response([], 204);
    }
}
