<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = isset($this->data['user_id']) ? User::find($this->data['user_id']) : null;

        return [
            'id' => $this->id,
            'read_at' => $this->read_at,
            'data' => $this->data,
            'user' => $user ? new UserResource($user) : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
