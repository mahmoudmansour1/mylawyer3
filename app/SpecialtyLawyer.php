<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Specialty;
use App\Lawyer;

class SpecialtyLawyer extends Model
{
  //  use SoftDeletes;

    protected $table = 'specialty_lawyer';

    protected $fillable = ['id','specialty_id','lawyer_id'];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class,'specialty_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class,'lawyer_id');
    }
}
