<?php

namespace App\Http\Controllers\API\V1;

use App\Filter\V1\ProfileFilter;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Requests\V1\StoreProfileRequest;
use App\Http\Requests\V1\UpdateProfileRequest;
use App\Http\Resources\V1\ProfileCollection;
use App\Http\Resources\V1\ProfileResources;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
        abort(403, 'Unauthorized');
        }

        $filter = new ProfileFilter();
        $filterItem = $filter->transform($request); //[['column','operator','value']]

        $result = Profile::where($filterItem);

        return new ProfileCollection($result->paginate()->appends($request->query()));

        // $profile = Profile::all();
        // return new ProfileCollection($profile);

        // return Profile::all();
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
    public function store(StoreProfileRequest $request)
    {
        return new ProfileResources(Profile::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        return new ProfileResources($profile);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $profile->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        $user = request()->user();

        if (!$user || !$user->tokenCan('delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $profile->delete();
        return response()->noContent();
        // return response()->json(['message' => 'Deleted'], 200);
    }
}
