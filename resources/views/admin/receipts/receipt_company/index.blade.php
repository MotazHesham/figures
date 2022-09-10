@extends('layouts.app')

@section('styles')
    <style>
        td{
            font-size: 15px
        }

    </style>
@endsection

@section('content')


    <div class="row">
        <div class="col-lg-6 pull-left">
            <a href="{{ route('receipt.company.trashed')}}" class="btn btn-rounded btn-lg btn-danger pull-left">{{__('Trashed Company Receipts')}}</a>
        </div>
        <div class="col-lg-6 pull-right">
            <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-rounded btn-lg btn-info pull-right">{{__('Add Company Receipt')}}</a>
        </div>
    </div>

<br>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" >
                <h5 class="modal-title" id="exampleModalLabel" >{{__('Add Company Receipt')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('receipt.company.add')}}" method="GET">
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
                <form action="{{ route('receipt.company.update_playlist_status2')}}" method="POST">
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


<div class="panel">
    <div class="panel-body">

        <h2 class="text-center">
            {{__('Company Receipts')}}
            <button class="btn btn-mint  btn-rounded" data-toggle="modal" data-target="#editcolorsmodal">Edit Colors</button>
        </h2>

        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_receipt_company" action="" method="GET" >
                    <h5 class="text-center">{{__('Search In Company Receipts')}}</h5>
                    <div class="row">
                        {{-- sort by (staff - type - payment_status - delivery_status) --}}
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <span>&nbsp;</span>
                                    <div class="@isset($staff_id) isset @endisset" style="min-width: 160px;margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="staff_id" id="staff_id" onchange="sort_receipt_company()">
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
                                <div class="col-md-4">
                                    <span>&nbsp;</span>
                                    <div class="" style="min-width: 100px;margin-bottom: 10px">
                                        <select class="form-control @isset($sent_to_wasla) isset @endisset" name="sent_to_wasla" id="sent_to_wasla" onchange="sort_receipt_company()">
                                            <option value="">حالة الشحن</option>
                                            <option value="0" @isset($sent_to_wasla) @if($sent_to_wasla == '0') selected @endif @endisset >لم يتم الأرسال</option>
                                            <option value="1" @isset($sent_to_wasla) @if($sent_to_wasla == '1') selected @endif @endisset>تم الأرسال</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class="@isset($delivery_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                        <select class="form-control  demo-select2" name="delivery_status" id="delivery_status" onchange="sort_receipt_company()">
                                            <option value="">{{__('Delivery Status')}}</option>
                                            @foreach(\App\Models\Order::DELIVERY_STATUS_SELECT as $key => $label)
                                                <option value="{{$key}}"   @isset($delivery_status) @if($delivery_status == $key) selected @endif @endisset>{{__($label)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class="@isset($payment_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                        <select class="form-control  demo-select2" name="payment_status" id="payment_status" onchange="sort_receipt_company()">
                                            <option value="">{{__('Payment Status')}}</option>
                                            @foreach(\App\Models\Order::PAYMENT_STATUS_SELECT as $key => $label)
                                                <option value="{{$key}}"   @isset($payment_status) @if($payment_status == $key) selected @endif @endisset>{{__($label)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <span>&nbsp;</span>
                                    <div class=" @isset($type) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="type" id="type" onchange="sort_receipt_company()">
                                            <option value="">{{__('Receipt Type')}}</option>
                                            <option value="individual" @isset($type) @if($type == 'individual') selected @endif @endisset >Individual</option>
                                            <option value="corporate" @isset($type) @if($type == 'corporate') selected @endif @endisset>Corporate</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <span>&nbsp;</span>
                                    <div class=" @isset($quickly) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="quickly" id="quickly" onchange="sort_receipt_company()">
                                            <option value="">{{__('Select')}}</option>
                                            <option value="0" @isset($quickly) @if($quickly == '0') selected @endif @endisset >{{__('Not Quickly')}}</option>
                                            <option value="1" @isset($quickly) @if($quickly == '1') selected @endif @endisset>{{__('Quickly')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div style=";margin-bottom: 10px">
                                        <span>&nbsp;</span>
                                        <div class="@isset($playlist_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                            <select class="form-control  demo-select2" name="playlist_status" id="playlist_status" onchange="sort_receipt_company()">
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
                            </div>

                        </div>

                        {{-- sort by (phone - order_num - client_name - calling) --}}
                        <div class="col-md-4">
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
                                        <select class="form-control  demo-select2" name="country_id" id="country_id" onchange="sort_receipt_company()">
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
                                {{-- no answer --}}
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class=" @isset($no_answer) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="no_answer" id="no_answer" onchange="sort_receipt_company()">
                                            <option value="">{{__('No Answer Status')}}</option>
                                            <option value="0" @isset($no_answer) @if($no_answer == '0') selected @endif @endisset>تم الرد</option>
                                            <option value="1" @isset($no_answer) @if($no_answer == '1') selected @endif @endisset>لم يتم الرد</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- order_num - calling --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class=" @isset($calling) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="calling" id="calling" onchange="sort_receipt_company()">
                                            <option value="">{{__('Calling Status')}}</option>
                                            <option value="0" @isset($calling) @if($calling == '0') selected @endif @endisset>لم يتم الأتصال</option>
                                            <option value="1" @isset($calling) @if($calling == '1') selected @endif @endisset>تم الأتصال</option>
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
                            </div>

                            <div style="margin-top: 10px">
                                <span>&nbsp;</span>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="submit" value="{{__('Search')}}" name="search" class="btn btn-success btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="{{__('Download')}}" name="download" class="btn btn-info btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit" value="{{__('Print')}}" name="print" class="btn btn-danger btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-3">
                                        <a class="btn btn-warning btn-rounded btn-block" href="{{route('receipt.company')}}">{{__('Reset')}}</a>
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
                        {{__('Statistics Company Receipts')}}
                        <div>
                            @if($phone == null &&
                                $payment_status == null &&
                                $client_name == null &&
                                $type == null &&
                                $order_num == null &&
                                $quickly == null &&
                                $delivery_status == null &&
                                $calling == null &&
                                $staff_id == null &&
                                $country_id == null &&
                                $no_answer == null &&
                                $playlist_status == null &&
                                $sent_to_wasla == null &&
                                $from == null && $to == null )

                                <span class="text-center badge badge-danger">All</span>

                            @else
                            <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                <span class="text-center badge badge-grey">{{$phone}}</span>
                                <span class="text-center badge badge-grey">{{__(ucfirst($payment_status))}}</span>
                                <span class="text-center badge badge-grey">{{$client_name}}</span>
                                <span class="text-center badge badge-grey">{{$playlist_status}}</span>
                                <span class="text-center badge badge-grey">{{$type}}</span>
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
                                <span class="text-center badge badge-grey">@isset($no_answer)
                                        {{$no_answer ?
                                            'لم يتم الرد'
                                            :
                                            'تم الرد'}}
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
                            </div>
                            @endif
                        </div>
                    </h5>
                    <div class="row ">
                        <div class="col-md-4">
                            <span class="badge badge-mint">عدد الفواتير {{$receipts->total()}}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="badge badge-{{$generalsetting->order_cost}}">+ {{($statistics['total_order_cost'])}} {{__('Order Cost')}} </span>
                            <span class="badge badge-{{$generalsetting->shipping_country_cost}}">+ {{($statistics['total_shipping_cost'])}} {{__('Shipping Cost')}}</span>
                            <span class="badge badge-{{$generalsetting->deposit}}">- {{($statistics['total_deposit'])}} {{__('Deposit')}}</span>
                            <span class="badge badge-{{$generalsetting->total_cost}}">  {{__('Total Cost')}}<br>={{($statistics['total_need_to_pay'])}} </span>
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
        <table class="table res-table table-hover mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th>{{__('Order Num')}} </th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Client')}}</th>
                    <th style="width: 200px">{{__('Address')}}</th>
                    <th>{{__('Total Cost')}}</th>
                    <th style="width: 400px">{{__('Description')}}</th>
                    <th>{{__('Deliverd Status')}}</th>
                    <th></th>
                    <th>{{__('')}}</th>
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
                        @elseif($receipt->no_answer)
                            style="background-image:linear-gradient(to {{$generalsetting->no_answer_3}},{{$generalsetting->no_answer_1}},{{$generalsetting->no_answer_2}})"
                        @elseif($receipt->quickly)
                            style="background-image:linear-gradient(to {{$generalsetting->quickly_3}},{{$generalsetting->quickly_1}},{{$generalsetting->quickly_2}})"
                        @endif>
                        <td>
                            <br>{{ ($key+1) + ($receipts->currentPage() - 1)*$receipts->perPage() }}
                        </td>
                        <td>
                            <br>
                            <button class="btn btn-dark" onclick="show_logs('App\\Models\\ReceiptCompany','{{ $receipt->id }}','admin.receipts.receipt_company')">{{ $receipt->order_num }}</button>

                            @if($receipt->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif
                        </td>
                        <td>
                            <br>
                            <span class="badge badge-{{$generalsetting->date_created}}" style="margin: 2px;">
                                <span>{{__('Date Created')}}</span>
                                <br>
                                {{format_Date_TIme(strtotime($receipt->created_at))}}
                            </span>
                            <span class="badge badge-{{$generalsetting->date_of_receiving_order}}" style="margin: 2px;">
                                <span>{{__('Date of Receiving Order')}}</span>
                                <br>
                                {{format_Date($receipt->date_of_receiving_order)}}
                            </span>
                            <span class="badge badge-{{$generalsetting->delivery_date}}" style="margin: 2px;">
                                <span>{{__('Delivery Date')}}</span>
                                <br>
                                {{format_Date($receipt->deliver_date)}}
                            </span>
                        </td>
                        <td>
                            <br>
                            {{ $receipt->client_name }}
                            <br>
                            {{ $receipt->phone }}
                            <br>
                            {{$receipt->phone2}}
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
                            <?php echo nl2br($receipt->address);?>
                        </td>
                        <td>
                            <br>
                            <span class="badge badge-{{$generalsetting->order_cost}}">+ {{ ($receipt->order_cost) }} <span>{{__('Order Cost')}}</span></span>
                            <span class="badge badge-{{$generalsetting->shipping_country_cost}}">+ {{ ($receipt->shipping_country_cost) }} <span>{{__('Shipping Cost')}}</span></span>
                            <span class="badge badge-{{$generalsetting->deposit}}">- {{ ($receipt->deposit) }} <span>{{__('Deposit')}}</span></span>
                            <span class="badge badge-{{$generalsetting->total_cost}}">= {{ ($receipt->need_to_pay) }}</span>
                        </td>

                        <td>
                            <br>
                            <?php echo $receipt->description;?>
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
                            <hr>
                            @if($general_settings->delivery_system == 'wasla' && $receipt->sent_to_wasla)
                                <span class="badge badge-success">  تم الارسال لوصلة</span>
                            @endif
                        </td>

                        <td>
                            <br>
                            <span class="badge badge-default">{{__('Calling')}}</span>
                            <label class="switch">
                                <input onchange="update_calling(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->calling == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            <br>
                            <hr>
                            <span class="badge badge-default">{{__('Quickly')}}</span>
                            <label class="switch">
                                <input onchange="update_quickly(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->quickly == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            <hr>
                            <span class="badge badge-default">{{__('No Answer')}}</span>
                            <label class="switch">
                                <input onchange="update_no_answer(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->no_answer == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                        </td>

                        <td class=" text-center">
                            <br>
                            <span class="badge badge-{{$generalsetting->added_by}}" style="margin: 2px;">
                                <span>{{__('Added By')}}</span>
                                <br>
                                {{ $receipt->Staff ? $receipt->Staff->email : ''}}
                            </span>

                            @if($receipt->DeliveryMan)
                            <span class="badge badge-{{$generalsetting->delivery_man}}" style="margin: 2px;">
                                <span>{{__('Delivery Man')}}</span>
                                <br>
                                {{ $receipt->DeliveryMan->email}}
                            </span>
                            @endif

                            <hr>
                            <span class="badge badge-default">{{__('Note')}}</span>
                            <br>
                            <span style="font-weight: bolder">
                                <?php echo $receipt->note;?>
                            </span>
                        </td>

                        <td>
                            <br>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('receipt.company.edit', $receipt->id)}}"><i class="fa fa-edit" style="color: #2E86C1"></i> {{__('Edit')}}</a></li>
                                    <li><a target="_blanc" href="{{route('receipt.company.print', $receipt->id)}}"><i class="fa fa-print" style="color: #2ECC71"></i> {{__('Print')}}</a></li>
                                    <li><a href="{{route('receipt.company.duplicate', $receipt->id)}}"><i class="fa fa-clone" style="color: hsl(295, 41%, 45%)"></i> {{__('Duplicate')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('receipt.company.destroy', $receipt->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i> {{__('Delete')}}</a></li>
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


<!-- editcolorsmodal -->
<div class="modal fade" id="editcolorsmodal" tabindex="-1" role="dialog" aria-labelledby="editcolorsmodalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editcolorsmodalLabel">Edit Colors</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $badges = \App\Models\GeneralSetting::BADGE_SELECT;
                @endphp
                <form class="form-horizontal" action="{{ route('generalsettings_2') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row">
                        <div style="padding: 15px">
                            <label class="col-sm-3 control-label" for="date_created">{{__('No Answer')}}</label>
                            <div class="col-sm-4">
                                <input type="color" name="no_answer_1" lass="form-control" value="{{$generalsetting->no_answer_1}}">
                                <input type="color" name="no_answer_2" lass="form-control" value="{{$generalsetting->no_answer_2}}">
                                <select name="no_answer_3" class="form-control">
                                    <option value="bottom" @if($generalsetting->no_answer_3 == 'bottom') selected @endif>to bottom</option>
                                    <option value="right" @if($generalsetting->no_answer_3 == 'right') selected @endif>to right</option>
                                    <option value="left" @if($generalsetting->no_answer_3 == 'left') selected @endif>to left</option>
                                    <option value="top" @if($generalsetting->no_answer_3 == 'top') selected @endif>to bottom</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div style="padding: 15px">
                            <label class="col-sm-3 control-label" for="date_created">{{__('Done')}}</label>
                            <div class="col-sm-4">
                                <input type="color" name="done_1" lass="form-control" value="{{$generalsetting->done_1}}">
                                <input type="color" name="done_2" lass="form-control" value="{{$generalsetting->done_2}}">
                                <select name="done_3" class="form-control">
                                    <option value="bottom" @if($generalsetting->done_3 == 'bottom') selected @endif>to bottom</option>
                                    <option value="right" @if($generalsetting->done_3 == 'right') selected @endif>to right</option>
                                    <option value="left" @if($generalsetting->done_3 == 'left') selected @endif>to left</option>
                                    <option value="top" @if($generalsetting->done_3 == 'top') selected @endif>to bottom</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div style="padding: 15px">
                            <label class="col-sm-3 control-label" for="date_created">{{__('Quickly')}}</label>
                            <div class="col-sm-4">
                                <input type="color" name="quickly_1" lass="form-control" value="{{$generalsetting->quickly_1}}">
                                <input type="color" name="quickly_2" lass="form-control" value="{{$generalsetting->quickly_2}}">
                                <select name="quickly_3" class="form-control">
                                    <option value="bottom" @if($generalsetting->quickly_3 == 'bottom') selected @endif>to bottom</option>
                                    <option value="right" @if($generalsetting->quickly_3 == 'right') selected @endif>to right</option>
                                    <option value="left" @if($generalsetting->quickly_3 == 'left') selected @endif>to left</option>
                                    <option value="top" @if($generalsetting->quickly_3 == 'top') selected @endif>to bottom</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="date_created">{{__('Date Created')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="date_created_{{$badge}}" name="date_created"  @if($badge == $generalsetting->date_created) checked @endif value="{{$badge}}">
                                <label for="date_created_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Date of Receiving Order')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="date_of_receiving_order_{{$badge}}" name="date_of_receiving_order"  @if($badge == $generalsetting->date_of_receiving_order) checked @endif value="{{$badge}}">
                                <label for="date_of_receiving_order_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Delivery Date')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="delivery_date_{{$badge}}" name="delivery_date" @if($badge == $generalsetting->delivery_date) checked @endif value="{{$badge}}">
                                <label for="delivery_date_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Order Cost')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="order_cost_{{$badge}}" name="order_cost" @if($badge == $generalsetting->order_cost) checked @endif value="{{$badge}}">
                                <label for="order_cost_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Deposit')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="deposit_{{$badge}}" name="deposit" @if($badge == $generalsetting->deposit) checked @endif value="{{$badge}}">
                                <label for="deposit_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Shipping Cost')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="shipping_country_cost_{{$badge}}" name="shipping_country_cost"  @if($badge == $generalsetting->shipping_country_cost) checked @endif value="{{$badge}}">
                                <label for="shipping_country_cost_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Total Cost')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="total_cost_{{$badge}}" name="total_cost" @if($badge == $generalsetting->total_cost) checked @endif value="{{$badge}}">
                                <label for="total_cost_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Added By')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="added_by_{{$badge}}" name="added_by" @if($badge == $generalsetting->added_by) checked @endif value="{{$badge}}">
                                <label for="added_by_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label>  &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Delivery Man')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="delivery_man_{{$badge}}" name="delivery_man" @if($badge == $generalsetting->delivery_man) checked @endif value="{{$badge}}">
                                <label for="delivery_man_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-sm-3 control-label" for="name">{{__('Receipt Type')}}</label>
                        <div class="col-sm-9">
                            @foreach($badges as $badge)
                                <input type="radio" id="receipt_type_{{$badge}}" name="receipt_type" @if($badge == $generalsetting->receipt_type) checked @endif value="{{$badge}}">
                                <label for="receipt_type_{{$badge}}"><span class="badge badge-{{$badge}}">{{$badge}}</span></label> &nbsp; &nbsp;
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection


@section('script')
    <script type="text/javascript">

        function printExternal(url) {
            var printWindow = window.open( url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');

            printWindow.addEventListener('load', function() {
                if (Boolean(printWindow.chrome)) {
                    printWindow.print();
                    setTimeout(function(){
                        printWindow.close();
                    }, 500);
                } else {
                    printWindow.print();
                    printWindow.close();
                }
            }, true);
        }

        function sort_receipt_company(el){
            $('#sort_receipt_company').submit();
        }

        function update_done(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('receipt.company.done') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'success');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function change_status(order_num,type){
            $.post('{{ route('receipt.company.playlist_users') }}', {_token:'{{ csrf_token() }}',order_num:order_num}, function(data){
                $('#designer').modal('show');
                $('#designer .modal-body #select_users').html(data);
                $('#designer #order_num').val(order_num);
                $('#designer #status').val(type);
                $('#designer .modal-title').html(order_num);
            });
        }

        function update_calling(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.company.calling') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Calling Client updated successfully');
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }

        function update_quickly(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.company.quickly') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Quickly Receipt updated successfully');
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }

        function update_no_answer(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.company.no_answer') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Receipt updated successfully');
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }

    </script>
@endsection
