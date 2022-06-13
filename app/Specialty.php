<?php

namespace App;
use App\Common;
use App\Lawyer;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
   public $table = "specialty";
   protected $appends = ['name'];

   public function getNameAttribute()
   {
       return Common::nameLanguage($this->name_en, $this->name_ar);
   }

   public function lawyers()
   {
       return $this->belongsToMany(Lawyer::class, 'specialty_lawyer');
   }  
   public function lawyers_limit()
   {
       return $this->belongsToMany(Lawyer::class, 'specialty_lawyer')->where('is_active','1')->take(2);
   }      
}
