<?php

namespace App;

use App\Consultation;

use Illuminate\Database\Eloquent\Model;

class FilesConsultation extends Model
{
    protected $fillable = [
        'file', 'consultation_id'
    ];
    public $table = "files_consultation";

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
