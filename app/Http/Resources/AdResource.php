<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BidResource;
use App\Models\Bid;
use Illuminate\Support\Facades\Storage;

class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "premium" => $this->premium,
            "image" => Storage::url($this->image),
            "created_at" => $this->created_at->format('d-m-Y H:i:s'),
            "updated_at" => $this->updated_at->format('d-m-Y H:i:s'),
            "advertiser_id" => $this->user->id,
            "advertiser" => $this->user->name,
            "categories" => $this->category,
            "bids" => BidResource::collection(Bid::where("ad_id", "=", $this->id)->orderBy("amount", "desc")->get()),
        ];
    }
}
