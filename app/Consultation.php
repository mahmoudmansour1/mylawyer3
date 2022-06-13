<?php

namespace App;
use App\User;
use App\Lawyer;
use App\Specialty;
use App\Message;
use App\FilesConsultation;
use App\Models\Status;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentStatus;
use App\Models\Payment;

class Consultation extends Model
{
    public function specialty()
    {
        return $this->belongsTo(Specialty::class,'specialty_id');
    }    
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class,'lawyer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class,'request_id')->where('payment_status_id','!=',4);
    }
    public function messages_user()
    {
        return $this->hasMany(Message::class)->where('type_status',0);
    }
    public function messages_lawyer()
    {
        return $this->hasMany(Message::class)->where('type_status',1);
    } 
    public function messages()
    {
        return $this->hasMany(Message::class);
    }       
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }
    public function files()
    {
        return $this->hasMany(FilesConsultation::class);
    }

}
