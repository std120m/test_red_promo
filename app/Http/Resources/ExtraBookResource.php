<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtraBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            BookResource::make($this)->resolve(),
            [
                'reserves' => ReserveResource::collection($this->reserves),
                'checkouts' => BookCheckoutResource::collection($this->checkouts),
            ]
        );
    }
}
