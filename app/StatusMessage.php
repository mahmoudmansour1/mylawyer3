<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusMessage extends Model
{
    protected $table = 'status_message';

    protected $appends = ['name'];

    protected $fillable = ['id','name_en','name_ar'];



    public function getNameAttribute()
    {
        return Common::nameLanguage($this->name_en, $this->name_ar);
    }
}
