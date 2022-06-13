@extends('admin::index')
@section('content')

<section class="content-header">
    <h1>
        <span>Requests Reports</span>
    </h1>
</section>

<section class="content">
    @include('admin::partials.alerts')
    @include('admin::partials.exception')
    @include('admin::partials.toastr')
    @php
        $totalAmount = 0 ;

        foreach ($orders as $order)
        {
            $totalAmount +=$order->amount;
        }
    @endphp
    <div class="box">
        <div class="box-header with-border">
            <div class="pull-right">
                <div class="btn-group" style="margin-right: 10px" data-toggle="buttons">
                    <label class="btn btn-sm btn-dropbox filter-btn " title="Filter">
                        <input type="checkbox"><i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;Filter</span>
                    </label>
                </div>
            </div>
            <div class="box-header with-border {{ $request->has('search') ? '' : 'hide' }}" id="filter-box">
                <form action="{{ route('admin.reports.requests_report') }}" class="form-horizontal" pjax-container="" method="get">
                    <input type="hidden" name="search" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-body">
                                <div class="fields-group">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> From Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <input class="form-control datepicker" id="from_date" placeholder="From Date" name="from_date" value="{{ $request->from_date }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> To Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <input class="form-control datepicker" id="to_date" placeholder="To Date" name="to_date" value="{{  $request->to_date }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> Users</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <select class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                                                    <option value="">Please select</option>
                                                    @foreach ($users as $user)
                                                        <option {{ $request->has('user_id') && $request->user_id == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> lawyers</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <select class="form-control select2" id="lawyer_id" name="lawyer_id" style="width: 100%;">
                                                    <option value="">Please select</option>
                                                    @foreach ($lawyers as $lawyer)
                                                        <option {{ $request->has('lawyer_id') && $request->lawyer_id == $lawyer->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> specialties</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-pencil"></i>
                                                </div>
                                                <select class="form-control select2" id="specialty_id" name="specialty_id" style="width: 100%;">
                                                    <option value="">Please select</option>
                                                    @foreach ($specialties as $specialty)
                                                        <option {{ $request->has('specialty_id') && $request->specialty_id == $specialty->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <div class="btn-group pull-left">
                                        <button class="btn btn-info submit btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>Request Number</th>
                            <th>Service Name</th>
                            <th>Customer Name</th>
                            <th>Car Make</th>
                            <th>Car Model</th>
                            <th>Car Year</th>
                            <th>Request Date</th>
                            <th>Request Status</th>
                            <th>Payment Status</th>
                            <th>Discount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->number_request }}</td>
                                <td>{{ $order->service->name_en }}</td>
                                <td>{{ $order->user_name }}</td>
                                <td>{{ $order->make->name_en }}</td>
                                <td>{{ $order->model->name_en }}</td>
                                <td>{{ $order->car_years }}</td>
                                <td>{{ $order->req_date }}</td>
                                <td>{{ $order->status_id?$order->status->name_en:'-' }}</td>
                                <td>{{ $order->payment_status_id?$order->paymentStatus->name_en:'-' }}</td>
                                <td>{{ $order->discount? 'Yes': 'No' }}</td>
                                <td>{{ number_format($order->amount,3) }}</td>
                            </tr>
                       @endforeach
                    </tbody>
                </table>

                <table style="" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th style="width: 87%;text-align: right;">Total amount</th>
                            <th>{{ number_format($totalAmount,3) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
            </div>
        </div>


    </div>
</section>

<script src="{{ asset('vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.filter-btn').click(function(){
            if($('#filter-box').hasClass('hide')){
                $('#filter-box').removeClass('hide')
            }else{
                $('#filter-box').addClass('hide');
            }
        });
    });
</script>

<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({format: 'yyyy-mm-d'});
        $('.datepicker_year').datepicker({format: 'yyyy',
                                            viewMode: "years",
                                            minViewMode: "years"});
    });

    $(".select2").select2({"allowClear":true,"placeholder":"Please Select"});
</script>
@endsection
