<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MessageChainResource;
use App\Models\MessageChain;
use App\Http\Requests\StoreMessageChain;

class MessageChainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MessageChainResource::collection(MessageChain::orderBy("updated_at", "desc")->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageChain $request)
    {
        $validated = $request->validated();
        $chain = new MessageChain();

        $chain->ad_id = $validated["ad_id"];
        $chain->bidder_id = $validated["bidder_id"];
        $chain->save();

        return $chain->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageChain $messageChain)
    {
        $messageChain->delete();
    }
}
