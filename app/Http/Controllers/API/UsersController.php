<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomMail;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Lawyer;
use App\Models\Addresse;
use App\Models\Car;
use App\Models\GuestDevice;
use App\Models\UserNotifications;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Car as CarResource;
use App\Http\Resources\Addresse as AddresseResource;
use App\Http\Resources\UserNotification as NotificationResource;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\SpecialtyLawyer;


class UsersController extends Controller
{
    //create user
    public function signup(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|',
            'user_name' => 'required|string|',
        //  'phone_prefix' => 'required',
            'phone' => 'required|numeric|unique:lawyer,phone|unique:users,phone',
        //    'email' => 'email|unique:users',
            'email' => 'email|unique:lawyer,email|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'gender' => 'numeric',
        //    'date_birth' => 'string',
            'accept_terms' => ['required']
        ]);

        if($validator->fails()){
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        // $guest = GuestDevice::where('device_id',$request->device_id)->first();
        // if($guest){
        //     GuestDevice::where('device_id', $request->device_id)->delete();
        // }

        $code = 'EN' . Carbon::now() . $request->user_name;

        $activation_code = substr(preg_replace('/[^A-Za-z0-9\-]/', '', Hash::make($code)), 0, 6);

        $user =  User::create([
            'name' => $request->user_name,
            'name2' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'date' => $request->date_birth,
            'password' => Hash::make($request->password),
           // 'phone_prefix' => $request->phone_prefix,
           'is_active' => 1,
           'code' => $activation_code,
           'otp_at' => Carbon::now(),
            'device_token' => $request->device_token,
            'device_platform' => $request->device_platform,
            'device_id' => $request->device_id
        ]);
       // dd($user);
        //sendsms($user,$activation_code,"activation%20account");
        
        //Mail::to($request->email)->send(new WelcomMail($user));
        // $message = 'Welcome%20to%20MyLawyer%20,%20please%20use%20the%20following%20activation%20code:%20' . $activation_code;

        // sendsms('965'.$request->phone ,$message);
        if($user){

            $success['status'] = 'success';
            $success['api_status'] = 200;
            $success['message'] = trans('api.registrat');
            $success['data']['id'] = $user->id;
            $success['data']['token'] = $user->createToken('token')->accessToken;
            return response()->json($success, 200); 


        }
    }



        //create lawyer
        public function signup_lawyer(Request $request)
        {
            
            $setting = Setting::first();

            $validator = Validator::make($request->all(), [
                'membership_img' => 'mimes:jpeg,jpg,png|required|max:2000',
                'civil_img' => 'mimes:jpeg,jpg,png|required|max:2000',
                'full_name' => 'required|string|',
                'specialties' => 'required',
                'consultation_fees' => 'required|numeric|min:'.$setting['consultation_fees'],
            //  'user_name' => 'required|string|',
            //  'phone_prefix' => 'required',
                'phone' => 'required|numeric|unique:lawyer,phone|unique:users,phone',
                'email' => 'required|string|email|unique:lawyer,email|unique:users,email',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password',
                'gender' => 'numeric',
            //    'date_birth' => 'string',
                'accept_terms' => ['required']
            ]);
    
            if($validator->fails()){
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("validation errors");
                $response['data'] = $validator->errors();
                return response()->json($response, 200);
            }
    
    
            $code = 'EN' . Carbon::now() . $request->full_name;
    
            $activation_code = rand(1000,9000);
    
            if($request->file('membership_img') != null){

                $file = $request->file('membership_img');
                $membership_img = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $membership_img = 'uploads/requests/'.$membership_img.'.'.$file->getClientOriginalExtension();
                $file->move('/var/www/html/public/uploads/requests',$membership_img);
            }

            if($request->file('civil_img') != null){
                $file = $request->file('civil_img');
                $civil_img = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $civil_img = 'uploads/requests/'.$civil_img.'.'.$file->getClientOriginalExtension();
                $file->move('/var/www/html/public/uploads/requests',$civil_img);
            }
            //dd($request->specialties[0]);

            $lawyer =  Lawyer::create([
                //    'name' => $request->user_name,
                'membership_img' => $membership_img,
                'civil_img' => $civil_img,
                'name' => $request->full_name,
                'consultations_fees' => $request->consultation_fees,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'date' => $request->date_birth,
                'password' => Hash::make($request->password),
                // 'phone_prefix' => $request->phone_prefix,
                'code' => $activation_code,
                'is_blocked' => 2,
                'otp_at' => Carbon::now(),
                'device_token' => $request->device_token,
                'device_platform' => $request->device_platform,
                'device_id' => $request->device_id
            ]);

            
            foreach (explode(',',$request->specialties) as $specialt){
                //dd($specialt);

                if($specialt != null){
                   
                   $SpecialtyLawyer = new SpecialtyLawyer(); 
                   $SpecialtyLawyer->specialty_id = $specialt;
                   $SpecialtyLawyer->lawyer_id = $lawyer->id;
                   $SpecialtyLawyer->save();
    
               }    
            } 

            //dd($user);
            // sendsms($user,$activation_code,"activation%20account");
            
            // Mail::to($request->email)->send(new WelcomMail($user));
    
            if($lawyer){
                $success['status'] = 'success';
                $success['api_status'] = 200;
                $success['message'] = trans('api.registrat');
                $success['data']['id'] = $lawyer->id;
            //    $success['data']['token'] = $lawyer->createToken('token')->accessToken;
                return response()->json($success, 200); 
            }
        }
    public function getProfil()
    {
        $user = Auth::user();
        if($user){
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = new UserResource($user);
            return response()->json($response, 200);
        }
    }
    public function getProfil_lawyer()
    {
        $user = Auth::user();
        if($user){
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = new UserResource($user);
            return response()->json($response, 200);
        }
    }    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update_profile_account(Request $request)
    {
        $rules = [
            'user_name' => ['required', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255','unique:users,email,'.Auth::user()->id,],
           // 'phone' => ['required', 'numeric','unique:users,phone,'.Auth::user()->id,]
            'date_birth' => ['required'],
            'gender' => ['required'],
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }

        if (Auth::user()) {
            User::updateOrCreate(['id' => Auth::id()], [
                'name' => $request->user_name,
                'name2' => $request->full_name,
                'email' => $request->email,
                'date' => $request->date_birth,
                'gender' => $request->gender
            ]);
        } else {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.success_update");
        $response['data'] = [];
        return response()->json($response, 200);
    }
    // public function update_profile_lawyer(Request $request)
    // {


    //     if (Auth::user()) {
    //         $last_Lawyer = Auth::user();
    //         //dd($last_Lawyer);
    //         $img = $last_Lawyer->img;
    
    //         if($request->file('img') != null){
    
    //             $file2 = $request->file('img');
    //             $filename2 = time() . md5($file2->getClientOriginalName());
    //             $file2->move('/var/www/html/uploads/requests',$filename2);
    //             $img = '/var/www/html/uploads/requests/'.$filename2;
                
    //         }
    //         Lawyer::updateOrCreate(['id' => Auth::id()], [
    //             'img' => $img,
    //             'about' => $request->about

    //         ]);
        
    //     } else {
    //         $response['status'] = 'error';
    //         $response['api_status'] = 400;
    //         $response['message'] = trans("api.Unauthorized");
    //         $response['data'] = [];
    //         return response()->json($response, 200);
    //     }

    //     $response['status'] = 'success';
    //     $response['api_status'] = 200;
    //     $response['message'] = trans("api.success_update");
    //     $response['data'] = [];
    //     return response()->json($response, 200);
    // }
    
    public function update_deactive(Request $request)
    {
     
        if (Auth::user()) {



            $user = Auth::user();
            $user->is_blocked = 1;
            $user->update();

            // Lawyer::update(['id' => Auth::id()], [
            //     'img' => $img,
            //     'about' => $request->about

            // ]);
        } else {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }


            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans("api.logged out");
            $response['data'] = [];
            return response()->json($response, 200);

    }        
    public function update_profile_lawyer(Request $request)
    {
        $rules = [
            'img' => 'mimes:jpeg,jpg,png|max:2000'

        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }

        if (Auth::user()) {


            $last_Lawyer = Auth::user();
            //dd($last_Lawyer);
            $fileName = $last_Lawyer->img;
    




            
            if($request->file('img') != null){
    
                $file = $request->file('img');
                $fileName = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = 'uploads/requests/'.$fileName.'.'.$file->getClientOriginalExtension();
                $file->move('/var/www/html/public/uploads/requests',$fileName);


                // $filename2 = time().rand(0, 1000).pathinfo($file2->getClientOriginalName(), PATHINFO_FILENAME);
                // $filename2 = 'uploads/requests/'.$filename2.'.'.$file2->getClientOriginalExtension();

                // $file2->move('/var/www/html/uploads/requests',$filename2);
                // $img = 'uploads/requests/'.$filename2;
                
            }
            $user = Auth::user();
            $user->about = $request->about;
            $user->img = $fileName;
            $user->update();

            // Lawyer::update(['id' => Auth::id()], [
            //     'img' => $img,
            //     'about' => $request->about

            // ]);
        } else {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.success_update");
        $response['data'] = [];
        return response()->json($response, 200);

    }    


    public function update_profile_account_lawyer(Request $request)
    {
        $rules = [
            'user_name' => ['required', 'string', 'max:255'],
         //   'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255','unique:lawyer,email,'.Auth::user()->id,],
           // 'phone' => ['required', 'numeric','unique:users,phone,'.Auth::user()->id,]
            'date_birth' => ['required'],
            'gender' => ['required'],
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);

        }

        if (Auth::user()) {
            Lawyer::updateOrCreate(['id' => Auth::id()], [
                'name' => $request->user_name,
              //  'name2' => $request->full_name,
                'email' => $request->email,
                'date' => $request->date_birth,
                'gender' => $request->gender
            ]);
        } else {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.success_update");
        $response['data'] = [];
        return response()->json($response, 200);

    }    
    


    public function update_profile_phone(Request $request)
    {
        $rules = [

            'phone' => ['required', 'numeric','unique:users,phone,'.Auth::user()->id,]

        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        if(Auth::user()){
            User::updateOrCreate(['id' => Auth::id()], [
                'phone' => $request->phone

            ]);
        }else{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.success_update");
        $response['data'] = [];
        return response()->json($response, 200);

    }


    public function update_profile_phone_lawyer(Request $request)
    {
        $rules = [

            'phone' => ['required', 'numeric','unique:lawyer,phone,'.Auth::user()->id,]

        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        if (Auth::user()) {
            Lawyer::updateOrCreate(['id' => Auth::id()], [
                'phone' => $request->phone

            ]);
        } else {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.success_update");
        $response['data'] = [];
        return response()->json($response, 200);
    }
    
    public function notifications()
    {
        $user = Auth::user();
        //dd($user);
        if($user){
            $success['status'] = 'success';
            $success['api_status'] = 200;
            $success['message'] = '';
            $success['data'] = NotificationResource::collection($user->notifications);
            $success['count_not_read_notifications'] = count($user->notifications->where('is_read',0));
            return response()->json($success, 200);
        }
    }

    public function readNotification(Request $request){
        UserNotifications::where('id', $request->id)
                            ->where('user_id', Auth::Id())
                            ->update(['is_read' => 1]);

        $success['status'] = 'success';
        $success['api_status'] = 200;
        $success['message'] = trans("api.success_update");
        $success['data'] = [];

        return response()->json($success, 200);
    }

    public function notificationSettings()
    {
        $user = Auth::user();

        $success['status'] = 'success';
        $success['api_status'] = 200;
        $success['message'] = '';
        $success['data']['is_notified'] = $user->is_notified;
        return response()->json($success, 200);
    }

    public function updateNotificationSettings(Request $request)
    {
        $user = Auth::user();
        $user->is_notified = $request->value;
        $user->update();

        $success['status'] = 'success';
        $success['api_status'] = 200;
        $success['message'] = '';
        $success['data']['is_notified'] = $user->is_notified;
        return response()->json($success, 200);
    }



  

}
