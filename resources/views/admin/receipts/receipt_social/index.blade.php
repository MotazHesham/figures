@extends('layouts.app')
@section('styles')
    <style>
        td{
            font-size: 15px
        }

    </style>
@endsection

@section('content')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Add '.ucfirst($receipt_type).' Receipt')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('receipt.social.add',$receipt_type)}}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="phone" required placeholder="Phone Number" onkeyup="searchByPhone(this)">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="designer" tabindex="-1" aria-labelledby="designerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="designerLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('receipt.social.update_playlist_status2')}}" method="POST">
                    @csrf
                    <input type="hidden" name="order_num" id="order_num">
                    <input type="hidden" name="status" id="status" value="design">
                    <div id="select_users">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <a href="{{ route('receipt.social.trashed',$receipt_type)}}" class="btn btn-rounded btn-lg btn-danger pull-left">{{__('Trashed '.ucfirst($receipt_type).' Receipts')}}</a>
        </div>
        <div class="col-lg-4">
            <a href="{{route('receipt.product',$receipt_type)}}" class="btn btn-lg btn-rounded btn-success">{{__('Products Of '. ucfirst($receipt_type) .' Receipt')}}</a>
        </div>
        <div class="col-lg-4">
            <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-rounded btn-lg btn-info pull-right">{{__('Add '.ucfirst($receipt_type).' Receipt')}}</a>
        </div>
    </div>
</div>

<br>

<div class="panel">
    <div class="panel-body">

        <h2 class="text-center">
            {{__(ucfirst($receipt_type).' Receipt')}}
        </h2>

        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_receipt_social" action="" method="GET" >
                    <h5 class="text-center">{{__('Search In '.ucfirst($receipt_type).' Receipts')}}</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">

                                {{-- sort by (staff - type - payment_status - delivery_status) --}}
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span>&nbsp;</span>
                                            <div class="@isset($staff_id) isset @endisset" style="min-width: 160px;margin-bottom: 10px">
                                                <select class="form-control demo-select2" name="staff_id" id="staff_id" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Choose Staff')}}</option>
                                                    @foreach($staffs as $staff)
                                                        <option value="{{$staff->id}}"
                                                                @isset($staff_id) @if($staff_id == $staff->id) selected @endif @endisset>
                                                                {{$staff->email}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class="" style="margin-bottom: 10px">
                                                <select class="form-control @isset($sent_to_wasla) isset @endisset" name="sent_to_wasla" id="sent_to_wasla" onchange="sort_receipt_social()">
                                                    <option value="">حالة الشحن</option>
                                                    <option value="0" @isset($sent_to_wasla) @if($sent_to_wasla == '0') selected @endif @endisset >لم يتم الأرسال</option>
                                                    <option value="1" @isset($sent_to_wasla) @if($sent_to_wasla == '1') selected @endif @endisset>تم الأرسال</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class="@isset($delivery_status) isset @endisset" style="margin-bottom: 10px">
                                                <select class="form-control  demo-select2" name="delivery_status" id="delivery_status" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Delivery Status')}}</option>
                                                    @foreach(\App\Models\Order::DELIVERY_STATUS_SELECT as $key => $label)
                                                        <option value="{{$key}}"   @isset($delivery_status) @if($delivery_status == $key) selected @endif @endisset>{{__($label)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class="@isset($payment_status) isset @endisset" style="margin-bottom: 10px">
                                                <select class="form-control  demo-select2" name="payment_status" id="payment_status" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Payment Status')}}</option>
                                                    @foreach(\App\Models\Order::PAYMENT_STATUS_SELECT as $key => $label)
                                                        <option value="{{$key}}"   @isset($payment_status) @if($payment_status == $key) selected @endif @endisset>{{__($label)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class=" @isset($done) isset @endisset" style=" margin-bottom: 10px">
                                                <select class="form-control demo-select2" name="done" id="done" onchange="sort_receipt_social()">
                                                    <option value="">حالة التسليم</option>
                                                    <option value="1" @isset($done) @if($done == '1') selected @endif @endisset>تم التسليم</option>
                                                    <option value="0" @isset($done) @if($done == '0') selected @endif @endisset>لم يتم التسليم</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class=" @isset($type) isset @endisset" style=" margin-bottom: 10px">
                                                <select class="form-control demo-select2" name="type" id="type" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Receipt Type')}}</option>
                                                    <option value="individual" @isset($type) @if($type == 'individual') selected @endif @endisset >Individual</option>
                                                    <option value="corporate" @isset($type) @if($type == 'corporate') selected @endif @endisset>Corporate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class=" @isset($quickly) isset @endisset" style=" margin-bottom: 10px">
                                                <select class="form-control demo-select2" name="quickly" id="quickly" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Select')}}</option>
                                                    <option value="0" @isset($quickly) @if($quickly == '0') selected @endif @endisset >{{__('Not Quickly')}}</option>
                                                    <option value="1" @isset($quickly) @if($quickly == '1') selected @endif @endisset>{{__('Quickly')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- sort by (phone - order_num - client_name - calling) --}}
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- phone --}}
                                            <div style=" margin-bottom: 10px">
                                                <span>&nbsp;</span>
                                                <input  type="text"
                                                        class="form-control @isset($phone) isset @endisset"
                                                        id="phone"
                                                        name="phone"
                                                        @isset($phone) value="{{ $phone }}" @endisset
                                                        placeholder="{{__('Phone Number')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {{-- client_name --}}
                                            <div style=" margin-bottom: 10px">
                                                <span>&nbsp;</span>
                                                <input  type="text"
                                                        class="form-control @isset($client_name) isset @endisset"
                                                        id="client_name"
                                                        name="client_name"
                                                        @isset($client_name) value="{{ $client_name }}" @endisset
                                                        placeholder="{{__('Client Name')}}">
                                            </div>
                                        </div>
                                    </div>


                                    @php
                                        $countries = \App\Models\Country::where('type','countries')->get();
                                        $districts = \App\Models\Country::where('type','districts')->get();
                                        $metro = \App\Models\Country::where('type','metro')->get();
                                    @endphp
                                    <div class="row">
                                        {{-- country_id --}}
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class="@isset($country_id) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                                <select class="form-control  demo-select2" name="country_id" id="country_id" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Shipping Country')}}</option>
                                                    <optgroup label="{{__('Districts')}}">
                                                        @foreach($districts as $district)
                                                            <option value={{$district->id}} @if($district->id == $country_id) selected @endif>{{$district->name}} - EGP {{($district->cost)}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="{{__('Countries')}}">
                                                        @foreach($countries as $country)
                                                            <option value={{$country->id}} @if($country->id == $country_id) selected @endif>{{$country->name}} - EGP {{($country->cost)}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="{{__('Metro')}}">
                                                        @foreach($metro as $raw)
                                                            <option value={{$raw->id}} @if($raw->id == $country_id) selected @endif>{{$raw->name}} - EGP {{($raw->cost)}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div style=" margin-bottom: 10px">
                                                <input  type="text"
                                                        class="form-control @isset($order_num) isset @endisset"
                                                        id="order_num"
                                                        name="order_num"@isset($order_num) value="{{ $order_num }}"
                                                        @endisset placeholder="{{__('Order Num')}}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- no answer - calling --}}
                                    <div class="row">
                                        {{-- <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div class=" @isset($calling) isset @endisset" style=" margin-bottom: 10px">
                                                <select class="form-control demo-select2" name="calling" id="calling" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Calling Status')}}</option>
                                                    <option value="0" @isset($calling) @if($calling == '0') selected @endif @endisset>لم يتم الأتصال</option>
                                                    <option value="1" @isset($calling) @if($calling == '1') selected @endif @endisset>تم الأتصال</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        {{-- no answer --}}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div style=";margin-bottom: 10px">
                                                <span>&nbsp;</span>
                                                <div class="@isset($playlist_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                                    <select class="form-control  demo-select2" name="playlist_status" id="playlist_status" onchange="sort_receipt_social()">
                                                        <option value="">{{__('حالات التشغيل')}}</option>
                                                        <option value="pending"   @isset($playlist_status) @if($playlist_status == 'pending') selected @endif @endisset>{{__('لم يتم الأرسال')}}</option>
                                                        <option value="design"   @isset($playlist_status) @if($playlist_status == 'design') selected @endif @endisset>{{__('مرحلة الديزاين')}}</option>
                                                        <option value="manufacturing"   @isset($playlist_status) @if($playlist_status == 'manufacturing') selected @endif @endisset>{{__('مرحلة التصنيع')}}</option>
                                                        <option value="prepare"   @isset($playlist_status) @if($playlist_status == 'prepare') selected @endif @endisset>{{__('مرحلة التجهيز')}}</option>
                                                        <option value="finish"   @isset($playlist_status) @if($playlist_status == 'finish') selected @endif @endisset>{{__('جاهز للتوصيل')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span>&nbsp;</span>
                                            <div style=" margin-bottom: 10px">
                                                <input  type="text"
                                                        class="form-control @isset($description) isset @endisset"
                                                        id="description"
                                                        name="description"@isset($description) value="{{ $description }}"
                                                        @endisset placeholder="{{__('Description')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="@isset($social_id) isset @endisset" style="min-width: 160px;margin-bottom: 10px;margin-top:18px">
                                                <select class="form-control demo-select2" name="social_id" id="social_id" onchange="sort_receipt_social()">
                                                    <option value="">{{__('Choose Social')}}</option>
                                                    @foreach($socials as $social)
                                                        <option value="{{$social->id}}"
                                                                @isset($social_id) @if($social_id == $social->id) selected @endif @endisset>
                                                                {{$social->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    {{-- start date --}}
                                    <div class="col-md-12 text-center" style="margin-bottom:10px">
                                        <span class="badge badge-default">من تاريخ الأضافة</span>
                                        <input type="text" @isset($from_date) value="{{format_date($from_date)}}" @endisset  disabled id="from_date_text" class="form-control @isset($from_date) isset @endisset" style="position: relative;">
                                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="from_date" id="from_date" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- end date --}}
                                    <div class="col-md-12 text-center" style="margin-bottom:10px">
                                        <span class="badge badge-default">الي تاريخ الأضافة</span>
                                        <input type="text" @isset($to_date) value="{{format_date($to_date)}}" @endisset  disabled id="to_date_text" class="form-control @isset($to_date) isset @endisset" style="position: relative;">
                                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="to_date" id="to_date" >
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- sort by (receipt num)--}}
                        <div class="col-md-4">

                            <div class="row">
                                <div class="col-md-6">
                                    {{-- from receipt --}}
                                    <div class="@isset($from) isset @endisset text-center" style="min-width: 160px;margin-bottom: 10px">
                                        <span class="badge badge-default">من الفاتورة</span>
                                        <select class="form-control demo-select2" name="from" id="from" >
                                            <option value="">{{__('Choose Receipt')}}</option>
                                            @foreach($receipts2 as $raw)
                                                <option value="{{$raw->id}}"
                                                    @isset($from) @if($from == $raw->id) selected @endif @endisset>
                                                    {{$raw->order_num }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- to receipt --}}
                                    <div class="@isset($to) isset @endisset text-center" style="min-width: 160px;margin-bottom: 10px">
                                        <span class="badge badge-default">الي الفاتورة</span>
                                        <select class="form-control demo-select2" name="to" id="to" >
                                            <option value="">{{__('Choose Receipt')}}</option>
                                            @foreach($receipts2 as $raw)
                                                <option value="{{$raw->id}}"
                                                    @isset($to) @if($to == $raw->id) selected @endif @endisset>
                                                    {{$raw->order_num }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    {{-- exclude receipts --}}
                                    <div class="@isset($exclude) isset @endisset text-center" style="min-width: 160px; margin-bottom: 10px">
                                        <span class="badge badge-default">ماعادا الفاتورة</span>
                                        <select class="form-control demo-select2" name="exclude[]" id="exclude" multiple>
                                            @foreach($receipts2 as $raw)
                                                <option value="{{$raw->id}}"
                                                    @isset($exclude) @if(in_array($raw->id,$exclude)) selected @endif @endisset>
                                                    {{$raw->order_num }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {{-- include receipts --}}
                                    <div class="@isset($include) isset @endisset text-center" style="min-width: 160px; margin-bottom: 10px">
                                        <span class="badge badge-default">اختيار فواتير</span>
                                        <select class="form-control demo-select2" name="include[]" id="include" multiple>
                                            @foreach($receipts2 as $raw)
                                                <option value="{{$raw->id}}"
                                                    @isset($include) @if(in_array($raw->id,$include)) selected @endif @endisset>
                                                    {{$raw->order_num }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 10px">
                                <span>&nbsp;</span>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="submit" value="{{__('Search')}}" name="search" class="btn btn-success btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-4">
                                        <input type="submit" value="{{__('Download')}}" name="download" class="btn btn-info btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-4">
                                        <input type="submit" value="{{__('Print')}}" name="print" class="btn btn-danger btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-6">
                                        <a class="btn btn-warning btn-rounded btn-block" href="{{route('receipt.social',['confirm' => $confirm , 'receipt_type' => $receipt_type])}}">{{__('Reset')}}</a>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="submit" value="تحميل للشحن" name="download_delivery" class="btn btn-dark btn-rounded btn-block" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            {{-- statistics --}}
            <div class="col-md-3">
                <div class="filteration-box">
                    <h5 class="text-center">
                        {{__('Statistics '.ucfirst($receipt_type).' Receipts')}}
                        <div>
                            @if($phone == null &&
                                $payment_status == null &&
                                $client_name == null &&
                                $type == null &&
                                $order_num == null &&
                                $description == null &&
                                $quickly == null &&
                                $delivery_status == null &&
                                $calling == null &&
                                $staff_id == null &&
                                $country_id == null &&
                                $done == null &&
                                $social_id == null &&
                                $sent_to_wasla == null &&
                                $playlist_status == null &&
                                $from == null && $to == null &&
                                $from_date == null && $to_date == null)

                                <span class="text-center badge badge-danger">All</span>

                            @else
                            <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                <span class="text-center badge badge-grey">{{$phone}}</span>
                                <span class="text-center badge badge-grey">{{$description}}</span>
                                <span class="text-center badge badge-grey">{{__(ucfirst($payment_status))}}</span>
                                <span class="text-center badge badge-grey">{{$client_name}}</span>
                                <span class="text-center badge badge-grey">{{$playlist_status}}</span>
                                <span class="text-center badge badge-grey">{{$type}}</span>
                                <span class="text-center badge badge-grey">@php
                                    $social = \App\Models\Social::find($social_id);
                                    if($social){
                                        echo $social->name;
                                    }
                                @endphp</span>
                                <span class="text-center badge badge-grey">{{$order_num}}</span>
                                <span class="text-center badge badge-grey">@php
                                    $country = \App\Models\Country::find($country_id);
                                    if($country){
                                        echo $country->name;
                                    }
                                @endphp</span>
                                <span class="text-center badge badge-grey">@isset($calling)
                                        {{$calling ?
                                            'تم الأتصال'
                                            :
                                            'لم يتم الأتصال'}}
                                    @endisset</span>
                                <span class="text-center badge badge-grey">@isset($quickly)
                                        {{$quickly ?
                                            'عاجل'
                                            :
                                            'غير عاجل'}}
                                    @endisset</span>
                                <span class="text-center badge badge-grey">@isset($sent_to_wasla)
                                        {{$sent_to_wasla ?
                                            'تم الأرسال لوصلة'
                                            :
                                            'لم يتم الأرسال لوصلة'}}
                                    @endisset</span>
                                <span class="text-center badge badge-grey">@isset($done)
                                        {{$done ?
                                            'تم التسليم'
                                            :
                                            'لم يتم التسليم'}}
                                    @endisset</span>
                                <span class="text-center badge badge-grey">{{__(ucfirst(str_replace('_',' ', $delivery_status)))}}</span>
                                <span class="text-center badge badge-grey">@php
                                    if($staff_id){
                                        $staff = \App\Models\User::find($staff_id);
                                        if($staff){
                                            echo $staff->email;
                                        }
                                    }
                                @endphp</span>
                                <span class="text-center badge badge-grey">{{$from ? 'من الفاتورة ' . $from : ''}}</span>
                                <span class="text-center badge badge-grey">{{$to ? 'الي الفاتورة ' . $to : ''}}</span>
                                <span class="text-center badge badge-grey">{{$from_date ? 'من تاريخ الأضافة ' . format_Date($from_date) : ''}}</span>
                                <span class="text-center badge badge-grey">{{$to_date ? 'الي تاريخ الأضافة ' . format_Date($to_date) : ''}}</span>
                            </div>
                            @endif
                        </div>
                    </h5>
                    <div class="row ">
                        <div class="col-md-4">
                            <span class="badge badge-mint">عدد الفواتير {{$receipts->total()}}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="badge badge-{{$generalsetting->deposit}}">- {{ $statistics['total_deposit']}} {{__('Deposit')}}</span>
                            <span class="badge badge-{{$generalsetting->total_cost}}">= {{ $statistics['total_total'] + $statistics['total_extra_commission'] + $statistics['total_shipping_country_cost'] - $statistics['total_deposit']}}   {{__('Total')}} </span>
                            <span class="badge badge-{{$generalsetting->total_cost}}">{{ $statistics['total_commission']}} {{__('Commission')}}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <div class="clearfix">
            <div class="pull-right">
                {{ $receipts->appends(request()->input())->links() }}
            </div>
        </div>
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th >{{__('Order Num')}} </th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Client')}}</th>
                    <th>{{__('Address')}}</th>
                    <th>{{__('Products')}}</th>
                    <th>{{__('Total')}}</th>
                    <th>{{__('Note')}}</th>
                    <th>{{__('Deliverd Status')}}</th>
                    <th></th>
                    <th></th>
                    <th>{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $general_settings = \App\Models\GeneralSetting::first();
                @endphp
                @foreach($receipts as $key => $receipt)
                <tr @if($receipt->done)
                        style="background-image:linear-gradient(to {{$generalsetting->done_3}},{{$generalsetting->done_1}},{{$generalsetting->done_2}})"
                    @elseif($receipt->quickly)
                        style="background-image:linear-gradient(to {{$generalsetting->quickly_3}},{{$generalsetting->quickly_1}},{{$generalsetting->quickly_2}})"
                    @endif>
                    <td>
                        <br>{{ ($key+1) + ($receipts->currentPage() - 1)*$receipts->perPage() }}
                    </td>
                    <td>
                        <br>
                        <button class="btn btn-dark" onclick="show_logs('App\\Models\\Receipt_Social','{{ $receipt->id }}','admin.receipts.receipt_social')">{{ $receipt->order_num }}</button>

                        @if($receipt->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif
                    </td>
                    <td>
                        <br>
                        <span class="badge badge-{{$generalsetting->date_created}}" style="margin: 2px;">
                            <span>{{__('Date Created')}}</span>
                            <br>
                            {{format_Date(strtotime($receipt->created_at))}}
                        </span>
                        <br>
                        <span class="badge badge-{{$generalsetting->date_of_receiving_order}}" style="margin: 2px;">
                            <span>{{__('Date of Receiving Order')}}</span>
                            <br>
                            {{format_Date($receipt->date_of_receiving_order)}}
                        </span>
                    </td>
                    <td>
                        <br>
                        {{ $receipt->client_name }}
                        <br>
                        {{ $receipt->phone }}
                        <br>
                        {{ $receipt->phone2 }}
                        <br>
                        <span class="badge badge-{{$generalsetting->receipt_type}}">
                            {{__('Receipt Type')}}
                            <br>
                            {{ $receipt->type }}
                        </span>
                    </td>
                    <td>
                        <br>
                        <span class="badge badge-default">{{$receipt->shipping_country_name}}</span> ,
                        <br>
                        <?php echo $receipt->address;?>
                    </td>
                    <td>
                        <br>
                        <a class="btn btn-warning btn-rounded" onclick="view_products({{$receipt->id}})">عرض الكل</a>
                        <br>
                        @php
                            foreach($receipt->receipt_social_products as $product){
                                    echo '<span class="badge badge-default">' . $product->title . '</span>';
                                    echo '<span class="badge badge-pink">' . $product->quantity . '</span>';
                                    echo '<span class="badge badge-mint">' . ($product->cost) . '</span><br>';
                            }
                        @endphp
                    </td>
                    <td>
                        <br>
                        @php
                            $discount = round( ( ($receipt->total/100) * $receipt->discount ) , 2);
                        @endphp
                        <span class="badge badge-default">{{ ($receipt->deposit) }}{{__('Deposit')}}</span><br>
                        <span class="badge badge-default">{{ ($receipt->extra_commission) }}  {{__('Extra Commission')}}</span> <br>
                        <span class="badge badge-default">{{ ($receipt->shipping_country_cost) }}  {{__('Shipping Cost')}}</span> <br>
                        <span class="badge badge-default">{{ ($receipt->total) }} {{__('Total')}}</span> <br>
                        <span class="badge badge-success">= {{ ($receipt->total + $receipt->extra_commission + $receipt->shipping_country_cost - $receipt->deposit) }}</span>
                    </td>
                    <td>
                        <br>
                        <?php echo $receipt->note;?>
                    </td>

                    <td>
                        <br>
                        <span class="badge badge-{{delivery_status_function($receipt->delivery_status)}}">{{ __(ucfirst(str_replace('_', ' ', $receipt->delivery_status))) }}</span>
                        <span class="badge badge-grey">{{__(ucfirst($receipt->payment_status))}}</span>
                        <hr>
                        <span class="badge badge-default">{{__('Done')}}</span>
                        <label class="switch">
                            <input onchange="update_done(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->done == 1) echo "checked";?> >
                            <span class="slider round"></span></label>
                    </td>
                    <td>
                        <br>
                        @if($general_settings->delivery_system == 'wasla' && $receipt->sent_to_wasla)
                            <span class="badge badge-success">  تم الارسال لوصلة</span>
                            <hr>
                        @endif
                        <span class="badge badge-default">{{__('Quickly')}}</span>
                        <br>
                        <label class="switch">
                            <input onchange="update_quickly(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->quickly == 1) echo "checked";?> >
                            <span class="slider round"></span></label>
                    </td>
                    <td>
                        <br>
                        <span class="badge badge-{{$generalsetting->added_by}}">
                            {{__('Added By')}}
                            <br>
                            {{ $receipt->Staff ? $receipt->Staff->email : ''}}
                        </span>
                        <br>
                        @foreach($receipt->socials as $social)
                            <span class="badge badge-dark">{{$social->name}}</span>
                        @endforeach
                        <hr>

                        <span class="badge badge-default">تأكيد</span>
                        <br>
                        <label class="switch">
                            <input onchange="update_confirm(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->confirm == 1) echo "checked";?> >
                            <span class="slider round"></span></label>

                    </td>
                    <td>
                        <br>
                        <div class="btn-group dropdown">
                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                {{__('Actions')}} <i class="dropdown-caret"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{route('receipt.social.edit', $receipt->id)}}"><i class="fa fa-edit" style="color: #2E86C1"></i> {{__('Add Product')}}</a></li>
                                <li><a target="_blanc" href="{{route('receipt.social.print_new', $receipt->id)}}"><i class="fa fa-print" style="color: #2ECC71"></i> {{__('Print')}}</a></li>
                                <li><a target="_blanc" href="{{route('receipt.social.print_receive_money', $receipt->id)}}"><i class="fa fa-print" style="color: #3c5e4a"></i> استلام نقدية</a></li>
                                <li><a onclick="confirm_modal('{{route('receipt.social.destroy', $receipt->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i> {{__('Delete')}}</a></li>
                            </ul>
                        </div>
                        <hr>
                        @if($receipt->playlist_status == 'pending')
                            <a class="btn btn-success btn-rounded"
                                onclick="change_status('{{$receipt->order_num}}','design')">
                                أرسال للديزاينر
                            </a>
                        @else
                            @if($receipt->playlist_status == 'design')
                                <span class="badge badge-info" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','design')">
                                    مرحلة الديزاين
                                </span>
                            @elseif($receipt->playlist_status == 'manufacturing')
                                <span class="badge badge-warning" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','manufacturing')">
                                    مرحلة التصنيع
                                </span>
                            @elseif($receipt->playlist_status == 'prepare')
                                <span class="badge badge-danger" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','prepare')">
                                    مرحلة التجهيز
                                </span>
                            @elseif($receipt->playlist_status == 'finish')
                                <span class="badge badge-success" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','finish')">
                                    جاهز للتوصيل
                                </span>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $receipts->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="show-products" tabindex="-1" aria-labelledby="show-productsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content ">
            <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div id="show-products-modal-body">

            </div>
        </div>
    </div>
</div>


@endsection


@section('script')
    <script type="text/javascript">

        function sort_receipt_social(el){
            $('#sort_receipt_social').submit();
        }

        function update_quickly(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.social.quickly') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Confirm Receipt updated successfully');
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }

        function update_confirm(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.social.confirm') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Quickly Receipt updated successfully');
                        location.reload();
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }

        function change_status(order_num,type){
            $.post('{{ route('receipt.social.playlist_users') }}', {_token:'{{ csrf_token() }}',order_num:order_num}, function(data){
                $('#designer').modal('show');
                $('#designer .modal-body #select_users').html(data);
                $('#designer #order_num').val(order_num);
                $('#designer #status').val(type);
                $('#designer .modal-title').html(order_num);
            });
        }

        function view_products(id){
            $.post('{{ route('receipt.social.view_products') }}', {_token:'{{ csrf_token() }}',id:id}, function(data){
                $('#show-products').modal('show');
                $('#show-products #show-products-modal-body').html(data);
            });
        }

        function update_done(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('receipt.social.done') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'success');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

    </script>
@endsection
