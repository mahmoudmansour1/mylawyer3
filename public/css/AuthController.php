<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\User;
use App\Lawyer;
use App\Models\Setting;
use App\Models\GuestDevice;

use App\Mail\WelcomMail;
use Mail;
use Auth;

class AuthController extends Controller
{
    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        $user = User::where('phone', $request->phone)->first();
        $lawyer = Lawyer::where('phone', $request->phone)->first();

        if($user){



            $credentials = array(
                'phone' => $user['phone'],
                'password' => ($request->password),
            );
    
            if(!Auth::guard('web')->attempt($credentials)){
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.Wrong Password");
                $response['data'] = [];
                return response()->json($response, 200);
            }
            if($user['is_blocked'] == 1){
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.blocked");
                $response['data']['is_blocked'] = $user['is_blocked'];
                $response['data']['is_active'] = $user['is_active'];

                // $response['token'] = $user->createToken('token')->accessToken;
                return response()->json($response, 200);
            }
            if($user['is_active'] == 0){
                // $response['status'] = 'error';
                // $response['api_status'] = 400;
                // $response['message'] = trans("api.Inactive");
                // $response['data']['is_blocked'] = $user['is_blocked'];
                // $response['data']['is_active'] = $user['is_active'];

                // // $response['token'] = $user->createToken('token')->accessToken;
                // return response()->json($response, 200);
                $user = $request->user();

                $success['status'] = 'success';
                $success['api_status'] = 200;
                $success['message'] = trans('api.login');
    
                //  $success['user_phone_prefix'] = $user->phone_prefix;
                $success['data']['type'] = "user";
                $success['data']['id'] = $user->id;
                $success['data']['wallet'] = $user->wallet;
                $success['data']['phone'] = $user->phone;
                $success['data']['email'] = $user->email;
                $success['data']['is_active'] = $user->is_active;
                $success['data']['name'] = $user->name;
                $success['data']['is_blocked'] = $user->is_blocked;
                $success['data']['token'] = $user->createToken('token')->accessToken;
                return response()->json($success, 200);   


            }
    

    
            $user = $request->user();
    
            if ($request->has('device_token')) {
                User::updateOrCreate(['id' => $user->id], [
                    'device_token' => $request->device_token,
                ]);
            }
            if ($request->has('device_platform')) {
                User::updateOrCreate(['id' => $user->id], [
                    'device_platform' => $request->device_platform,
                ]);
            }
            if ($request->has('device_id')) {
                User::updateOrCreate(['id' => $user->id], [
                    'device_id' => $request->device_id,
                ]);
            }

            $success['status'] = 'success';
            $success['api_status'] = 200;
            $success['message'] = trans('api.login');

            //  $success['user_phone_prefix'] = $user->phone_prefix;
            $success['data']['type'] = "user";
            $success['data']['id'] = $user->id;
            $success['data']['wallet'] = $user->wallet;
            $success['data']['phone'] = $user->phone;
            $success['data']['email'] = $user->email;
            $success['data']['is_active'] = $user->is_active;
            $success['data']['name'] = $user->name;
            $success['data']['is_blocked'] = $user->is_blocked;
            $success['data']['token'] = $user->createToken('token')->accessToken;
            return response()->json($success, 200);    



        }elseif($lawyer){


            $credentials = array(
                'email' => $lawyer['email'],
                'password' => ($request->password),
            );
    
            if(!Auth::guard('lawyer')->attempt($credentials)){
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.Wrong Password");
                $response['data'] = [];
                return response()->json($response, 200);
            }
            if($lawyer['is_blocked'] == 2){

                $response['status'] = 'error';
                $response['api_status'] = 40000;
                $response['message'] = trans("api.blocked");
                $response['data']['is_blocked'] = $lawyer['is_blocked'];
                // $response['token'] = $user->createToken('token')->accessToken;
                return response()->json($response, 200);

            }    
            if($lawyer['is_blocked'] == 1){

                $response['status'] = 'error';
                $response['api_status'] = 40000;
                $response['message'] = trans("api.reject");
                $response['data']['is_blocked'] = $lawyer['is_blocked'];
                $response['data']['is_active'] = $lawyer['is_active'];

                // $response['token'] = $user->createToken('token')->accessToken;
                return response()->json($response, 200);

            }
            
            if($lawyer['is_active'] == 0){
                // $response['status'] = 'error';
                // $response['api_status'] = 400;
                // $response['message'] = trans("api.Inactive");
                // $response['is_active'] = $lawyer['is_active'];
                // $response['is_blocked'] = $lawyer['is_blocked'];
                // $response['token'] = $lawyer->createToken('token')->accessToken;
                // return response()->json($response, 200);

            // sendsms($user,$activation_code,"activation%20account");
            $message = 'Welcome to MyLawyer , please use the following activation code: ' . $lawyer['code'];

            sendsms('965' . $lawyer['phone'] ,$message); 
            
             // Mail::to($request->email)->send(new WelcomMail($user));
       
                $success['status'] = 'success';
                $success['api_status'] = 200;
                $success['message'] = trans('api.login');

                //  $success['user_phone_prefix'] = $user->phone_prefix;
                $success['data']['type'] = "lawyer";
                $success['data']['id'] = $lawyer->id;
                $success['data']['phone'] = $lawyer->phone;
                $success['data']['wallet'] = 0;
                $success['data']['email'] = $lawyer->email;
                $success['data']['is_active'] = $lawyer->is_active;
                $success['data']['name'] = $lawyer->name;
                $success['data']['is_blocked'] = $lawyer->is_blocked;
                $success['data']['token'] = $lawyer->createToken('token')->accessToken;
                return response()->json($success, 200);                


            }else{


                //$user = $request->user();
    
                if ($request->has('device_token')) {
                    Lawyer::updateOrCreate(['id' => $lawyer->id], [
                        'device_token' => $request->device_token,
                    ]);
                }
                if ($request->has('device_platform')) {
                    Lawyer::updateOrCreate(['id' => $lawyer->id], [
                        'device_platform' => $request->device_platform,
                    ]);
                }
                if ($request->has('device_id')) {
                    Lawyer::updateOrCreate(['id' => $lawyer->id], [
                        'device_id' => $request->device_id,
                    ]);
                }
        
                $success['status'] = 'success';
                $success['api_status'] = 200;
                $success['message'] = trans('api.login');

                //  $success['user_phone_prefix'] = $user->phone_prefix;
                $success['data']['type'] = "lawyer";

                $success['data']['id'] = $lawyer->id;
                $success['data']['phone'] = $lawyer->phone;
                $success['data']['wallet'] = 0;
                $success['data']['email'] = $lawyer->email;
                $success['data']['is_active'] = $lawyer->is_active;
                $success['data']['name'] = $lawyer->name;
                $success['data']['is_blocked'] = $lawyer->is_blocked;
                $success['data']['token'] = $lawyer->createToken('token')->accessToken;
                return response()->json($success, 200);  
 
            }
    

    



        }else{

            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Invalid Phone");
            $response['data'] = [];
            return response()->json($response, 200);

        }


     

    }
    public function resendCode(request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);
        if($validator->fails()){
                                                                                                        
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }

        $user = User::where('phone',$request->phone)->first();
        
        if($user){
            $code = 'EN' . Carbon::now() . $user->name;
            $user->code = substr(preg_replace('/[^A-Za-z0-9\-]/', '', Hash::make($code)), 0, 6);
            $user->otp_at = Carbon::now();
            $user->update();
           // sendsms($user,$user->code,"activation%20account");
           $message = 'Welcome to MyLawyer , please use the following activation code: ' . $user->code;

           sendsms('965' . $user->phone ,$message); 
           // Mail::to($user->email)->send(new WelcomMail($user));

            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans("the code has been sent");
            $response['data'] = [];
            return response()->json($response, 200);


        }else{

            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
            

        }
    }
    public function resendCodelawyer(request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);
        if($validator->fails()){
                                                                                                        
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }

        $user = Lawyer::where('phone',$request->phone)->first();
        
        if($user){
            $code = 'EN' . Carbon::now() . $user->name;
            $user->code = substr(preg_replace('/[^A-Za-z0-9\-]/', '', Hash::make($code)), 0, 6);
            $user->otp_at = Carbon::now();
            $user->update();
           // sendsms($user,$user->code,"activation%20account");
           $message = 'Welcome to MyLawyer , please use the following activation code: ' . $user->code;

           sendsms('965' . $user->phone ,$message); 
           // Mail::to($user->email)->send(new WelcomMail($user));
           $response['status'] = 'success';
           $response['api_status'] = 200;
           $response['message'] = trans("the code has been sent");
           $response['data'] = [];
           return response()->json($response, 200);
        }else{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }
    }


    
    public function change_online_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'online_status' => 'required'
        ]);
        if($validator->fails()){
                                                                                                        
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }


        $lawyer = Auth::user();
       // dd($lawyer);
        if($lawyer){
      
                $lawyer->online_status = $request->online_status;
                $lawyer->update();

                $response['status'] = 'success';
                $response['api_status'] = 200;
                $response['message'] = trans("api.active_account");
                $response['data'] = [];
                return response()->json($response, 200);
           
        }else{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }



    }

    
    public function activateAccount_lawyer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if($validator->fails()){
                                                                                                        
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }

        $lawyer = Auth::user('lawyer-api');

        if($lawyer){
            $seting = Setting::find(1);
            if(Carbon::parse($lawyer['otp_at'])->diffInHours(Carbon::now()) >= $seting->nbr_hour_activ_code){
                
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.expired_code");
                $response['data'] = [];
                return response()->json($response, 200);
            }
            if($lawyer->code == $request->code){
                $lawyer->is_active = 1;
                $lawyer->code = '';
                $lawyer->otp_at = null;
                $lawyer->email_verified_at = Carbon::now();
                $lawyer->update();
                $response['status'] = 'success';
                $response['api_status'] = 200;
                $response['message'] = trans("api.active_account");
                $response['data'] = [];
                return response()->json($response, 200);
            }else{
  
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.not_found_code");
                $response['data'] = [];
                return response()->json($response, 200);
                
            }
        }else{

           
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }
    }

    public function activateAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if($validator->fails()){
                                                                                                        
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }

        $user = Auth::user();

        if($user){
            $seting = Setting::find(1);
            if(Carbon::parse($user['otp_at'])->diffInHours(Carbon::now()) >= $seting->nbr_hour_activ_code){
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.expired_code");
                $response['data'] = [];
                return response()->json($response, 200);
            }
            if($user->code == $request->code){
                $user->is_active = 1;
                $user->code = '';
                $user->otp_at = null;
                $user->email_verified_at = Carbon::now();
                $user->update();
                $response['status'] = 'success';
                $response['api_status'] = 200;
                $response['message'] = trans("api.active_account");
                $response['data'] = [];
                return response()->json($response, 200);
            }else{
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.not_found_code");
                $response['data'] = [];
                return response()->json($response, 200);
            }
        }else{
           
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }
    }

    //logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans("api.logged out");
            $response['data'] = [];
            return response()->json($response, 200);
    }

    public function storeGuestDevice(Request  $request)
    {
        $guest = GuestDevice::where('device_id',$request->device_id)->first();
        if($guest){
            $success['status'] = 'success';
            $success['api_status'] = 200;
            $success['message'] = trans('api.existe_guest_device');
            return response()->json($success, 200);
        }
        $user = User::where('device_id',$request->device_id)->first();
        if($user){
            $success['status'] = 'success';
            $success['api_status'] = 200;
            $success['message'] = trans('api.existe_guest_device');
            return response()->json($success, 200);
        }
        GuestDevice::create(
            [
                'device_token'=>$request->device_token,
                'device_id'=>$request->device_id
            ]
        );

        $success['status'] = 'success';
        $success['api_status'] = 200;
        $success['message'] = trans('api.added_guest_device');
        return response()->json($success, 200);
    }

}
