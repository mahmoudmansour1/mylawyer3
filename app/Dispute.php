<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    public $table = "dispute";

    protected $fillable = ['consultation_id','type_status','type_accept','refund_method','message'];

}
