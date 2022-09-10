@extends('layouts.app')

@section('content')

<div class="container">
<div class="col-sm-8">
    <div class="panel mb-5 text-center">
        <div class="panel-heading" style="padding: 10px;margin-bottom:40px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.price_view')}}">
                    <i class="fa fa-chevron-circle-left"></i>
                    back
                </a>
                {{__('Add Price View Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.price_view.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="row ">
                <div class="panel-body">

                    @include('admin.partials.error_message')
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span class="badge badge-default">{{__('Client Name')}}</span>
                            <input type="text" class="form-control" name="client_name" id="client_name" value="{{old('client_name')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span class="badge badge-default">{{__('Phone Number')}}</span>
                            <input type="text" class="form-control" name="phone" id="phone" value="{{old('phone')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span class="badge badge-default">مدة الأرتباط</span>
                            <input type="text" class="form-control" name="relate_duration" id="relate_duration" value="{{old('relate_duration')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span class="badge badge-default">مدة التوريد</span>
                            <input type="text" class="form-control" name="supply_duration" id="supply_duration" value="{{old('supply_duration')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span class="badge badge-default">الدفع</span>
                            <input type="number" class="form-control" name="payment" id="payment" value="{{old('payment')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span class="badge badge-default">مكان التسليم</span>
                            <input type="text" class="form-control" name="place" id="place" value="{{old('place')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-center" style="margin-bottom: 10px;">
                            <span class="badge badge-default">قيمة (14%)</span>
                            <select class="form-control" name="added_value" id="added_value" >
                                <option value="1" >شامل</option>
                                <option value="0" >غير شامل</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>
</div>

@endsection

