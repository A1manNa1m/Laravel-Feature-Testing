<?php

namespace App\Http\Controllers\API\V1;

use App\Filter\V1\SkillFilter;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Http\Requests\V1\StoreSkillRequest;
use App\Http\Requests\V1\UpdateSkillRequest;
use App\Http\Resources\V1\SkillCollection;
use App\Http\Resources\V1\SkillResources;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
        abort(403, 'Unauthorized');
        }

        $filter = new SkillFilter();
        $filterItem = $filter->transform($request); //[['column','operator','value']]

        $result = Skill::where($filterItem);

        return new SkillCollection($result->paginate()->appends($request->query()));

        // $skill = Skill::all();
        // return new SkillCollection($skill);

        // return Skill::all();
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
    public function store(StoreSkillRequest $request)
    {
        return new SkillResources(Skill::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        return new SkillResources($skill);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $skill->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        $user = request()->user();

        if (!$user || !$user->tokenCan('delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $skill->delete();
        return response()->noContent();
        // return response()->json(['message' => 'Deleted'], 200);
    }
}
