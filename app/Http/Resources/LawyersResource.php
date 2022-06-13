<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Specialty;
use App\Http\Resources\SpecialtyResource;
use App\Http\Resources\RelatedLawyers;
use App\Lawyer;
use App\Specialty as SpecialtyModel;

class LawyersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

      //  $id_specialty = Lawyer::find($this->id)->specialties[0]->id;
        // $related = Lawyer::whereHas('specialties', function($q) use($id_specialty) {
        //     $q->whereIn('specialty_id', $id_specialty);
        // })->get();
       // dd($id_specialty);
    //   dd($this->specialties[0]->id);
    if(count($this->specialties) != 0){
        $related = RelatedLawyers::collection(SpecialtyModel::find($this->specialties[0]->id)->lawyers);
    }else{
        $related = [];

    }

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
            'specialties' => Specialty::collection($this->specialties),
            'related_lawyers' => $related
        ];
        
    }
}
