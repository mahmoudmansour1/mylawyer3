<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LawyersSpcialResource;

class SpecialtyResource1 extends JsonResource
{
    /**
     * Transform the resource into an array.
     * rt
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'lawyers' => LawyersSpcialResource::collection($this->lawyers_limit)

        ];
    }
}