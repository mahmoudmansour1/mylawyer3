<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Common;

class UserNotifications extends Model
{

    protected $table = 'user_notifications';
    protected $fillable = [
        'is_read', 'user_id','title', 'text' ,'link','type_user'
    ];
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}