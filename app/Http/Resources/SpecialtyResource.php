<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LawyersResource;

class SpecialtyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *rt
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'lawyers' => LawyersResource::collection($this->lawyers)

        ];    
    }
}
