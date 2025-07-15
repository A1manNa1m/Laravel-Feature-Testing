<?php

namespace App\Http\Controllers\API\V1;

use App\Filter\V1\ProjectFilter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\V1\StoreProjectRequest;
use App\Http\Requests\V1\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectCollection;
use App\Http\Resources\V1\ProjectResources;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
        abort(403, 'Unauthorized');
        }

        $filter = new ProjectFilter();
        $filterItem = $filter->transform($request); //[['column','operator','value']]

        $result = Project::where($filterItem);

        return new ProjectCollection($result->paginate()->appends($request->query()));

        // $project = Project::all();
        // return new ProjectCollection($project);

        // return Project::all();
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
    public function store(StoreProjectRequest $request)
    {
        return new ProjectResources(Project::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return new ProjectResources($project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $user = request()->user();

        if (!$user || !$user->tokenCan('delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $project->delete();
        // return response()->noContent();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
