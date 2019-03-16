<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
   protected $fillable = ['email','name','password','token'];

  public function prescriptions()
  {
      return $this->hasMany('App\Prescription');
  }

  public function appointment()
   {
       return $this->hasOne('App\Appointment');
   }
}
