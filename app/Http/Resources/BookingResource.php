<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'user' => new UserResource($this->whenLoaded('user')),
            'level' => new LevelResource($this->whenLoaded('level')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'times' => BookingTimeResource::collection($this->whenLoaded('times')),
        ];
    }
}
