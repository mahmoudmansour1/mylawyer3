<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Specialty;

class LawyersSpcialResource extends JsonResource
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
            "id" => $this->id,
            "membership_id" => $this->membership_id,
            "name" => $this->name,
            "number_consultations" => $this->number_consultations,
            "consultations_fees" => $this->consultations_fees,
            "img" => $this->img,
            "membership_img" => $this->membership_img,
            'specialties' => Specialty::collection($this->specialties),
            'pagination' => $this->perPage

        ];   
     }
}
