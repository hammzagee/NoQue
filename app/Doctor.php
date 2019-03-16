<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
  protected $fillable = ['token'];
  public function appointments()
 {
     return $this->hasMany('App\Appointment');
 }

 public function prescriptions()
{
    return $this->hasMany('App\Prescription');
}

 public function clinic()
   {
       return $this->belongsTo('App\Clinic');
   }
}
