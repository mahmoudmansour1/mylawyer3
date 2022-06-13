<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Car;
use App\Models\Addresse;
use App\Models\Category;
use App\Models\UserNotifications;
use App\StatusOnline;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_prefix', 'phone', 'is_notified', 'is_active', 'is_blocked', 'otp', 'code','device_id',
        'device_token','category_id','otp_at','name2','phone2','relationship1','relationship2','img','date','gender'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function addresses()
    {
        return $this->hasMany(Addresse::class,'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotifications::class,'user_id')->where('type_user',0)->orderBy('updated_at', 'DESC');
    }

    public function admin_notifications()
    {
        return $this->belongsToMany(AdminNotification::class, 'admin_notification_users', 'admin_notification_id', 'user_id');
    }

    public function statusOnline()
    {
        return $this->belongsTo(StatusOnline::class, 'online_status');
    }

}
