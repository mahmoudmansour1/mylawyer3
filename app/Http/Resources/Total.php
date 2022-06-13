<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Setting;

class Total extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if($this->commission == 0){
            $settings = Setting::first();
            $commission = $settings->commission; 
        }else{
            $commission = $this->commission; 
        }

        $total = $commission + $this->consultations_fees;
        return [

            'commission' => $commission,
            'consultations_fees' => $this->consultations_fees,
            'total' => $total,
        ];
    }
}
