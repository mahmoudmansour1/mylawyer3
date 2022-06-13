<?php

namespace App\Admin\Controllers;

use App\User;
use Carbon\Carbon;
use Mail;
use App\Mail\RequestServiceMail;
use App\Mail\StatusMail;
use Encore\Admin\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Addresse;
use App\Models\Area;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Days;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TimeSlot;
use App\Models\Requests;
use App\Models\Status;
use App\Models\Payment;
use App\Models\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Admin;
class RequestController extends Controller
{
    use HasResourceActions;
    /**
     * Title for current resource.
     *
     * @var string
     */
    // protected $title = 'Request';

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        \Session::forget('error');

        Notification::where('admin_read',0)->update(['admin_read'=>1]);
        return $content
            ->header('Request')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Request')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Request')
            ->body($this->form($id)->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */

    public function create(Content $content)
    {
        return $content
            ->header('Request')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Requests());

        $grid->disableColumnSelector();
        $grid->disableExport();

        $grid->filter(function ($filter) {
            // $filter->disableIdFilter();
            $filter->like('status_id', 'Status')->select(Status::all()->pluck('name_en', 'id'));
            $filter->like('payment_id', 'Payment')->select(Payment::all()->pluck('name_en', 'id'));
            $filter->between('req_date', 'Date')->date();
        });

        $grid->actions(function ($actions) {
			$actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('number_request', __('Request Id'));
        $grid->column('user_name', __('Customer name'));
        $grid->column('req_date', __('Request date'));
        $grid->column('req_time', __('Request time'))->sortable();
        $grid->column('job_date', __('Job date'));
        $grid->column('status.name_en', __('Current status'));
        $grid->column('payment.name_en', __('Payment method'));
        $grid->column('paymentStatus.name_en', __('Payment status'));
        $grid->column('amount', __('Amount'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->model()->orderBy('id', 'desc');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Requests::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('number_request', __('Number request'));
        $show->field('service_id', __('Service id'));
        $show->field('service_info', __('Service info'));
        $show->field('user_id', __('User id'));
        $show->field('user_phone', __('User phone'));
        $show->field('user_info', __('User info'));
        $show->field('addresse_info', __('Addresse info'));
        $show->field('car_make', __('Car make'));
        $show->field('car_model', __('Car model'));
        $show->field('car_years', __('Car years'));
        $show->field('instruction', __('Instruction'));
        $show->field('amount', __('Amount'));
        $show->field('discount', __('Discount'));
        $show->field('req_date', __('Req date'));
        $show->field('job_date', __('Job date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;

    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null )
    {
        $form = new Form(new Requests());
        $requestFields = new Requests() ;
        $seriveRequestFields = null ;
        $seriveAddressFields = null ;

        if($id)
        {
            $requestFields = Requests::find($id);
            $seriveRequestFields = json_decode($requestFields->service_info);
            $seriveAddressFields = json_decode($requestFields->addresse_info);
        }
        $request = new Request();
        $form->tools(function (Form\Tools $tools) {
            // Disable `Veiw` btn.
            $tools->disableView();
            // Disable `Delete` btn.
            $tools->disableDelete();
        });

        $form->footer(function ($footer) {
            // disable `View` checkbox
            $footer->disableViewCheck();

            // disable reset btn
            $footer->disableReset();
        });

        $seriveFields = new \App\Http\Controllers\API\ServiceController();
        $request->id = 1 ;
        $fields = $seriveFields->getServiceDetails($request);
        $fields =$fields->getData('service')['service'] ;

        $form->select('service_id', __('Service '))->options(Service::withTrashed()->get()->pluck('name_en', 'id'))->load('requestinputs','/admin/requestinputs')->required();

        $form->select('car_make_id', __('Car make'))->options(CarMake::withTrashed()->get()->pluck('name','id'))->load('car_model_id','/admin/car_model')->required();
        $form->select('car_model_id', __('Car model'))->options(CarModel::withTrashed()->get()->pluck('name','id'))->required();
        $form->text('car_years', __('Car years'))->required();
        $form->text('car_license_plate', __('Car license plate'));
        $form->html("<div class='empty_services_div' id='empty_services_div'></div>");
        $form->html($this->requestinputs( $request , $id));
        $form->select('user_id', __('User id'))->options(User::all()->pluck('email','id')->toArray())->load('address_id','/admin/requestaddress');

        $form->select('address_id', __('Address '));
        $form->text('user_name', __('User name'))->attribute(['id','id_user_name']);
        $form->text('user_email', __('User email'))->attribute(['id','id_user_email']);
        $form->text('user_phone', __('User phone'))->attribute(['id','id_user_phone']);

        $form->select('addresse_area_id', __('Addresse Area'))->options(Area::all()->pluck('name','id')->toArray())->default(isset($seriveAddressFields->addresse_area_id)?$seriveAddressFields->addresse_area_id:'')->required();
        $form->text('addresse_block', __('Addresse Block'))->default(isset($seriveAddressFields->addresse_block)?$seriveAddressFields->addresse_block:'')->attribute(['id','id_address_block'])->required();
        $form->text('addresse_street', __('Addresse Street'))->default(isset($seriveAddressFields->addresse_street)?$seriveAddressFields->addresse_street:'')->attribute(['id','id_address_street'])->required();
        $form->text('addresse_building', __('Addresse Building'))->default(isset($seriveAddressFields->addresse_building)?$seriveAddressFields->addresse_building:'')->attribute(['id','id_address_building'])->required();
        $form->text('addresse_extra_info', __('Addresse Extra Info '))->default(isset($seriveAddressFields->addresse_extra_info)?$seriveAddressFields->addresse_extra_info:'')->attribute(['id','id_address_extra_info']);

        $form->decimal('amount', __('Amount'))->default(0.00);
        $states = [
            'off' => ['value' => 0, 'text' => 'disabled', 'color' => 'danger'],
            'on' => ['value' => 1, 'text' => 'enabled', 'color' => 'success'],
        ];
        $form->switch('discount', 'Discount')->states($states)->disable();
        $form->text('discount_from', __('Discount From Date'))->disable();
        $form->text('discount_to', __('Discount To Date'))->disable();

        $form->date('req_date', __('Req date'))->required();
        $form->time('req_time', __('Req time'))->required();
        $form->text('job_date', __('Job date'));
        $form->select('status_id', __('Status'))->options(Status::all()->pluck('name','id')->toArray());
        $form->textarea('reason', __('Reason'));
        $form->hidden('user_deleted', __('User deleted'));
        $form->hidden('service_type_hidden')->default(isset($seriveRequestFields->service_type)?$seriveRequestFields->service_type:'')->addElementClass('service_type_hidden');
        $form->hidden('tire_type_hidden')->default(isset($seriveRequestFields->tire_type)?$seriveRequestFields->tire_type:'')->addElementClass('tire_type_hidden');

        return $form;
    }
    public function requestinputs(Request $request , $id = null )
    {

        $seriveFields = new \App\Http\Controllers\API\ServiceController();
        $request->id = 1 ;
        $seriveRequestFields = null ;
        $seriveAddressFields = null ;
        if($id)
        {
            $requestFields = Requests::find($id);
            $request->id = $requestFields->service_id ;
            $seriveRequestFields = json_decode($requestFields->service_info);
            $seriveAddressFields = json_decode($requestFields->addresse_info);
        }
        $fields = $seriveFields->getServiceDetails($request);
        $fields =$fields->getData('service')['service'] ;
        return view('admin.requests.service')
            ->with('fields', $fields)
            ->with('seriveRequestFields', $seriveRequestFields)
            ->render();
    }
    public function store(Request $request)
    {
        $service = Service::find($request->service_id);

        $photo1 = null;
        $photo2 = null;
        if($request->file('photo1') != null){

            $file = $request->file('photo1');
            $photo1 = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $photo1 = 'uploads/requests/'.$photo1.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$photo1);
        }
        if($request->file('photo2') != null){

            $file = $request->file('photo2');
            $photo2 = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $photo2 = 'uploads/requests/'.$photo2.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$photo2);

        }

        if($request->user_id)
        {
            $user = User::find($request->user_id);
            $user_name  = $user->name;
            $user_email  = $user->email;
            $user_phone  = $user->phone_prefix.' '. $user->phone;
        }
        else
        {
            $user_name  = $request->user_name;
            $user_email  = $request->user_email;
            $user_phone  = $request->user_phone;
        }
        if($request->address_id)
        {
            $address = Addresse::find($request->address_id);
            $area = Area::find($address->area_id);
            $addresse_area_id = $address->area_id;
            $addresse_area = $area->name_en;
            $addresse_block = $address->block;
            $addresse_street = $address->street;
            $addresse_building = $address->building;
            $addresse_extra_info = $address->extra_info;
        }
        else
        {
            $area = Area::find($request->addresse_area_id);
            $addresse_area_id = $request->addresse_area_id;
            $addresse_area = $area->name_en;
            $addresse_block = $request->addresse_block;
            $addresse_street = $request->addresse_street;
            $addresse_building = $request->addresse_building;
            $addresse_extra_info = $request->addresse_extra_info;
        }
        $order = new Requests();
        $order->user_id = $request->user_id;

        $order->number_request = $this->generateRequestUniqueNumber();
        $order->service_id = $request->service_id;
        $service_type = $request->service_type;

        if($service_type != null){

           $service_type = implode(",",$request->service_type);

        }
        $order->service_id = $request->service_id;
        //dd($request->special_request);
        $order->service_info = json_encode([
            'service_name'=> $service->name,
            'service_type'=> $service_type,
            'front_tire_size'=>$request->front_tire_size,
            'back_tire_size'=>$request->back_tire_size,
            'tire_type'=>$request->tire_type,
            'chassis_numb'=>$request->chassis_numb,
            'numb_cylind'=>$request->numb_cylind,
            'front_rim_size'=>$request->front_rim_size,
            'back_rim_size'=>$request->back_rim_size,
            'numb_tire'=>$request->numb_tire,
            'request_details'=>$request->request_details,
            'special_request'=>$request->special_request,
            'photo1'=>$photo1,
            'photo2'=>$photo2,

        ]);
        $order->user_name = $user_name;
        $order->user_email = $user_email;
        $order->user_phone = $user_phone;
        $order->addresse_info = json_encode([
            'addresse_area_id'=>$addresse_area_id,
            'addresse_area'=>$addresse_area,
            'addresse_block'=>$addresse_block,
            'addresse_street'=>$addresse_street,
            'addresse_building'=>$addresse_building,
            'addresse_extra_info'=>$addresse_extra_info,
        ]);
        $order->car_make_id = $request->car_make_id;
        $order->car_model_id = $request->car_model_id;
        $order->car_years = $request->car_years;
        $order->car_license_plate = $request->car_license_plate;
        $order->discount = $service->discount;
        $order->discount_from = $service->discount_from;
        $order->discount_to = $service->discount_to;
        $order->req_date = $request->req_date;
        $order->job_date = $request->job_date;
        $order->amount = $request->amount;
        $order->req_time = $request->req_time;
        $order->status_id = $request->status_id;



        if($request->user_id){
            $user = User::find($request->user_id);
            if($user->is_notified){

                $setting = Setting::first();
                $title = $setting->title_notifications_new_order;
                $title_rep = str_replace('[name]', $order->user_name, $title);
                $number_request = $order->number_request;
                $desc = $setting->desc_notifications_new_order;
                $desc_rep = str_replace('[number_order]', $number_request, $desc);
                //dd($user->id);
                sendNotification($user->id , $order->id, $title_rep, $desc_rep);


            }
        }



        $order->save();

        $order = Requests::with(['service','make','model'])->where('id',$order->id)->first();

        Mail::to($order->user_email)->send(new RequestServiceMail($order));

        $setting = Setting::first();
        if($setting->email_req_submission != null || $setting->email_req_submission != ''){
            $recipients = explode(',', $setting->email_req_submission);
            Mail::to($recipients)->send(new RequestServiceMail($order));
        }

        return redirect()->route('admin.requests.index');
    }
    public function update(Request $request , $id)
    {
        // dd($request);
        $service = Service::find($request->service_id);

        $photo1 = $request->last_photo1;
        $photo2 = $request->last_photo2;
        if($request->file('photo1') != null){
            $file = $request->file('photo1');
            $photo1 = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $photo1 = 'uploads/requests/'.$photo1.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$photo1);
        }
        if($request->file('photo2') != null){
            $file = $request->file('photo2');
            $photo2 = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $photo2 = 'uploads/requests/'.$photo2.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$photo2);

        }

        if($request->user_id)
        {
            $user = User::find($request->user_id);
            $user_name  = $user->name;
            $user_email  = $user->email;
            $user_phone  = $user->phone_prefix.' '. $user->phone;
        }
        else
        {
            $user_name  = $request->user_name;
            $user_email  = $request->user_email;
            $user_phone  = $request->user_phone;
        }
        if($request->address_id)
        {
            $address = Addresse::find($request->address_id);
            $area = Area::find($address->area_id);
            $addresse_area_id = $address->area_id;
            $addresse_area = $area->name_en;
            $addresse_block = $address->block;
            $addresse_street = $address->street;
            $addresse_building = $address->building;
            $addresse_extra_info = $address->extra_info;
        }
        else
        {
            $area = Area::find($request->addresse_area_id);
            $addresse_area_id = $request->addresse_area_id;
            $addresse_area = $area->name_en;
            $addresse_block = $request->addresse_block;
            $addresse_street = $request->addresse_street;
            $addresse_building = $request->addresse_building;
            $addresse_extra_info = $request->addresse_extra_info;
        }
        $order = Requests::find($id);
        $orserStatus = $order->status_id;
        $anc_status = $order->status_id;
        $order->user_id = $request->user_id;

        $service_type = $request->service_type;

        if($service_type != null){

           $service_type = implode(",",$request->service_type);

        }
        $order->service_id = $request->service_id;
        //dd($request->special_request);
        $order->service_info = json_encode([
            'service_name'=> $service->name,
            'service_type'=> $service_type,
            'front_tire_size'=>$request->front_tire_size,
            'back_tire_size'=>$request->back_tire_size,
            'tire_type'=>$request->tire_type,
            'chassis_numb'=>$request->chassis_numb,
            'numb_cylind'=>$request->numb_cylind,
            'front_rim_size'=>$request->front_rim_size,
            'back_rim_size'=>$request->back_rim_size,
            'numb_tire'=>$request->numb_tire,
            'request_details'=>$request->request_details,
            'special_request'=>$request->special_request,
            'photo1'=>$photo1,
            'photo2'=>$photo2,
        ]);


        $order->user_name = $user_name;
        $order->user_email = $user_email;
        $order->user_phone = $user_phone;
        $order->addresse_info = json_encode([
            'addresse_area_id'=>$addresse_area_id,
            'addresse_area'=>$addresse_area,
            'addresse_block'=>$addresse_block,
            'addresse_street'=>$addresse_street,
            'addresse_building'=>$addresse_building,
            'addresse_extra_info'=>$addresse_extra_info,
        ]);
        $order->car_make_id = $request->car_make_id;
        $order->car_model_id = $request->car_model_id;
        $order->car_years = $request->car_years;
        $order->car_license_plate = $request->car_license_plate;
        $order->discount = $service->discount;
        $order->discount_from = $service->discount_from;
        $order->discount_to = $service->discount_to;
        $order->req_date = $request->req_date;
        $order->job_date = $request->job_date;
        $order->amount = $request->amount;
        $order->req_time = $request->req_time;
        $order->status_id = $request->status_id;
        $order->reason = $request->reason;




        $order->save();


        $setting = Setting::first();
        $title = $setting->title_notifications_change_status;
        $title_rep = str_replace('[name]', $order->user_name, $title);
        $number_request = $order->number_request;
        $desc = $setting->desc_notifications_change_status;
        $desc_rep = str_replace('[number_order]', $number_request, $desc);

        if(Requests::COMPLETED_JOB == $request->status_id){
            $status_text = "Job completed";
            $desc_rep = str_replace('[status]', $status_text, $desc_rep);

        }if(Requests::CANCALLED_JOB == $request->status_id){
            $status_text = "Canceled";
            $desc_rep = str_replace('[status]', $status_text, $desc_rep);

        }

        if(isset($status_text))
        {
            $desc_rep = str_replace('[status]', $status_text, $desc_rep);
        }



        $order = Requests::with(['service','make','model','status'])->where('id',$order->id)->first();

        if($request->status_id != $anc_status){
            Mail::to($order->user_email)->send(new StatusMail($order));
            if($request->user_id){
                if($user->is_notified && in_array($request->status_id , [Requests::COMPLETED_JOB , Requests::CANCALLED_JOB])){
                   // dd($title_rep);
                    sendNotification($request->user_id , $order->id, $title_rep, $desc_rep);
                }
            }
        }

        return redirect()->route('admin.requests.index');

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
    public function requestaddress(Request  $request)
    {
            $provinceId = $request->get('q');

            $branches =  Addresse::where('user_id', $provinceId)->get();
            $branchesArr = [] ;
            foreach ($branches as $branch)
            {
                $branchesArr[]=[
                    'id'=>$branch->id,
                    'text'=>$branch->addresse_name,
                    'title'=>$branch->addresse_name
                ];
            }
            return $branchesArr;
    }
    public function car_model(Request  $request)
    {
            $provinceId = $request->get('q');

            $branches =  CarModel::where('make_id', $provinceId)->get();
            $branchesArr = [] ;
            foreach ($branches as $branch)
            {
                $branchesArr[]=[
                    'id'=>$branch->id,
                    'text'=>$branch->name,
                    'title'=>$branch->name
                ];
            }
            return $branchesArr;
    }
    public function addressDetails(Request $request)
    {
        $address = Addresse::find($request->id);
        return $address;
    }
}
