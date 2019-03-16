<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
  public function prescriptions()
  {
      return $this->hasMany('App\Prescription');
  }

  public function appointment()
   {
       return $this->hasOne('App\Appointment');
   }
}
