<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
   protected $fillable = ['startTime','endTime','day','patient_id','doctor_id'];
  public function patient()
  {
      return $this->belongsTo('App\Patient');
  }

  public function doctor()
   {
       return $this->belongsTo('App\Doctor');
   }
}
