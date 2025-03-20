<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            'code' => $this->code,
            'provider' => $this->provider->user->name,
            'worker' => $this->worker->user->name,
            'receiver_name' => $this->receiver_name,
            'receiver_address' => $this->receiver_address,
            'delivery_date' => $this->delivery_date,
        ];
    }
}
