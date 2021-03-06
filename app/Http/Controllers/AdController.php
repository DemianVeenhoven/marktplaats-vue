<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Bid;
use App\Http\Requests\StoreAd;
use App\Http\Requests\StoreBid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AdResource::collection(Ad::orderBy("premium", "desc")->orderBy("created_at", "desc")->get());
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
    public function store(StoreAd $request)
    {
        $validated = $request->validated();
        $ad = new Ad();

        if (array_key_exists("image", $validated)) {
            $path = Storage::put("public", $validated["image"]);
            $ad->image_path = $path;
            unset($validated["image"]);
        }

        $ad->fill($validated);
        $ad->user_id = Auth::id();
        $ad->save();

        $ad->categories()->sync($validated["categories"]);
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
    public function update(StoreAd $request, Ad $ad)
    {
        $validated = $request->validated();

        if (array_key_exists("image", $validated)) {
            $path = Storage::put("public", $validated["image"]);
            $ad->image_path = $path;
            unset($validated["image"]);
        }

        $ad->update($validated);

        $ad->categories()->sync($validated["categories"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad)
    {
        $ad->categories()->sync([]);
        $ad->delete();
    }

    public function bid(StoreBid $request, Ad $ad) {
        $validated = $request->validated();
        $bid = new Bid();

        $bid->fill($validated);
        $bid->ad_id = $ad->id;
        $bid->user_id = Auth::id();
        $bid->save();
    }

    public function upgrade(Ad $ad) {
        $ad->premium = 1;
        $ad->save();
    }
}
