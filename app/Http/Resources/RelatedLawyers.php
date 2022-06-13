<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Specialty;
use App\Http\Resources\SpecialtyResource;
use App\Http\Resources\RelatedLawyers;
use App\Lawyer;
use App\Specialty as SpecialtyModel;
class RelatedLawyers extends JsonResource
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
            "about" => $this->about,
            "email" => $this->email,
            "number_consultations" => $this->number_consultations,
            "consultations_fees" => $this->consultations_fees,
            "password" => $this->password,
            "phone" => $this->phone,
            "is_notified" => $this->is_notified,
            "created_at" => $this->created_at,
            "img" => $this->img,
            "date" => $this->date,
            "membership_img" => $this->membership_img,
            "civil_img" => $this->civil_img,
            "gender" => $this->gender,
            "online_status" => $this->online_status,
            "specialties" => Specialty::collection($this->specialties)
        ]; 
    
    }
}
