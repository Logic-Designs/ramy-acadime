<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingTimeResource extends JsonResource
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
            'booking_id' => $this->booking_id,
            'session_time_id' => $this->session_time_id,
            'session_time_start' => $this->sessionTime->start_time,
            'session_time_end' => $this->sessionTime->end_time,
            'date' => $this->date,
            'status' => $this->status,
            'levelSession' => new LevelSessionResource($this->whenLoaded('levelSession')),
            'coach' => new UserResource($this->whenLoaded('coach')),
        ];
    }
}
