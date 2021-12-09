<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageChainResource extends JsonResource
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
            "ad_id" => $this->ad->id,
            "ad" => $this->ad->title,
            "advertiser_id" => $this->ad->user->id,
            "advertiser" => $this->ad->user->name,
            "bidder_id" => $this->bidder->id,
            "bidder" => $this->bidder->name,
            "updated_at" => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
