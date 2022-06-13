<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusOnline extends Model
{
    protected $table = 'status_online';

    protected $appends = ['name'];

    protected $fillable = ['id','name_en','name_ar'];



    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }
}
