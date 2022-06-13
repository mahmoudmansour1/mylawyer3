<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Invoice as InvoiceResource;

class Request extends JsonResource
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
            'number_request' => $this->number_request,
            'service_id' => $this->service_id,
            'service_name' => $this->service->name,
            'service_info' => json_decode($this->service_info,true),
            'req_date' => $this->req_date,
            'req_time' => $this->req_time,
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'addresse_info' => json_decode($this->addresse_info,true),
            'car_make_id' => $this->car_make_id,
            'car_model_id' => $this->car_model_id,
            'car_make' => $this->make->name,
            'car_model' => $this->model->name,
            'car_years' => $this->car_years,
            'car_license_plate' => $this->car_license_plate,
            'status_id' => $this->status_id,
            'status' => $this->status->name,
            'reason' => $this->reason,
            'payment_status' => $this->payment_status_id?$this->paymentStatus->name:null,
            'invoice' => InvoiceResource::collection($this->invoices)
        ];
    }
}
