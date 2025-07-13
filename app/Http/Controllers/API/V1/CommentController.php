<?php

namespace App\Http\Controllers\API\V1;

use App\Filter\V1\CommentFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Requests\V1\StoreCommentRequest;
use App\Http\Requests\V1\UpdateCommentRequest;
use App\Http\Resources\V1\CommentCollection;
use App\Http\Resources\V1\CommentResources;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
        abort(403, 'Unauthorized');
        }
        
        $filter = new CommentFilter();
        $filterItem = $filter->transform($request); //[['column','operator','value']]

        $result = Comment::where($filterItem);

        return new CommentCollection($result->paginate()->appends($request->query()));

        // $comment = Comment::all();
        // return new CommentCollection($comment);

        // return Comment::all();
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
    public function store(StoreCommentRequest $request)
    {
        return new CommentResources(Comment::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return new CommentResources($comment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());
        return response()->json(['message'=>'Comment succesfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $user = request()->user();

        if (!$user || !$user->tokenCan('delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $comment->delete();
    }
}
