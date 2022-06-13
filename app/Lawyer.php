<?php

namespace App;
use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\Model;
use App\Specialty;
use App\StatusOnline;

use App\Models\UserNotifications;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Lawyer extends Authenticatable
{
    use HasApiTokens;

    public $table = "lawyer";

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'number_consultations','consultations_fees', 'online_status','is_notified', 'is_active', 'is_blocked', 'otp', 'code','device_id',
        'device_token','category_id','otp_at','specialty_id','img','date','gender','membership_img','civil_img','commission','about'
    ];

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'specialty_lawyer');
    }
    public function statusOnline()
    {
        return $this->belongsTo(StatusOnline::class, 'online_status');
    }
    public function notifications()
    {
        return $this->hasMany(UserNotifications::class,'user_id')->where('type_user',1)->orderBy('updated_at', 'DESC');
    }

    public function admin_notifications()
    {
        return $this->belongsToMany(AdminNotification::class, 'admin_notification_users', 'admin_notification_id', 'user_id');
    }
}
