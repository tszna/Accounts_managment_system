<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AdministrationEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'correspondence_address' => AddressResource::make($this->correspondenceAddress),
            'home_address' => AddressResource::make($this->homeAddress),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
