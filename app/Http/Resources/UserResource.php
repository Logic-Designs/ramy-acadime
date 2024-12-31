<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profileData = [];
        if($this->hasRole('user')  && $this->profile){
            $profileData = [
                'gender'=> $this->profile->gender,
                'bio'=> $this->profile->bio,
                'city'=> $this->profile->city?$this->profile->city->name:'',
                'address'=> $this->profile->address,
                'birthday'=> $this->profile->birthday,
            ];
        }

        return
        array_merge($profileData, [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'avatar' => $this->avatar?getUrl($this->avatar): url('images/defualte-user.png'),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'slug' => $this->slug,
            'email' => $this->email,
            'role' => optional($this->roles->first())->name,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ]);
    }
}
