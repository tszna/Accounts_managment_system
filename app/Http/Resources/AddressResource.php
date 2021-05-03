<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'voivodeship' => $this->voivodeship,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'street' => $this->street,
            'number' => $this->number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
