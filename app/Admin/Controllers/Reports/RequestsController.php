<?php

namespace App\Admin\Controllers\Reports;

use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Requests;
use App\Models\Service;
use App\Models\Status;
use App\Models\PaymentStatus;
use App\Models\CarMake;
use App\Models\CarModel;
use App\User;
use App\Consultation;
use App\Lawyer;
use App\Specialty;
class RequestsController extends Controller
{
    public function index(Request $request)
    {

        $orders = $this->filter($request);
        // dd($orders);
        $user = USer::where('is_active',1)->get();
        $lawyers = Lawyer::where('is_active',1)->get();
        $specialties = Specialty::where('is_active',1)->get();
        $status = Status::all();
        $payment_status = PaymentStatus::all();


        return view('admin.reports.requests_report.index')->with([
            'orders' => $orders,
            'users' => $user,
            'services' => $service,
            'orderStatus' => $status,
            'payments_status' => $payment_status,
            'lawyers' => $lawyers,
            'specialties' => $specialties,
            'request' => $request,
            'header' => 'Request Reports',
        ]);


    }

    protected function filter(Request $request)
    {
        $orders = Consultation::with(['paymentStatus','lawyer','user','specialty']);

        if ($request->has('search') && $request->has('from_date') && !is_null($request->from_date)) {
            $orders = $orders->whereDate('req_date', '>=', $request->from_date);
        }
        if ($request->has('search') && $request->has('to_date') && !is_null($request->to_date)) {
            $orders = $orders->whereDate('req_date', '<=', $request->to_date);
        }
        if ($request->has('search') && $request->has('user_id') && !is_null($request->user_id)) {
            $orders = $orders->where('user_id', $request->user_id);
        }
        if ($request->has('search') && $request->has('lawyer_id') && !is_null($request->lawyer_id)) {
            $orders = $orders->where('lawyer_id', $request->lawyer_id);
        }   
        if ($request->has('search') && $request->has('specialty_id') && !is_null($request->specialty_id)) {
            $orders = $orders->where('specialty_id', $request->specialty_id);
        }  

        $orders = $orders->orderBy('created_at', 'desc')->get();

        return $orders;

    }
}
