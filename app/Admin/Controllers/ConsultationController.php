<?php

namespace App\Admin\Controllers;

use App\Consultation;
use Encore\Admin\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Status;
use App\User;
use App\lawyer;
use App\specialty;
use App\Models\Payment;
use App\Models\Notification;

class ConsultationController extends Controller
{


    use HasResourceActions;
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Consultation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    public function index(Content $content)
    {
        \Session::forget('error');

        Notification::where('admin_read',0)->update(['admin_read'=>1]);
        return $content
            ->header('Request')
            ->body($this->grid());
    }


    protected function grid()
    {
        $grid = new Grid(new Consultation());
	    $grid->actions(function ($actions) {

            $actions->disableDelete();

            $actions->disableView();

            $actions->append(
                '<a href="' . $actions->getResource() . '/' . $actions->getKey() . '" target="_blank" class="grid-row-edit" style="margin-right:4px">
            <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"></i></a>');

        });
        $grid->column('id', __('Id'));
        $grid->column('consultation_number', __('Consultation number'));
        $grid->column('subject', __('Subject'));
        $grid->column('customer_name', __('Customer name'));
        $grid->column('customer_phone', __('Customer phone'));
        $grid->column('lawyer_name', __('Lawyer name'));
        $grid->column('lawyer_phone', __('Lawyer phone'));
        // $grid->column('req_date', __('Req date'));
        // $grid->column('req_time', __('Req time'));
        $grid->column('status.name_en', __('Current status'));
        $grid->column('amount', __('Amount'));
        $grid->column('paymentStatus.name_en', __('Payment status'));
        $grid->column('specialty.name_en', __('specialty id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->model()->orderBy('id', 'desc');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    // protected function detail($id)
    // {
    //     $show = new Show(Consultation::findOrFail($id));

    //     $show->field('id', __('Id'));
    //     $show->field('consultation_number', __('Consultation number'));
    //     $show->field('subject', __('Subject'));
    //     $show->field('customer_name', __('Customer name'));
    //     $show->field('customer_phone', __('Customer phone'));
    //     $show->field('lawyer_name', __('Lawyer name'));
    //     $show->field('lawyer_phone', __('Lawyer phone'));
    //     $show->field('req_date', __('Req date'));
    //     $show->field('req_time', __('Req time'));
    //     $show->field('status_id', __('Status id'));
    //     $show->field('amount', __('Amount'));
    //     $show->field('payment_status_id', __('Payment status id'));
    //     $show->field('user_id', __('User id'));
    //     $show->field('lawyer_id', __('Lawyer id'));
    //     $show->field('created_at', __('Created at'));
    //     $show->field('updated_at', __('Updated at'));
    //     $show->field('deleted_at', __('Deleted at'));

    //     return $show;
    // }

    public function show($id, Content $content)
    {

        return $content
            ->header('Consultation Details')
            ->body(view('admin.requests.details')
	            ->with('order', Consultation::with(['status','payment','paymentStatus','lawyer','user','specialty','messages'])->find($id))
        );


    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        $form = new Form(new Consultation());
    //  $form->text('consultation_number', __('Consultation number'));
        $form->text('subject', __('Subject'));
        // $form->text('customer_name', __('Customer name'));
        // $form->text('customer_phone', __('Customer phone'));
        // $form->text('lawyer_name', __('Lawyer name'));
        // $form->text('lawyer_phone', __('Lawyer phone'));
    //  $form->text('req_date', __('Req date'));
    //  $form->text('req_time', __('Req time'));
    //  $form->number('status_id', __('Status id'));
        $form->select('status_id', __('Status'))->options(Status::all()->pluck('name','id')->toArray());

        $form->currency('amount', __('Amount'))->default(0.00);
        //    $form->number('payment_status_id', __('Payment status id'));
        //    $form->select('user_id', __('User id'))->options(User::all()->pluck('email','id')->toArray())->load('address_id','/admin/requestaddress');
        $form->select('user_id', __('User'))->options(User::all()->pluck('name', 'id'));
        $form->select('lawyer_id', __('Lawyer'))->options(lawyer::all()->pluck('name', 'id'));
        $form->select('specialty_id', __('specialty'))->options(Specialty::all()->pluck('name', 'id'));
        $form->select('payment_id', __('Payment Method'))->options(Payment::all()->pluck('name', 'id'));
        $form->file('file', __('file'));

        // $form->saving(function (Form $form) {

        //     dd($form->subject);
        // });
        return $form;
    }

    public function create(Content $content)
    {
        return $content
            ->header('Request')
            ->body($this->form());
    }    
    public function store(Request $request)
    {

        $file = null;
        if($request->file('file') != null){

            $file = $request->file('file');
            $fileName = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = 'uploads/requests/'.$fileName.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$fileName);

        }

        if($request->user_id)
        {
            $user = User::find($request->user_id);
            $customer_name  = $user->name;
          //  $user_phone  = $user->phone_prefix.' '. $user->phone;
            $customer_phone  = $user->phone;
        }
        
        if($request->lawyer_id)
        {
            $lawyer = Lawyer::find($request->lawyer_id);
            $lawyer_name  = $lawyer->name;
        //    $lawyer_phone  = $lawyer->phone_prefix.' '. $lawyer->phone;
            $lawyer_phone  = $lawyer->phone;
        }

        $Consultation = new Consultation();

        $Consultation->subject = $request->subject;
        $Consultation->amount = $request->amount;
        $Consultation->customer_name = $customer_name;
        $Consultation->customer_phone = $customer_phone;
        $Consultation->lawyer_name = $lawyer_name;
        $Consultation->lawyer_phone = $lawyer_phone;
        $Consultation->status_id = $request->status_id;
        $Consultation->payment_id = $request->payment_id;
        
        $Consultation->user_id = $request->user_id;
        $Consultation->lawyer_id = $request->lawyer_id;
        $Consultation->specialty_id = $request->specialty_id;

        $Consultation->subject = $request->subject;
        $Consultation->file = $fileName;
        




        
        $Consultation->consultation_number = $this->generateRequestUniqueNumber();



        // if($request->user_id){
        //     $user = User::find($request->user_id);
        //     if($user->is_notified){

        //         $setting = Setting::first();
        //         $title = $setting->title_notifications_new_Consultation;
        //         $title_rep = str_replace('[name]', $Consultation->user_name, $title);
        //         $consultation_number = $Consultation->consultation_number;
        //         $desc = $setting->desc_notifications_new_Consultation;
        //         $desc_rep = str_replace('[number_Consultation]', $consultation_number, $desc);
        //         //dd($user->id);
        //         sendNotification($user->id , $Consultation->id, $title_rep, $desc_rep);
        //     }
        // }
        // if($request->lawyer_id){
        //     $lawyer = Lawyer::find($request->lawyer_id);
        //     if($lawyer->is_notified){

        //         $setting = Setting::first();
        //         $title = $setting->title_notifications_new_Consultation;
        //         $title_rep = str_replace('[name]', $Consultation->lawyer_name, $title);
        //         $consultation_number = $Consultation->consultation_number;
        //         $desc = $setting->desc_notifications_new_Consultation;
        //         $desc_rep = str_replace('[number_Consultation]', $consultation_number, $desc);
        //         //dd($lawyer->id);
        //         sendNotification($lawyer->id , $Consultation->id, $title_rep, $desc_rep);
        //     }
        // }



        $Consultation->save();

        // Mail::to($Consultation->user_email)->send(new RequestServiceMail($Consultation));

        return redirect()->route('admin.consultations.index');


    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('Request')
            ->body($this->form($id)->edit($id));
    }  
    
    public function update(Request $request , $id)
    {
         //dd($id);
        $consultation = Consultation::find($id);

        $file = $consultation->file;

        if($request->file('file') != null){


            $file = $request->file('file');
            $fileName = time().rand(0, 1000).pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = 'uploads/requests/'.$fileName.'.'.$file->getClientOriginalExtension();
            $file->move('/var/www/html/public/uploads/requests',$fileName);
        }

     
        if($request->user_id)
        {
            $user = User::find($request->user_id);
            $customer_name  = $user->name;
          //  $user_phone  = $user->phone_prefix.' '. $user->phone;
            $customer_phone  = $user->phone;
        }
        
        if($request->lawyer_id)
        {
            $lawyer = Lawyer::find($request->lawyer_id);
            $lawyer_name  = $lawyer->name;
        //    $lawyer_phone  = $lawyer->phone_prefix.' '. $lawyer->phone;
            $lawyer_phone  = $lawyer->phone;
        }

        $Consultation = Consultation::find($id);

        $Consultation->subject = $request->subject;
        $Consultation->amount = $request->amount;
        $Consultation->customer_name = $customer_name;
        $Consultation->customer_phone = $customer_phone;
        $Consultation->lawyer_name = $lawyer_name;
        $Consultation->lawyer_phone = $lawyer_phone;
        $Consultation->status_id = $request->status_id;
        $Consultation->payment_id = $request->payment_id;
        
        $Consultation->user_id = $request->user_id;
        $Consultation->lawyer_id = $request->lawyer_id;
        $Consultation->specialty_id = $request->specialty_id;

        $Consultation->subject = $request->subject;
        $Consultation->file = $fileName;
        $Consultation->consultation_number = $this->generateRequestUniqueNumber();

        $Consultation->save();


        // $setting = Setting::first();
        // $title = $setting->title_notifications_change_status;
        // $title_rep = str_replace('[name]', $order->user_name, $title);
        // $number_request = $order->number_request;
        // $desc = $setting->desc_notifications_change_status;
        // $desc_rep = str_replace('[number_order]', $number_request, $desc);

        // if(Requests::COMPLETED_JOB == $request->status_id){
        //     $status_text = "Job completed";
        //     $desc_rep = str_replace('[status]', $status_text, $desc_rep);

        // }if(Requests::CANCALLED_JOB == $request->status_id){
        //     $status_text = "Canceled";
        //     $desc_rep = str_replace('[status]', $status_text, $desc_rep);

        // }

        // if(isset($status_text))
        // {
        //     $desc_rep = str_replace('[status]', $status_text, $desc_rep);
        // }




        // if($request->status_id != $anc_status){
        //     Mail::to($order->user_email)->send(new StatusMail($order));
        //     if($request->user_id){
        //         if($user->is_notified && in_array($request->status_id , [Requests::COMPLETED_JOB , Requests::CANCALLED_JOB])){
        //            // dd($title_rep);
        //             sendNotification($request->user_id , $order->id, $title_rep, $desc_rep);
        //         }
        //     }
        // }
        return redirect()->route('admin.consultations.index');

    }    

    public function generateRequestUniqueNumber()
    {
        $requestUniqueNumber = rand(10000000, 99999999);
        $request = Consultation::where('consultation_number', $requestUniqueNumber)->first();

        if (!$request) {
            return $requestUniqueNumber;
        }

        $this->generateRequestUniqueNumber();
    }

    // public function show($id, Content $content)
    // {
    //     return $content
    //         ->header('Request')
    //         ->body($this->detail($id));
    // }
    public function print($id)
    {
        $orders = Consultation::find($id);
        
        return view('admin.requests.print')->with([
            'order' => $orders,
        ]);
    }

}
