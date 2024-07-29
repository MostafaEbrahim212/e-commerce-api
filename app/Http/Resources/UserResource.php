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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'first_name' => $this->profile->first_name,
            'last_name' => $this->profile->last_name,
            'phone_number' => $this->profile->phone_number,
            'profile_picture' => $this->profile->profile_picture,
            'cover_picture' => $this->profile->cover_picture,
            'bio' => $this->profile->bio,
            'addresses' =>
                $this->addresses->map(function ($address) {
                    return [
                        'id' => $address->id,
                        'address' => $address->address,
                        'city' => $address->city,
                        'country' => $address->country,
                        'postal_code' => $address->postal_code,
                    ];
                })
            ,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
