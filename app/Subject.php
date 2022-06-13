<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $appends = ['subject'];


    public function getSubjectAttribute()
    {
        return Common::nameLanguage($this->subject_en, $this->subject_ar);
    }

}
