<?php

namespace App\Http\Controllers;

use App\Editorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EditorialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = Editorial::latest()
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('name') .'%');
            })
            ->get(['id', 'name', 'image', 'created_at']);

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
                Rule::unique('editorials')->where(function ($query) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'image' => [
                'nullable',
                'image'
            ]
        ]);

        $record = Editorial::create([
            'fk_created_by' => auth()->user()->id,
            'name' => $request->input('name'),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/editorials', 'public');
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
    public function show(Editorial $editorial)
    {
        return $editorial->load([
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
    public function update(Request $request, Editorial $editorial)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('editorials')
                    ->where(function ($query) {
                        return $query->where('deleted_at', null);
                    })
                    ->ignore($editorial->id),
            ],
            'image' => [
                'nullable',
                'image'
            ]
        ]);

        $editorial->update([
            'fk_updated_by' => auth()->user()->id,
            'name' => $request->input('name'),
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($editorial->avatar);

            $path = $request->file('image')->store('images/editorials', 'public');
            $editorial->update(['image' => $path]);
        }

        return $editorial;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Editorial $editorial)
    {
        Storage::disk('public')->delete($editorial);

        $editorial->delete();

        return response([], 204);
    }
}
