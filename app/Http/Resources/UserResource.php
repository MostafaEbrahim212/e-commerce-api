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
            'status' => $this->status,
            'first_name' => $this->profile->first_name ?? null,
            'last_name' => $this->profile->last_name ?? null,
            'phone_number' => $this->profile->phone_number ?? null,
            'profile_picture' => $this->profile->profile_picture ?? null,
            'cover_picture' => $this->profile->cover_picture ?? null,
            'bio' => $this->profile->bio ?? null,
            'addresses' => $this->addresses->map(function ($address) {
                return [
                    'id' => $address->id,
                    'address' => $address->address,
                    'city' => $address->city,
                    'country' => $address->country,
                    'postal_code' => $address->postal_code,
                ];
            }) ?? null,
            'orders' => $this->orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ];
            }) ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
