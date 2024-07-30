<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'total' => $this->total,
            'status' => $this->status,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
            'order_details' => $this->whenLoaded('orderDetails', function () {
                return $this->orderDetails->map(function ($orderDetail) {
                    return [
                        'id' => $orderDetail->id,
                        'product_id' => $orderDetail->product_id,
                        'quantity' => $orderDetail->quantity,
                        'price' => $orderDetail->price,
                        'product' => $this->whenLoaded('product', function () use ($orderDetail) {
                            return [
                                'id' => $orderDetail->product->id,
                                'name' => $orderDetail->product->name,
                                'price' => $orderDetail->product->price,
                            ];
                        }),
                    ];
                });
            }),
            'payment' => $this->whenLoaded('payment', function () {
                return [
                    'id' => $this->payment->id,
                    'amount' => $this->payment->amount,
                    'status' => $this->payment->status,
                    'method' => $this->payment->method,
                    'transaction_id' => $this->payment->transaction_id,
                    'payment_details' => json_decode($this->payment->payment_details, true),
                    'created_at' => $this->payment->created_at,
                    'updated_at' => $this->payment->updated_at,
                ];
            }),
        ];
    }
}
