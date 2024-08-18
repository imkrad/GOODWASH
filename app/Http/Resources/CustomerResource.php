<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->lastname.', '.$this->firstname,
            'address' => $this->address.', '.$this->barangay->name,
            'email' => $this->email,
            'contact_no' => $this->contact_no,
            'type' => $this->type,
            'status' => $this->status,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'reserved_at' => $this->reserved_at,
            'created_at' => $this->created_at
        ];
    }
}
