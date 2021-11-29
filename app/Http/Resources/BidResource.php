<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
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
            "amount" => $this->amount,
            "bidder" => $this->user->name,
            "created_at" => $this->created_at->format('d-m-Y H:i:s')
        ];
    }
}
