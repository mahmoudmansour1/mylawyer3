<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessagesProResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        if($this->type_status == 0){
            $sender = "user";
        }elseif ($this->type_status == 1){
            $sender = "lawyer";
        }elseif ($this->type_status == 2){
            $sender = "user";
        }else{
            $sender = "admin";
        }

        if($this->type_message == 0){
            $type_message = "text";
        }elseif($this->type_message == 2){
            $type_message = "voice";
        }else{
            $type_message = "file";
        }
        

        return [
            'id' => $this->id,
            'type_sender' => $sender,
            'type_message' => $type_message,
           'status_message' => $this->statusMessage->name,
            'content_message' => $this->message,
            'date' => $this->created_at->format('Y-m-d'),
            'time' => $this->created_at->format('H:i:s'),
            
        ];
    }
}
