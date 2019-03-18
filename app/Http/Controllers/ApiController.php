<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Doctor;
use Validator;
use DB;
use App\Appointment;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Doctor as DoctorResource;
use App\Http\Resources\Patient as PatientResource;

class ApiController extends Controller
{
    public function patientLogin(Request $request)
    {
      $record = Patient::where('email',$request->get('email'))->first();
      if(isset($record)){
        if(Hash::check($request->get('password'), $record->password)){
          $token = Hash::make(str_random(8));
          $record->token = $token;
          $record->save();
          return response()->json(['success'=>true, 'message'=>'Successfully','token'=>$token,'name'=>$record->name],200);
        }
        else {
          return response()->json(['success'=>false, 'message'=>'Wrong Password'],200);
        }
      }
      else {
        return response()->json(['success'=>false, 'message'=>'No Record Found Against provided Email'],200);
      }
    }

    public function getDoctors()
    {
      return response()->json(['success'=>true, 'message'=>'Doctors List Fetched','doctors'=>DoctorResource::collection(Doctor::all())],200);
    }
    public function patientSignUp(Request $request)
    {
      $validator = Validator::make($request->all(), [
           'name' => 'required',
           'email' => 'required|email',
           'password' => 'required',
           'c_password' => 'required|same:password',
       ]);
       if ($validator->fails()) {
            return response()->json(['success'=>false,'message'=>$validator->errors()], 200);
        }
        $record = Patient::where('email',$request->get('email'))->first();
        if(isset($record)){
          return response()->json(['success'=>false,'message'=>'This Email is Taken'], 200);
        }
        else {
          $input = $request->all();
          $input['password'] = bcrypt($input['password']);
          $patient = Patient::create([
              'name' => $input['name'],
              'email' => $input['email'],
              'password' => $input['password']
              ]);
          $token = Hash::make(str_random(8));
          $patient->token = $token;
          $patient->save();
          return response()->json(['success'=>true,'message'=>'Patient Register Successfull','token'=>$token], 200);
        }
    }

    public function doctorLogin(Request $request)
    {
      $record = Doctor::where('email',$request->get('email'))->first();
      if(isset($record)){
        if(Hash::check($request->get('password'), $record->password)){
          $token = Hash::make(str_random(8));
          $record->token = $token;
          $record->save();
          return response()->json(['success'=>true, 'message'=>'Successfully','token'=>$token,'name'=>$record->name],200);
        }
        else {
          return response()->json(['success'=>false, 'message'=>'Wrong Password'],200);
        }
      }
      else {
        return response()->json(['success'=>false, 'message'=>'No Record Found Against provided Email'],200);
      }
    }

    public function getAppointments_d(Request $request)
    {
      $appStatus = false;
      $patient = Patient::where('token',$request->get('token'))->first();
      if(isset($patient)){
        $app = Appointment::where('patient_id',$patient->id)->first();
        if(isset($app)){
          $appStatus = true;
        }else {
          $appStatus = false;
        }
      }
      $record = Doctor::where('id',$request->get('id'))->first();
      if(isset($record)){
        $appoints = Doctor::with('Clinic')->where('id',$request->get('id'))->get();
        return response()->json(['success'=>true, 'message'=>'Successfully','appStatus'=>$appStatus,'day1'=>Appointment::where('day',1)->count(),
        'day2'=>Appointment::where('day',2)->count(),'day3'=>Appointment::where('day',3)->count(),
        'day4'=>Appointment::where('day',4)->count(),'day5'=>Appointment::where('day',5)->count(),
        'data'=>DoctorResource::collection($appoints),],200);
        }
      else {
        return response()->json(['success'=>false, 'message'=>'Unauthorized'],200);
      }
    }

    public function makeAppointment(Request $request)
    {
      $record = Patient::where('token',$request->get('token'))->first();
      if(isset($record)){
        $doc =  Doctor::where('id',$request->get('doctor_id'))->first();
        if(isset($doc)){
          $appoints = Appointment::create([
            'patient_id'=>$record->id,
            'doctor_id'=>$request->get('doctor_id'),
            'day'=>$request->get('day'),
            'startTime'=>$request->get('startTime'),
            'endTime'=>$request->get('endTime'),
          ]);
          return response()->json(['success'=>true, 'message'=>'Appointment Placed Successfully'],200);
        }
        else {
          return response()->json(['success'=>false, 'message'=>'UnKnown Doctor'],200);
        }
      }
      else {
        return response()->json(['success'=>false, 'message'=>'Unauthorized'],200);
      }

    }

    public function reScheduleGetAppointment(Request $request)
    {
      $record = Patient::where('token',$request->get('token'))->first();
      if(isset($record)){
        if (isset($record->appointment)) {
          $doc = Doctor::with('Appointments')->where('id',$record->appointment->doctor_id)->get();
          return response()->json(['success'=>true, 'message'=>'Successfully','current_app'=>$record->appointment,'data'=>DoctorResource::collection($doc)],200);
        }
        else {
            return response()->json(['success'=>true, 'message'=>'No Appointments'],200);
        }
      }
      else {
        return response()->json(['success'=>false, 'message'=>'Unauthorized'],200);
      }
    }

    public function updateAppointment(Request $request)
    {
      $record = Patient::where('token',$request->get('token'))->first();
      if(isset($record)){
        if (isset($record->appointment)) {
          DB::table('appointments')
          ->where('patient_id',$record->id)
          ->update(['startTime'=>$request->get('startTime'),'endTime'=>$request->get('endTime')]);
          return response()->json(['success'=>true, 'message'=>'Appointment Successfully Updated'],200);
        }
        else {
          return response()->json(['success'=>true, 'message'=>'No Appointments'],200);
        }
      }
      else {
          return response()->json(['success'=>false, 'message'=>'Unauthorized'],200);
      }
    }

    public function getPrescription(Request $request)
    {
      $record = Patient::where('token',$request->get('token'))->first();
      if(isset($record)){
        return response()->json(['success'=>true, 'message'=>'Successfully','data'=> new PatientResource($record)],200);
      }
      else {
        return response()->json(['success'=>false, 'message'=>'Unauthorized'],200);
      }
    }

    public function makePrescription(Request $request)
    {
        $record = Doctor::where('token',$request->get('token'))->first();
        if (isset($record)) {
          $pres = Prescription::create([
            'patient_id'=>$request->get('patient_id'),
            'doctor_id'=>$record->id,
            'pres'=>$request->get('prescription'),
          ]);
            return response()->json(['success'=>true, 'message'=>'Prescription given Successfully'],200);
        }
        else {
          return response()->json(['success'=>false, 'message'=>'Unauthorized'],200);
        }
    }
}
