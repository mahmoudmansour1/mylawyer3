<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            "consultation_number" => $this->consultation_number,
            'date' => $this->created_at->format('Y-m-d'),
            "status" => $this->status->name,
            "consultation_fees" => $this->consultation_fees,       
            "total" => $this->amount       

        ];
    }
}
