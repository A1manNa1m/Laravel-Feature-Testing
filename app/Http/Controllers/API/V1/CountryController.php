<?php

namespace App\Http\Controllers\API\V1;

use App\Filter\V1\CountryFilter;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Http\Requests\V1\StoreCountryRequest;
use App\Http\Requests\V1\UpdateCountryRequest;
use App\Http\Resources\V1\CountryCollection;
use App\Http\Resources\V1\CountryResources;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read')) {
        abort(403, 'Unauthorized');
        }
        
        $filter = new CountryFilter();
        $filterItem = $filter->transform($request); //[['column','operator','value']]

        $result = Country::where($filterItem);

        return new CountryCollection($result->paginate()->appends($request->query()));
        
        // $country = Country::all();
        // return new CountryCollection($country);
        
        // return Country::all();
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
    public function store(StoreCountryRequest $request)
    {
        return new CountryResources(Country::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return new CountryResources($country);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $user = request()->user();

        if (!$user || !$user->tokenCan('delete')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $country->delete();
        return response()->noContent();
        // return response()->json(['message' => 'Deleted'], 200);

    }
}
