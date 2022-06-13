<?php

namespace App\Admin\Controllers\Reports;

use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Requests;
use App\Consultation;
use App\User;
use App\Lawyer;
use App\Specialty;

class SalesRequesteController extends Controller
{
    public function index(Request $request)
    {

        $orders = $this->filter($request);
        $allorders = $this->filterall($request);
        $user = USer::where('is_active',1)->get();
        $lawyers = Lawyer::where('is_active',1)->get();
        $specialties = Specialty::where('is_active',1)->get();

        return view('admin.reports.sales_requests.index')->with([
            'orders' => $orders,
            'users' => $user,
            'lawyers' => $lawyers,
            'specialties' => $specialties,
            'request' => $request,
            'header' => 'sales Reports',
        ]);
    }

    protected function filterall(Request $request)
    {
        $orders = Consultation::with(['paymentStatus','lawyer','user','specialty']);

        if ($request->has('search') && $request->has('from_date') && !is_null($request->from_date)) {
            $orders = $orders->whereDate('req_date', '>=', $request->from_date);
        }
        if ($request->has('search') && $request->has('to_date') && !is_null($request->to_date)) {
            $orders = $orders->whereDate('req_date', '<=', $request->to_date);
        }

        if ($request->has('search') && $request->has('from_time') && !is_null($request->from_time)) {
            $orders = $orders->whereTime('req_time', '>=', $request->from_time);
        }
        if ($request->has('search') && $request->has('to_time') && !is_null($request->to_time)) {
            $orders = $orders->whereTime('req_time', '<=', $request->to_time);
        }

        $orderPaidIds = Consultation::where('payment_status_id' , 1)->pluck('id')->toArray();
        $orders->where('payment_status_id' , 1);
        $orders = $orders->orderBy('created_at', 'desc')->get();

        return $orders;
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
        // if ($request->has('search') && $request->has('from_time') && !is_null($request->from_time)) {
        //     $orders = $orders->whereTime('req_time', '>=', $request->from_time);
        // }
        // if ($request->has('search') && $request->has('to_time') && !is_null($request->to_time)) {
        //     $orders = $orders->whereTime('req_time', '<=', $request->to_time);
        // }

        $orders = $orders->where('payment_status_id' , 1)->where('status_id' , 2)->orderBy('created_at', 'desc')->get();

        return $orders;
    }


}
