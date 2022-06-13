<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactUsMail;
use Mail;
use Auth;

use App\Models\Area;
use App\Models\Banner;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Contact;
use App\Specialty;
use App\Lawyer;
use App\Subject;
use App\Models\Status;

use App\Http\Resources\Area as AreaResource;
use App\Http\Resources\SpecialtyResource;
use App\Http\Resources\SpecialtyResource1;
use App\Http\Resources\SpecialtyResource2;
use App\Http\Resources\Specialty as Specialtycoll;
use App\Http\Resources\LawyersResource;
use App\Http\Resources\RelatedLawyers;
use App\Http\Resources\Total;
use App\Http\Resources\test;
use App\Http\Resources\SubjectsResource;
use App\Http\Resources\StatusResource;
use App\Http\Resources\Setting as SettingResource;
use App\Http\Resources\Faq as FaqResource;
use App\Http\Resources\Page as PageResource;
use App\Http\Resources\Banner as BannerResource;
class HomeController extends Controller
{
    public function search(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        // ]);
        // if($validator->fails()){
        //     $response['status'] = 'error';
        //     $response['api_status'] = 400;
        //     $response['message'] = trans("validation errors");
        //     $response['data'] = $validator->errors();
        //     return response()->json($response, 200);
        // }
        $lang = $request->header('X-localization');


        $check = Lawyer::where('name','like','%'.$request->name.'%')->first();
        if($check){

            $posts = Lawyer::where('name','like','%'.$request->name.'%');

        }else{
            $name = $request->name;
            
            $posts = Lawyer::whereHas('specialties', function($q) use($name,$lang) {
                $q->where('name_'.$lang,'like','%'.$name.'%');
            });

        }
        if($request->gender != "")
        {
            $posts->where('gender', $request->gender);
        }
        if($request->specialty)
        {
            $specialty_id = $request->specialty;
            $posts->whereHas('specialties', function($q) use($specialty_id) {
                    $q->where('specialty_id', $specialty_id);
                });
        }

        if($request->category)
        {
            $posts->whereIn('category_id',$request->category);
        }
        if($request->from_price)
        {
            $posts->where('consultations_fees','>=',$request->from_price);
        }
        if($request->to_price)
        {
            $posts->where('consultations_fees','<=',$request->to_price);
        }
        if($request->order_by_name)
        {
            $posts->orderBy("name", $request->order_by_name);
        }
        if($request->order_by_consultations_fees)
        {
            $posts->orderBy("consultations_fees", $request->order_by_consultations_fees);
        }
        if($request->order_by_number_consultations)
        {
            $posts->orderBy("number_consultations", $request->order_by_number_consultations);
        }        
        $posts = $posts->get();
      //  dd($posts->toSql());


        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = RelatedLawyers::collection($posts);
        return response()->json($response, 200);
    }        
    
    public function getSpecialty_mini()
    {
        $specialties = Specialty::where('is_active','1')->get();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = Specialtycoll::collection($specialties);
        return response()->json($response, 200);

    }    




    public function getSpecialty()
    {
        $specialties = Specialty::where('is_active','1')->get();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = SpecialtyResource::collection($specialties);
        return response()->json($response, 200);

    }   
    public function getSpecialty_lawyers(Request $request)
    {
        $specialties = Specialty::where('is_active','1')->where('id', $request->specialty_id)->paginate(10);
//dd($specialties);
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = SpecialtyResource2::collection($specialties);
        return response()->json($response, 200);

    }       
    public function getSpecialty_update()
    {
        $specialties = Specialty::where('is_active','1')->get();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = SpecialtyResource1::collection($specialties);
        return response()->json($response, 200);

    }         
    
    public function getLawyers()
    {

        $lawyers = Lawyer::where('is_active','1')->limit(10)->get();
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = LawyersResource::collection($lawyers);
        return response()->json($response, 200);

    }
    
    public function get_total(Request $request)
    {
      //  $lawyer = Lawyer::where('id', $request->lawyer_id)->get();
        $lawyer = Lawyer::where('id', $request->lawyer_id)->get();
        if($lawyer[0]->commission == 0){
            $settings = Setting::first();
            $commission = $settings->commission; 
        }else{
            $commission = $lawyer[0]->commission; 
        }

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = Total::collection($lawyer);
        return response()->json($response, 200);

    }
    public function getLawyer(Request $request)
    {

      //  $lawyer = Lawyer::where('id', $request->lawyer_id)->get();
        $lawyer = Lawyer::where('id', $request->lawyer_id)->get();
     // dd($lawyer);        

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = LawyersResource::collection($lawyer);
        return response()->json($response, 200);
    }

    
    public function getSubjects(Request $request)
    {

      //  $lawyer = Lawyer::where('id', $request->lawyer_id)->get();
        $subject = Subject::all();
     // dd($lawyer);        

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = SubjectsResource::collection($subject);
        return response()->json($response, 200);
    }   
    
    public function get_status(Request $request)
    {

      //  $lawyer = Lawyer::where('id', $request->lawyer_id)->get();
        $status = Status::all();
     // dd($lawyer);        

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = StatusResource::collection($status);
        return response()->json($response, 200);
    }   
    

    public function getBanners(){
        $banners = Banner::all();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = BannerResource::collection($banners);
        return response()->json($response, 200);

    }

    public function settings()
    {

        $settings = Setting::all();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = SettingResource::collection($settings);

        return response()->json($response, 200);
    }

    public function storeContactUs(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'subject_id' => ['required'],
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'message' => ['required' ],
        ]);

        if ($validate->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        Contact::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);

        $setting = Setting::first();
        if($setting->email_contact_us != null || $setting->email_contact_us != ''){
            $recipients = explode(',', $setting->email_contact_us);
          //  Mail::to($recipients)->send(new ContactUsMail($request->all()));
        }


        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans("api.contact_us_success_message");
        $response['data'] = [];
        return response()->json($response, 200);
    }

    public function faqs()
    {
        $faq = Faq::all();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = FaqResource::collection($faq);
        return response()->json($response, 200);
    }

    public function getPage(Request $request)
    {

        $page = Page::where('slug', $request->slug)->get();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = PageResource::collection($page);
        return response()->json($response, 200);
    }

}