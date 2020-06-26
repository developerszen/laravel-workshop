<?php

namespace App\Http\Controllers;

use App\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = Format::with([
            'book' => function ($query) {
                $query->select('id', 'title');
            },
            'editorial' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->when($request->has('edition'), function ($query) use ($request) {
                $query->where('edition', 'like', '%' . $request->query('edition') . '%');
            })
            ->when($request->has('book'), function ($query) use ($request) {
                $query->where('fk_book', $request->query('book'));
            })
            ->when($request->has('editorial'), function ($query) use ($request) {
                $query->where('fk_editorial', $request->query('editorial'));
            })
            ->get(['id', 'fk_book', 'fk_editorial', 'edition', 'price']);

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
            'fk_book' => 'required|exists:books,id',
            'fk_editorial' => 'required|exists:editorials,id',
            'edition' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image'
        ]);

        $record = Format::create([
            'fk_created_by' => auth()->user()->id,
            'fk_book' => $request->input('fk_book'),
            'fk_editorial' => $request->input('fk_editorial'),
            'edition' => $request->input('edition'),
            'price' => $request->input('price'),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/formats', 'public');
            $record->update(['image' => $path]);
        }

        return $record;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Format $format)
    {
        return $format
            ->load([
                'createdBy' => function($query) {
                    $query->select('id', 'name');
                },
                'updatedBy' => function($query) {
                    $query->select('id', 'name');
                },
                'book' => function($query) {
                    $query->select('id', 'title');
                },
                'editorial' => function($query) {
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
    public function update(Request $request, Format $format)
    {
        $request->validate([
            'fk_book' => 'required|exists:books,id',
            'fk_editorial' => 'required|exists:editorials,id',
            'edition' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image'
        ]);

        $format->update([
            'fk_updated_by' => auth()->user()->id,
            'fk_book' => $request->input('fk_book'),
            'fk_editorial' => $request->input('fk_editorial'),
            'edition' => $request->input('edition'),
            'price' => $request->input('price'),
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($format->image);

            $path = $request->file('image')->store('images/formats', 'public');
            $format->update(['image' => $path]);
        }

        return $format;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Format $format)
    {
        $format->delete();

        return response([], 204);
    }
}
