<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
  public function doctor()
  {
      return $this->belongsTo('App\Doctor');
  }

  public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
}
