<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Consultation;
use App\StatusMessage;

class Message extends Model
{
    public $table = "messages";

    public function consultation()
    {
        return $this->belongsTo(Consultation::class,'consultation_id');
    }
    
    public function statusMessage()
    {
        return $this->belongsTo(StatusMessage::class, 'status_message');
    }
}
