<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RelatedLawyers;
use App\Message;

class ConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(count($this->messages) != 0){

            $cancel = 0;
           // dd(count($count));

        }else{
            
            $cancel = 1;

        }
        $count = Message::where('status_message', 1)->where('consultation_id', $this->id)->get();

        return [

            "id" => $this->id,
            "consultation_number" => $this->consultation_number,
            "subject" => $this->subject,
            "customer_name" => $this->customer_name,
            "customer_phone" => $this->customer_phone,
            "lawyer_name" => $this->lawyer_name,
            "lawyer_phone" => $this->lawyer_phone,
            "status" => $this->status->name,
            "amount" => $this->amount,
            "payment_method" => $this->payment->name,
            "user_id" => $this->user_id,
            "lawyer_id" => $this->lawyer_id,
            "created_at" => $this->created_at,
        //    "payment_id" => $this->payment_id,
            "review" => $this->review,
            "rating" => $this->rating,
            "commission" => $this->commission,
            "description" => $this->description,
            "consultation_fees" => $this->consultation_fees,           
            "transaction_id" => $this->transaction_id,           
            'can_cancel' => $cancel,
            'num_unseen_messages' => $count,
            'files' => FilesResource::collection($this->files)
            
        ];
    }
}
