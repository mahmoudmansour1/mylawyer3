<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Subject;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = ['subject_id','name','email','message'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
