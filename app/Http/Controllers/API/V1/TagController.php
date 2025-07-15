<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Requests\V1\StoreTagRequest;
use App\Http\Requests\V1\UpdateTagRequest;
use App\Http\Resources\V1\TagCollection;
use App\Http\Resources\V1\TagResources;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
        abort(403, 'Unauthorized');
        }

        $tag = Tag::all();
        return new TagCollection($tag);

        // return Tag::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        return new TagResources(Tag::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return new TagResources($tag);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $user = request()->user();

        if (!$user || !$user->tokenCan('delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $tag->delete();
        return response()->noContent();
        // return response()->json(['message' => 'Deleted'], 200);
    }
}
