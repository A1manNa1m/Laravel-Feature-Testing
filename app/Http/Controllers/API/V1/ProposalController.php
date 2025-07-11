<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Http\Requests\V1\StoreProposalRequest;
use App\Http\Requests\V1\UpdateProposalRequest;
use App\Http\Resources\V1\ProposalCollection;
use App\Http\Resources\V1\ProposalResources;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proposal = Proposal::all();
        return new ProposalCollection($proposal);

        // return Proposal::all();
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
    public function store(StoreProposalRequest $request)
    {
        return new ProposalResources(Proposal::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Proposal $proposal)
    {
        return new ProposalResources($proposal);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proposal $proposal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProposalRequest $request, Proposal $proposal)
    {
        $proposal->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proposal $proposal)
    {
        //
    }
}
