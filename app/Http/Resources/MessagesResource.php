<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Message;

use App\Http\Resources\MessagesProResource;

class MessagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->lawyer->img){
            $img_lawyer = $this->lawyer->img;
        }else{
            $img_lawyer = url('/uploads/requests/lawyer.png');
        }

            $img_user = url('/uploads/requests/user.png');

            $message_lawyer = [];
            $message_admin = [];

            $message_lawyer = MessagesProResource::collection(Message::whereIn('type_status', [0,1])->where('consultation_id',$this->id)->orderBy("id", "desc")->get());

            $message_admin = MessagesProResource::collection(Message::whereIn('type_status', [2,3])->where('consultation_id',$this->id)->orderBy("id", "desc")->get());

        return [

            'lawyer_id' => $this->lawyer->id,
            'lawyer_name' => $this->lawyer->name,
            'online_status_lawyer' => $this->lawyer->statusOnline->name,
            'img_lawyer' => $img_lawyer,
            'users_id' => $this->user->id,
            'users_name' => $this->user->name,
            'online_status_user' => $this->user->statusOnline->name,
            'img_user' => $img_user,            
            //'messages' => $this->messages->id,
            'messages' => $message_lawyer,
            'messages_admin' => $message_admin
        //   'old' => $this->when($this->resource->age > 18, true)

        ];
    }
}