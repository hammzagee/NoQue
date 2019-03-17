<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Appointment as AppointmentResource;

use App\Http\Resources\Clinic as ClinicResource;

class Doctor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id'=>$this->id,
          'name'=>$this->name,
          'email'=>$this->email,
          'appointments'=> AppointmentResource::collection($this->appointments),
          'clinic'=>new ClinicResource($this->clinic),
        ];
    }
}
