<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
   protected $fillable = ['patient_id','doctor_id','pres'];
  public function doctor()
  {
      return $this->belongsTo('App\Doctor');
  }

  public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
}
