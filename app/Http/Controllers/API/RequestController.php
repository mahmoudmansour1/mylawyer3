<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Mail\ReSheduleMail;
use App\Mail\RequestServiceMail;
use Mail;
use App\Consultation;
use App\Message;
use App\Dispute;

use Carbon\Carbon;
use App\User;
use App\Lawyer;
use App\FilesConsultation;
use App\Models\Requests;
use App\Models\Days;
use App\Models\Area;
use App\Models\TimeSlot;
use App\Models\Service;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Setting;
use App\Models\Notification;

use App\Http\Resources\Request as RequestResource;
use App\Http\Resources\ConsultationResource;
use App\Http\Resources\MessagesResource;
use App\Http\Resources\ReportResource;
use App\Http\Resources\ReportResource;
use App\Http\Resources\testResource;


class RequestController extends Controller
{
    public function __construct(){
        Auth::shouldUse('api');
    }

    public function placeRequest(Request $request)
    {


        //dd();
        if(!Auth::user())
        {

            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.Unauthorized");
            $response['data'] = [];
            return response()->json($response, 200);



        }    
            $validate = Validator::make($request->all(), [
                'subject' => 'required',
                'description' => 'required',
            //  'file' => 'required',
                'file.*' => 'mimes:jpeg,png,jpg,docx,pdf,zip,rar|max:2000',
                'lawyer_id' => 'required',
                'payment_id' => 'required'

                
            ]);


        // }

        if ($validate->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }



        $user = Auth::user();


        if($request->lawyer_id)
        {
            $lawyer = Lawyer::find($request->lawyer_id);
            $lawyer_name  = $lawyer->name;
        //    $lawyer_phone  = $lawyer->phone_prefix.' '. $lawyer->phone;
            $lawyer_phone  = $lawyer->phone;
        }

        if($lawyer->commission == 0){
            $settings = Setting::first();
            $commission = $settings->commission; 
        }else{
            $commission = $lawyer->commission; 
        }

        $total = $commission + $lawyer->consultations_fees;

        $Consultation = new Consultation();

      //  dd($user->wallet);
        if($request->payment_id == 2){

            if($user->wallet < $total){


                $response['status'] = 'error';
                $response['api_status'] = 400;
                $response['message'] = trans("api.wallet");
                $response['data'] = [];
                return response()->json($response, 200);
            }else{


                $Consultation->payment_status_id = 1;


                
            }

        }else{

            $Consultation->payment_status_id = 2;


        }


        $Consultation->subject = $request->subject;
        $Consultation->amount = $total;
        $Consultation->commission = $commission;
        $Consultation->consultation_fees = $lawyer->consultations_fees;
        $Consultation->description = $request->description;
        $Consultation->customer_name = $user->name;
        $Consultation->customer_phone = $user->phone;
        $Consultation->lawyer_name = $lawyer_name;
        $Consultation->lawyer_phone = $lawyer_phone;
        $Consultation->status_id = 1;
        $Consultation->payment_id = $request->payment_id;
        $Consultation->user_id = $user->id;
        $Consultation->lawyer_id = $request->lawyer_id;
        $Consultation->consultation_number = $this->generateRequestUniqueNumber();

        $Consultation->save();
        

        if($request->hasfile('file')) { 
            $count = 0;
            foreach($request->file('file') as $file)
            {
                $count++;
                if($count <= 5){
                $fileName = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = 'uploads/requests/'.$fileName.'.'.$file->getClientOriginalExtension();
                $file->move('/var/www/html/public/uploads/requests',$fileName);
                $input['file'] = $fileName;
                $input['consultation_id'] = $Consultation->id;
                FilesConsultation::create($input);

                }
            }
        } 


        $apiURL = env('FATOORAH_API_URL');
        $apiKey =  env('FATOORAH_API_KEY');
        $keyId   = 1;
        $KeyType = 'paymentId';
        $postFields = [
            'Key'     => $keyId,
            'KeyType' => $KeyType
        ];
       // dd($apiKey);
       $postFields = [
        //Fill required data
        'paymentMethodId' => 1,
        'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
        'InvoiceValue'       => 30,
        'CallBackUrl'        => url('api/auth/callback_success'),
        'ErrorUrl'           => url('api/auth/callback_fail'),        
        //Fill optional data
        //'DisplayCurrencyIso' => 'KWD',
        'CustomerReference'  => $Consultation->id,
        'UserDefinedField'     =>  $lawyer->number_consultations,

        

//                'InvoiceItems'       =>   $cartUnitProducts->map(function ($elm){
//                    return collect($elm->toArray())
//                        ->only(['ItemName','Quantity','UnitPrice'])
//                        ->all();
//                })->toArray(),
    ];



    if($request->payment_id == 1){
        $data = $this->executePayment($apiURL, $apiKey, $postFields);
        return response()->json($data);

    }






     

 

        // $order = Requests::with(['service','make','model'])->where('id',$order->id)->first();

        // Mail::to($order->user_email)->send(new RequestServiceMail($order));
        // if($user_id){
        //     $user = User::find($user_id);
        //     if($user->is_notified){

        //         $setting = Setting::first(); 
        //         $title = $setting->title_notifications_new_order;   
        //         $title_rep = str_replace('[name]', $order->user_name, $title);
        //         $number_request = $order->number_request;
        //         $desc = $setting->desc_notifications_new_order;   
        //         $desc_rep = str_replace('[number_order]', $number_request, $desc);
        //         //dd($user->id);

        
        //     } 
        // }
        // $setting = Setting::first();
        // if($setting->email_req_submission != null || $setting->email_req_submission != ''){
        //     $recipients = explode(',', $setting->email_req_submission);
        //     Mail::to($recipients)->send(new RequestServiceMail($order));
        // }


        // $adminNotify = new Notification();
		// $adminNotify->request_id = $order->id ;
		// $adminNotify->text = 'New Request Placed #'.$order->number_request ;
		// $adminNotify->admin_read = 0;
		// $adminNotify->save();

        sendNotification( $Consultation->user_id , null, "Consultation", 'New Consultation open #'.$Consultation->consultation_number, $send_to = "users");
        sendNotification( $Consultation->lawyer_id , null, "Consultation", 'New Consultation open #'.$Consultation->consultation_number, $send_to = "lawyer");

        $new_wallet = $user->wallet - $Consultation->amount;
        User::where('id', $Consultation->user_id)->update(['wallet' => $new_wallet]);
        //dd($user->wallet);

      //  $success['transaction_id'] = $request->Id;

        $success['status'] = 'success';
        $success['api_status'] = 200;
        $success['message'] = "";
        $success['data']['id'] = $Consultation->id;
        $success['data']['consultation_number'] = $Consultation->consultation_number;
        $success['data']['payment_method'] = $Consultation->payment->name;
        $success['data']['lawyer_name'] = $Consultation->lawyer->name;
        $success['data']['total'] = number_format($Consultation->amount, 2);
        $success['data']['created_at'] = $Consultation->created_at->format('Y-m-d H:i:s');
        return response()->json($success, 200);    




    }
    public function fail()
    {


        $response['status'] = 'payment_error';
        $response['api_status'] = 400;
        $response['message'] = "Payment Error";
        $response['data'] = [];
        return response()->json($response, 200);
    }
    public function success(Request $request)
    {
        $apiURL = env('FATOORAH_API_URL');
        $apiKey =  env('FATOORAH_API_KEY');
        $keyId   = 1;
        $KeyType = 'paymentId';
        $postFields = [
            'Key'     => $request->paymentId,
            'KeyType' => $KeyType
        ];
        $json = $this->callAPI($apiURL."v2/getPaymentStatus", $apiKey, $postFields);
        if ($json->IsSuccess){
           // dd($json->Data);
            $consultation = Consultation::where('id',$json->Data->CustomerReference)->first();
            $consultation->payment_status_id = 1;
            $consultation->transaction_id = $request->Id;
            
            $consultation->save();
            $number = $json->Data->UserDefinedField + 1;
            Lawyer::where('id', $consultation->lawyer_id)->update(['number_consultations' => $number]);
            sendNotification( $consultation->user_id , null, "Consultation", 'New Consultation open #'.$consultation->consultation_number, $send_to = "users");
            sendNotification( $consultation->lawyer_id , null, "Consultation", 'New Consultation open #'.$consultation->consultation_number, $send_to = "lawyer");

            // $closeCartService->setVisitorHash($visitorHash->visitor_tracking_hash)->setVisitorCountry($visitorHash->iso)->exec();
            // Notifications::create([
            //     'order_id' => $order->id,
            //     'text' => "Order ({$order->code}) Sent Successfully",
            //     'link' => "admin/order/{$order->code}/edit"
            // ]);
            
            // OrderTrack::create([
            //     'order_id' => $order->id,
            //     'order_status_id' => 1,
            //     'vendor_id' => 0
            // ]);

            // $customer_data = json_decode($order->address);

            // $phone_code =  $customer_data->phone_code;
            // $phone_number = $customer_data->phone_number;
            // $phone_number = $phone_code.$phone_number;

            // send_sms("New Order ".$order->code."Send Successfully", $phone_number);

            // invoice_email_to_user('invoice','invoice','/',Cache::get('email'),$order);

            // $customer_data = json_decode($order_obj->address);
            // $CustomerName = $customer_data->first_name;
            // $TotalVal = $order_obj->cart_price;
            // $TotalQty = $order_obj->totalQty;
            // $FinalValue = $order_obj->pay_amount;


            // $order_product = json_decode($order_obj->order_product);
           // dd($consultation);

            $success['status'] = 'payment_success';
            $success['api_status'] = 200;
            $success['message'] = "";
            $success['data']['consultation_number'] = $consultation->consultation_number;
            $success['data']['transaction_id'] = $request->Id;
            $success['data']['payment_method'] = $consultation->payment->name;
            $success['data']['lawyer_name'] = $consultation->lawyer->name;
            $success['data']['total'] = number_format($consultation->amount, 2);
            $success['data']['created_at'] = $consultation->created_at->format('Y-m-d H:i:s');
            return response()->json($success, 200);   

        }


        $response['status'] = 'error';
        $response['api_status'] = 400;
        $response['message'] = "Payment Error";
        $response['data'] = [];
        return response()->json($response, 200);
    }

    public function handleError($response) {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }

        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));

        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }
    public function initiatePayment($apiURL, $apiKey, $postFields) {
        $json = $this->callAPI("$apiURL/v2/InitiatePayment", $apiKey, $postFields);
        return $json->Data->PaymentMethods;
    }


    public function executePayment($apiURL, $apiKey, $postFields) {
        $json = $this->callAPI("$apiURL/v2/ExecutePayment", $apiKey, $postFields);
       // return $json->Data;
        $success['status'] = 'success';
        $success['api_status'] = 200;
        $success['message'] = "";
        $success['data'] = $json->Data;

        return response()->json($success, 200); 
    }

    public  function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST') {
      //  dd(json_encode($postFields));
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);

        curl_close($curl);

        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }

        $error = $this->handleError($response);
        if ($error) {
            throw new \Exception(json_encode($error));
         //   die("Error: $error");
        }

        return json_decode($response);
    }

    public function generateRequestUniqueNumber()
	{
		$requestUniqueNumber = rand(10000000, 99999999);
		$request = Requests::where('number_request', $requestUniqueNumber)->first();

		if (!$request) {
			return $requestUniqueNumber;
		}

		$this->generateRequestUniqueNumber();
	}
    public function getRequestsType_lawyer(Request $request)
    {
        if($request->type == 'active'){
            $requests = Consultation::where('status_id', 1)->where('payment_status_id', 1)->where('lawyer_id',Auth::Id())->orderBy("id", "desc")->get();
        
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = ConsultationResource::collection($requests);

        
            return response()->json($response, 200);
        }elseif($request->type == 'history'){
            $requests = Consultation::whereIn('status_id', [2,3,4])->where('payment_status_id', 1)->where('lawyer_id',Auth::Id())->orderBy("id", "desc")->get();
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = ConsultationResource::collection($requests);
            return response()->json($response, 200);
        }
    }


    public function getRequestsType(Request $request)
    {
        
        if($request->type == 'active'){
            $requests = Consultation::where('status_id', 1)->where('payment_status_id', 1)->where('user_id',Auth::Id())->orderBy("id", "desc")->get();
           // with('skus')
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = ConsultationResource::collection($requests);
            return response()->json($response, 200);
        }elseif($request->type == 'history'){
            $requests = Consultation::whereIn('status_id', [2,3,4])->where('payment_status_id', 1)->where('user_id',Auth::Id())->orderBy("id", "desc")->get();
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = ConsultationResource::collection($requests);
            return response()->json($response, 200);
        }
        
    }
    
    public function report(Request $request)
    {

        $requests = Consultation::where('lawyer_id', Auth::Id())->where('payment_status_id', 1);


        if($request->status)
        {
            $requests->where('status_id', $request->status);
        }
        if($request->from_date)
        {
          //  $requests->whereDate('created_at', '>=', );
            $requests->where('created_at', '>=', $request->from_date.' 00:00:00');

        }
        if($request->to_date)
        {
            $requests->where('created_at', '<=', $request->to_date.' 23:59:59');

        }
      
            $requests->orderBy("id", "desc");

            
        $requests = $requests->get();
      //  dd($requests->toSql());
      $total_amount = 0;
      foreach ($requests as $order)
      {
          $total_amount +=$order->consultation_fees;
      }
      
      $response['status'] = 'success';
      $response['api_status'] = 200;
      $response['message'] = "";
      $response['data'] = ReportResource::collection($requests);
      $response['total_amount'] = $total_amount;
      $response['total_consultations'] = $requests->count();
      return response()->json($response, 200);
    }        
    public function wallet(Request $request)
    {

        $requests = Consultation::where('user_id', Auth::Id())->where('payment_status_id', 1)->where('payment_id', 2);
        $requests->orderBy("id", "desc");

            
        $requests = $requests->get();
        //  dd($requests->toSql());

      
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = ReportResource::collection($requests);
        return response()->json($response, 200);

    }     
        
    public function week_sales(Request $request)
    {

        $requests = Consultation::where('lawyer_id', Auth::Id())->where('payment_status_id', 1);
        $requests->where('created_at', '>=', Carbon::now()->subdays(7));


            
        $requests = $requests->get();
      //  dd($requests->toSql());
      $total_amount = 0;
      foreach ($requests as $order)
      {
          $total_amount +=$order->consultation_fees;
      }
      
      $response['status'] = 'success';
      $response['api_status'] = 200;
      $response['message'] = "";
      $response['data']['total_amount'] = $total_amount;

      return response()->json($response, 200);
    }        
    public function getRequestDetails(Request $request)
    {
        $requests = Consultation::find($request->id);
        //dd($requests);

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = new ConsultationResource($requests);
        return response()->json($response, 200);
    }
    public function latestRequestDetails()
    {
       // $requests = Consultation::where('user_id', Auth::Id())->where('payment_status_id', 1)->get()->limit;
        $requests = Consultation::Where('payment_status_id',1)->get();

        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = new testResource($requests);

        return response()->json($response, 200);

    }    
    public function getMessages(Request $request)
    {
        $messages = Consultation::find($request->id);
        //dd($messages);
        if(!$messages){
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = [];
            return response()->json($response, 200);  
        }
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = new MessagesResource($messages);
        return response()->json($response, 200);
    }
    public function messages_admin(Request $request)
    {
        $messages = Consultation::find($request->id);
        //dd($messages);
        if(!$messages){
            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = "";
            $response['data'] = [];
            return response()->json($response, 200);  
        }
        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = "";
        $response['data'] = new MessagesResource($messages);
        return response()->json($response, 200);
    }

    
    public function cancelRequest(Request $request)
    {
        $requests = Consultation::where('id', $request->id)
                            ->update(['status_id' => 4,"refund_method" => $request->refund_method]);

        $requests = Consultation::find($request->id);
        sendNotification( $requests->user_id , null, "Consultation", 'Changed status '.$requests->status->name.' #'.$requests->consultation_number, $send_to = "users");
        sendNotification( $requests->lawyer_id , null, "Consultation", 'Changed status '.$requests->status->name.' #'.$requests->consultation_number, $send_to = "lawyer");

        // if(count($requests->messages) != 0){
        //     dd($requests->messages);

        // }else{
        //     dd("else");

        // }
        if($request->refund_method == 1){
            $user = Auth::user();

            $new_wallet = $user->wallet + $requests->amount;
            //dd($user);
            User::where('id', $requests->user_id)->update(['wallet' => $new_wallet]);
        }

        if($requests){


            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans('api.success_cancel_request');
            $response['data'] = new ConsultationResource($requests);
            return response()->json($response, 200);

            
        }else{

            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found");
            $response['data'] = [];
            return response()->json($response, 200);
        }

    }
    public function terminateConsultation(Request $request)
    {
        $requests = Consultation::where('id', $request->id)
                            ->update(['status_id' => 2,'review' => $request->review,'rating' => $request->rating]);
        $requests = Consultation::find($request->id);
        sendNotification( $requests->user_id , null, "Consultation", 'Changed status '.$requests->status->name.' #'.$requests->consultation_number, $send_to = "users");
        sendNotification( $requests->lawyer_id , null, "Consultation", 'Changed status '.$requests->status->name.' #'.$requests->consultation_number, $send_to = "lawyer");

        if($requests){

            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans('api.success_terminate_request');
            $response['data'] = new ConsultationResource($requests);
            return response()->json($response, 200);



        }else{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found");
            $response['data'] = [];
            return response()->json($response, 200);
        }

    } 
    public function disputeRequest(Request $request)
    {
        
        $requests = Consultation::where('id', $request->id)
                            ->update(['status_id' => 3]);

                $input['consultation_id'] = $request->id;
                $input['type_status'] = $request->type_status;
                $input['type_accept'] = 0;
                $input['refund_method'] = 0;
                $input['message'] = $request->message;

                Dispute::create($input);


        $requests = Consultation::find($request->id);
        sendNotification( $requests->user_id , null, "Consultation", 'Changed status '.$requests->status->name.' #'.$requests->consultation_number, $send_to = "users");
        sendNotification( $requests->lawyer_id , null, "Consultation", 'Changed status '.$requests->status->name.' #'.$requests->consultation_number, $send_to = "lawyer");

        if($requests){

            $response['status'] = 'success';
            $response['api_status'] = 200;
            $response['message'] = trans('api.success_dispute_request');
            $response['data'] = new ConsultationResource($requests);
            return response()->json($response, 200);

        }else{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found");
            $response['data'] = [];
            return response()->json($response, 200);
        }

    }         
    
    public function dropRequest(Request $request)
    {
        $requests = Requests::where('id', $request->id)
                                ->update(['user_deleted' => 1]);
        if($requests){
            $success['status'] = 'success';
            $success['api_status'] = 200;
            $success['message'] = trans('api.success_delete_request');
            return response()->json($success, 200);
        }else{
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("api.not_found");
            return response()->json($response, 200);
        }
    }



    public function addMessage(Request $request)
    {


        //dd();
        // if(!Auth::user())
        // {
            //dd($request->file('voice'));
            $validate = Validator::make($request->all(), [
                'lawyer_id' => 'required',
                'user_id' => 'required',
                'consultation_id' => 'required',
                'type_status' => 'required',
                'type_message' => 'required',
                'text' => 'required_if:type_message,==,0|max:2000',
                'file' => 'required_if:type_message,==,1|mimes:jpeg,png,jpg,docx,pdf,zip,rar|max:2000',
               // 'voice' => 'required_if:type_message,==,2|mimes:wav,mp3,m4a',

            ]);


        // }

        if ($validate->fails()) {
            $response['status'] = 'error';
            $response['api_status'] = 400;
            $response['message'] = trans("validation errors");
            $response['data'] = $validate->errors();
            return response()->json($response, 200);
        }



        $user = Auth::user();
        //dd($user);
        $message = new Message();

        $message_content = $request->text;
        $file = null;
        if($request->file('file') != null){

            $file = $request->file('file');
            $message_content = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $message_content = 'uploads/requests/'.$message_content.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$message_content);

        }


        if($request->file('voice') != null){



            $file = $request->file('voice');
            $message_content = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $message_content = 'uploads/requests/'.$message_content.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$message_content);
        }


        $message->user_id = $request->user_id;
        $message->lawyer_id = $request->lawyer_id;
        $message->consultation_id = $request->consultation_id;
        $message->type_status = $request->type_status;
        $message->type_message = $request->type_message;
        $message->status_message = 1;
        $message->message = $message_content;
        $message->save();

        $consultation = Consultation::find($request->consultation_id);

        if($request->type_status == 0){
            

            sendNotification( $request->lawyer_id , null, "Messages", 'New Message From User #'.$consultation->customer_name, $send_to = "lawyer");

        }
        if($request->type_status == 1){
            
            sendNotification( $request->user_id , null, "Messages", 'New Message From Lawyer #'.$consultation->lawyer_name, $send_to = "users");

        }



        $response['status'] = 'success';
        $response['api_status'] = 200;
        $response['message'] = trans('api.success_message');
        $response['data'] = [];
        return response()->json($response, 200);

    }

}
