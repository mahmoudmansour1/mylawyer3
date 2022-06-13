<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\PasswordResets;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\ResetPassword;
use App\Mail\WelcomMail;
use App\Mail\SmsMail;
use App\Mail\ChangePassword;
use Auth;
use App\Lawyer;


class PasswordController extends Controller
{
    public function sendPasswordResetCode(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric'
        ]);

        if($validator->fails())
		{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        $user = User::where('phone', $request->phone)->first();

        if ( !$user ){

            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found_user");
            $response['data'] = [];
            return response()->json($response, 200);
 

        }

        $code = 'EN' . Carbon::now() . $user->name;
        
        $code_password = rand(10000,50000);
        
        $user->update([
            'otp' => $code_password,
            // 'code' => $code_password,
        ]);

        PasswordResets::where('email', $user->email)->delete();
        //create a new token to be sent to the user.
        $PasswordResets = new PasswordResets();
        $PasswordResets->email = $user->email;
        $PasswordResets->token = str_random(60);
        $PasswordResets->created_at = Carbon::now();
		$PasswordResets->save();

        //Mail::to($user->email)->send(new SmsMail($user));

        //sendsms($user,$code_password,"verification");
        //dd($user);
        $message = 'Welcome%20to%20MyLawyer%20,%20please%20use%20the%20following%20activation%20code:%20' . $code_password;

        sendsms('965' . $user->phone ,$message); 
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.send_sms");
        $response['data'] = [];
        return response()->json($response, 200);

        /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
    }

    public function sendPasswordResetCode_lawyer(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric'
        ]);

        if($validator->fails())
		{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        $user = Lawyer::where('phone', $request->phone)->first();

        if ( !$user ){

            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found_user");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        $code = 'EN' . Carbon::now() . $user->name;
        
        $code_password = rand(10000,50000);
        
        $user->update([
            'otp' => $code_password,
            // 'code' => $code_password,
        ]);

        PasswordResets::where('email', $user->email)->delete();
        //create a new token to be sent to the user.
        $PasswordResets = new PasswordResets();
        $PasswordResets->email = $user->email;
        $PasswordResets->token = str_random(60);
        $PasswordResets->created_at = Carbon::now();
		$PasswordResets->save();

        $message = 'Welcome%20to%20MyLawyer%20,%20please%20use%20the%20following%20activation%20code:%20' . $code_password;

        sendsms('965' . $user->phone ,$message); 


        //Mail::to($user->email)->send(new SmsMail($user));
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.send_sms");
        $response['data'] = [];
        return response()->json($response, 200);
        //sendsms($user,$code_password,"verification");
        //dd($user);

        /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
    }

    
    public function verifyPasswordCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|'
        ]);
        if($validator->fails()){
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }
        $user = User::where('otp', $request->code)->first();
        //dd($user);
        if(!$user){
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found_code");
            $response['data'] = [];
            return response()->json($response, 200);
        }else{


            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans("api.verified_code");
            $response['data']['token'] =  $user->createToken('token')->accessToken;

            return response()->json($response, 200);            
        }
    }

    public function verifyPasswordCode_lawyer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|'
        ]);
        if($validator->fails()){
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }
        $user = Lawyer::where('otp', $request->code)->first();
        //dd($user);
        if(!$user){
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found_code");
            $response['data'] = [];
            return response()->json($response, 200);
        }else{

            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans("api.verified_code");
            $response['data']['token'] =  $user->createToken('token')->accessToken;
            return response()->json($response, 200); 
        }
    }    
    
    public function changePassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
			'password' => 'required|string|min:8|confirmed',
		]);

        if($validate->fails())
		{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
		}
        $user  = Auth::user();

        if(!$user)
        {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        $user->update([
            'password'=>bcrypt($request->password),
            'otp' => ''
        ]);

        DB::table('password_resets')->where('email', $user->email)->delete();

        $user  = User::where('email',$user->email)->first();
        // Mail::to($user->email)->send(new ChangePassword($user));

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.updated_password");
        $response['data'] = [];
        return response()->json($response, 200);

    }

    public function changePassword_lawyer(Request $request)
    {
        $validate = Validator::make($request->all(), [
			'password' => 'required|string|min:8|confirmed',
		]);

        if($validate->fails())
		{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
		}
        $user  = Auth::user();

        if(!$user)
        {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        $user->update([
            'password'=>bcrypt($request->password),
            'otp' => ''
        ]);

        DB::table('password_resets')->where('email', $user->email)->delete();

        $user  = Lawyer::where('email',$user->email)->first();
        // Mail::to($user->email)->send(new ChangePassword($user));
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.updated_password");
        $response['data'] = [];
        return response()->json($response, 200);
    }    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if($validator->fails()){
                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("validation errors");
                $response['data'] = $validator->errors();
                return response()->json($response, 200);
        }

        $user  = Auth::user();

        if(!$user)
        {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found_user");
            $response['data'] = [];
            return response()->json($response, 200);
        }

        if($user->code != $request->code){
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found_code");
            $response['data'] = [];
            return response()->json($response, 200);
        }else{
            $user->update([
                'password'=>bcrypt($request->password),
                'code' => ''
            ]);
            DB::table('password_resets')->where('email', $user->email)->delete();

            $user  = User::where('email',$user->email)->first();
            // Mail::to($user->email)->send(new ChangePassword($user));
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans("api.updated_password");
            $response['data'] = [];
            return response()->json($response, 200);
        }
    }
}
