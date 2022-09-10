@extends('layouts.app')

@section('content')
<!-- Basic Data Tables -->
<!--===================================================-->

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
                <form action="{{ route('admin.orders.update_playlist_status2')}}" method="POST">
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
        @if($order_from_user_type == 'seller')
            <h2 class="text-center" >{{__('Sellers Orders')}} </h2>
        @elseif($order_from_user_type == 'customer')
            <h2 class="text-center" >{{__('Customers Orders')}} </h2>
        @endif

        <div class="text-center" style="padding: 20px">
            <span class="badge badge-{{delivery_status_function('delivered')}}">{{ count($orders_count->where('delivery_status','delivered')) }} {{__('Delivered')}}</span>
            <span class="badge badge-{{delivery_status_function('cancel')}}">{{ count($orders_count->where('delivery_status','cancel')) }} {{__('Cancel')}}</span>
            <span class="badge badge-{{delivery_status_function('delay')}}">{{ count($orders_count->where('delivery_status','delay')) }} {{__('Delay')}}</span>
            <span class="badge badge-{{delivery_status_function('pending')}}">{{ count($orders_count->where('delivery_status','pending')) }} {{__('Pending')}}</span>
            <span class="badge badge-{{delivery_status_function('on_review')}}">{{ count($orders_count->where('delivery_status','on_review')) }} {{__('On review')}}</span>
            <span class="badge badge-{{delivery_status_function('on_delivery')}}">{{ count($orders_count->where('delivery_status','on_delivery')) }} {{__('On delivery')}}</span>
        </div>

        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_sellers_orders" action="" method="GET" >
                    @if($order_from_user_type == 'seller')
                        <h5 class="text-center">{{__('Search In Sellers Orders')}}</h5>
                    @elseif($order_from_user_type == 'customer')
                        <h5 class="text-center">{{__('Search In Customers Orders')}}</h5>
                    @endif
                    <div class="row">
                        {{-- sort by (seller  - order_num - calling) --}}
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <span>&nbsp;</span>
                                    <div class="@isset($seller_id) isset @endisset" style="min-width: 200px;margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="seller_id" id="seller_id" onchange="sort_sellers_orders()">
                                            @if($order_from_user_type == 'seller')
                                                <option value="">{{__('Choose Seller')}}</option>
                                            @elseif($order_from_user_type == 'customer')
                                                <option value="">{{__('Choose Customer')}}</option>
                                            @endif
                                            @foreach($sellers as $seller)
                                                <option value="{{$seller->id}}"
                                                        @isset($seller_id) @if($seller_id == $seller->id) selected @endif @endisset>
                                                        {{$seller->email}}
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

                            {{-- order_num - calling --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class=" @isset($calling) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="calling" id="calling" onchange="sort_sellers_orders()">
                                            <option value="">حالة الأتصال</option>
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

                            {{-- delivery_status % payment_status  --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class="@isset($delivery_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                        <select class="form-control  demo-select2" name="delivery_status" id="delivery_status" onchange="sort_sellers_orders()">
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
                                        <select class="form-control  demo-select2" name="payment_status" id="payment_status" onchange="sort_sellers_orders()">
                                            <option value="">{{__('Payment Status')}}</option>
                                            @foreach(\App\Models\Order::PAYMENT_STATUS_SELECT as $key => $label)
                                                <option value="{{$key}}"   @isset($payment_status) @if($payment_status == $key) selected @endif @endisset>{{__($label)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- sort by (phone - client_name - shipping_country - commission_status) --}}
                        <div class="col-md-4">

                            {{-- client_name // phone --}}
                            <div class="row">

                                {{-- client_name --}}
                                <div class="col-md-6">
                                    <div style="margin-bottom: 10px">
                                        <span>&nbsp;</span>
                                        <input  type="text"
                                                class="form-control @isset($client_name) isset @endisset"
                                                id="client_name"
                                                name="client_name"
                                                @isset($client_name) value="{{ $client_name }}" @endisset
                                                placeholder="{{__('Client Name')}}">
                                    </div>
                                </div>

                                {{-- phone --}}
                                <div class="col-md-6">
                                    <div style="margin-bottom: 10px">
                                        <span>&nbsp;</span>
                                        <input  type="text"
                                                class="form-control @isset($phone) isset @endisset"
                                                id="phone"
                                                name="phone"
                                                @isset($phone) value="{{ $phone }}" @endisset
                                                placeholder="{{__('Phone Number')}}">
                                    </div>
                                </div>

                            </div>

                            {{-- commission_status --}}
                            @if($order_from_user_type == 'seller')
                                <div style="min-width: 200px;margin-bottom: 10px">
                                    <span>&nbsp;</span>
                                    <div class="@isset($commission_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                        <select class="form-control  demo-select2" name="commission_status" id="commission_status" onchange="sort_sellers_orders()">
                                            <option value="">{{__('Commission Status')}}</option>
                                            <option value="pending"   @isset($commission_status) @if($commission_status == 'pending') selected @endif @endisset>{{__('pending_commission')}}</option>
                                            <option value="requested"   @isset($commission_status) @if($commission_status == 'requested') selected @endif @endisset>{{__('requested_commission')}}</option>
                                            <option value="delivered"   @isset($commission_status) @if($commission_status == 'delivered') selected @endif @endisset>{{__('delivered_commission')}}</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="row">

                                <div class="col-md-6">
                                    <div style=";margin-bottom: 10px">
                                        <span>&nbsp;</span>
                                        <div class="@isset($playlist_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                            <select class="form-control  demo-select2" name="playlist_status" id="playlist_status" onchange="sort_sellers_orders()">
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

                                @php
                                    $countries = \App\Models\Country::where('type','countries')->get();
                                    $districts = \App\Models\Country::where('type','districts')->get();
                                    $metro = \App\Models\Country::where('type','metro')->get();
                                @endphp
                                {{-- country_id --}}
                                <div class="col-md-6">
                                    <div style=";margin-bottom: 10px">
                                        <span>&nbsp;</span>
                                        <div class="@isset($country_id) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                            <select class="form-control  demo-select2" name="country_id" id="country_id" onchange="sort_sellers_orders()">
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
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class=" @isset($orders_type) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="orders_type" id="orders_type" onchange="sort_sellers_orders()">
                                            <option value="">كل الطلبات</option>
                                            <option value="discount_code" @isset($orders_type) @if($orders_type == 'discount_code') selected @endif @endisset>طلبات برومو كود</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{-- sort by (date)--}}
                        <div class="col-md-4">

                            {{-- start date --}}
                            <div class="col-md-12 text-center" style="margin-bottom:10px">
                                <span class="badge badge-default">من تاريخ الأضافة</span>
                                <input type="text" @isset($from) value="{{format_date($from)}}" @endisset  disabled id="from_date_text" class="form-control @isset($from) isset @endisset" style="position: relative;">
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="from" id="from_date" >
                            </div>

                            {{-- end date --}}
                            <div class="col-md-12 text-center" style="margin-bottom:10px">
                                <span class="badge badge-default">الي تاريخ الأضافة</span>
                                <input type="text" @isset($to) value="{{format_date($to)}}" @endisset  disabled id="to_date_text" class="form-control @isset($to) isset @endisset" style="position: relative;">
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="to" id="to_date" >
                            </div>

                            <div style="margin-top: 10px" class="text-center">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="submit" value="{{__('Search')}}" name="search" class="btn btn-success btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-4">
                                        <input type="submit" value="{{__('Download')}}" name="download" class="btn btn-info btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-warning btn-rounded btn-block" href="{{route('admin.orders.index',$order_from_user_type)}}">{{__('Reset')}}</a>
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
                        @if($order_from_user_type == 'seller')
                            {{__('Statistics Sellers Orders')}}
                        @elseif($order_from_user_type == 'customer')
                            {{__('Statistics Customers Orders')}}
                        @endif
                        <div>
                            @if($phone == null &&
                                $client_name == null &&
                                $order_num == null &&
                                $seller_id == null &&
                                $delivery_status == null &&
                                $payment_status == null &&
                                $country_id == null &&
                                $calling == null &&
                                $commission_status == null &&
                                $playlist_status == null &&
                                $sent_to_wasla == null &&
                                $orders_type == null &&
                                $from == null && $to == null)

                                <span class="text-center badge badge-danger">All</span>

                            @else
                            <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                <span class="text-center badge badge-grey">{{$phone}}</span>
                                <span class="text-center badge badge-grey">{{$commission_status}}</span>
                                <span class="text-center badge badge-grey">{{$playlist_status}}</span>
                                <span class="text-center badge badge-grey">{{$client_name}}</span>
                                <span class="text-center badge badge-grey">{{__(ucfirst(str_replace('_',' ', $delivery_status)))}}</span>
                                <span class="text-center badge badge-grey">{{__(ucfirst($payment_status))}}</span>
                                <span class="text-center badge badge-grey">{{$order_num}}</span>
                                <span class="text-center badge badge-grey">{{$orders_type}}</span>
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
                                    @endisset</span><span class="text-center badge badge-grey">@php
                                        if($seller_id){
                                            $staff = \App\Models\User::find($seller_id);
                                            if($staff){
                                                echo $staff->email;
                                            }
                                        }
                                    @endphp</span>
                                <span class="text-center badge badge-grey">@isset($sent_to_wasla)
                                        {{$sent_to_wasla ?
                                            'تم الأرسال لوصلة'
                                            :
                                            'لم يتم الأرسال لوصلة'}}
                                    @endisset</span>
                                <span class="text-center badge badge-grey">{{$from ? 'من تاريخ الأضافة ' . format_Date($from) : ''}}</span>
                                <span class="text-center badge badge-grey">{{$to ? 'الي تاريخ الأضافة ' . format_Date($to) : ''}}</span>
                            </div>
                            @endif
                        </div>
                    </h5>
                    <div class="row ">
                        <div class="col-md-4">
                            <span class="badge badge-mint">عدد الطلبات {{$orders->total()}}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="badge badge-{{$generalsetting->total_cost}}">= {{single_price($statistics['total_total'])}}   {{__('Total')}} </span>
                            @if($order_from_user_type == 'seller')
                                <span class="badge badge-{{$generalsetting->total_cost}}">= {{single_price($statistics['total_commission'])}}   {{__('Total')}} {{__('commission')}}</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <form action="{{route('orders.request_commission.store')}}" method="POST">
            @csrf

            <div class="modal fade" id="commission_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('Request Commission')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class=" control-label" for="payment_method">{{__('Payment Method')}}</label>
                                <div class="">
                                    <div style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="payment_method">
                                            <option value="in_company">في الشركة</option>
                                            <option value="bank_account">حساب بنكي</option>
                                            <option value="vodafon_cache">فودافون كاش</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" control-label" for="payment_method">{{__('Transfer Number')}}</label>
                                <div class="">
                                    <div style=" margin-bottom: 10px">
                                        <input type="text" class="form-control" name="transfer_number" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div>
                                    <input type="submit" value="{{__('Request Commission')}}" class="btn btn-warning btn-rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix">
                @if($order_from_user_type == 'seller')
                    @isset($seller_id)
                        <div class="pull-left">
                            <a data-toggle="modal" data-target="#commission_modal" class="btn btn-rounded btn-lg btn-danger">{{__('Commission Operations')}}</a>
                        </div>
                    @endisset
                @endif
                <div class="pull-right">
                    {{ $orders->appends(request()->input())->links() }}
                </div>
            </div>
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
                <thead>
                    <tr class="table-tr-color">
                        <th>#</th>
                        <th>{{__('Order Code')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Num. of Products')}}</th>
                        <th>{{__('Client')}}</th>
                        <th>{{__('Address')}}</th>
                        <th>{{__('Total')}}</th>
                        @if($order_from_user_type == 'seller')
                            <th>{{__('Commission')}}</th>
                        @endif
                        <th>{{__('Delivery Status')}}</th>
                        <th>{{__('Calling')}}</th>
                        <th> </th>
                        <th >{{__('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $general_settings = \App\Models\GeneralSetting::first();
                    @endphp
                    @foreach ($orders as $key => $order)
                        <tr>
                            <td>
                                <br>
                                {{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}
                                <br>
                                @isset($seller_id)
                                    @if($order->commission_status == 'pending' && $order->delivery_status == 'delivered')
                                        <input class="form-control" type="checkbox" name="orders[]" value="{{$order->id}}">
                                    @endif
                                @endisset
                            </td>
                            <td>
                                <br>
                                <button class="btn btn-dark" onclick="show_logs('App\\Models\\Order','{{ $order->id }}','admin.orders')">{{ $order->code }}</button>
                                @if($order->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif
                            </td>
                            <td>
                                <br>
                                <span class="badge badge-{{$generalsetting->date_created}}" style="margin: 2px;">
                                    <span>{{__('Date Created')}}</span>
                                    <br>
                                    {{format_date_time(strtotime($order->created_at))}}
                                </span>
                                @if($order_from_user_type == 'seller')
                                <br>
                                <span class="badge badge-{{$generalsetting->date_of_receiving_order}}" style="margin: 2px;">
                                    <span>{{__('Date of Receiving Order')}}</span>
                                    <br>
                                    {{format_Date($order->date_of_receiving_order)}}
                                </span>
                                <br>
                                <span class="badge badge-{{$generalsetting->delivery_date}}" style="margin: 2px;">
                                    <span>{{__('Excepected Deliverd Date')}}</span>
                                    <br>
                                    {{format_Date($order->excepected_deliverd_date)}}
                                </span>
                                @endif
                            </td>
                            <td>
                                <br>
                                {{ count($order->orderDetails) }}
                            </td>
                            <td>
                                <br>
                                @if($order_from_user_type == 'customer')
                                    <small>{{ $order->user ? $order->user->email : ''}}</small> <br>
                                @endif
                                {{$order->client_name}}<br>
                                {{$order->phone_number}}<br>
                                {{$order->phone_number2}}
                            </td>
                            <td>
                                <br>
                                <span class="badge badge-default">{{ $order->shipping_country_name }}</span> , {{ $order->shipping_address }}
                            </td>
                                <td>
                                    <br>
                                    <span class="badge badge-default">+ {{single_price($order->required_to_pay + $order->extra_commission)}} {{__('Total Required To Pay')}}</span><br>
                                    <span class="badge badge-default">+ {{single_price($order->shipping_country_cost)}} {{__('Shipping Cost')}}</span><br>
                                    @if($order_from_user_type == 'seller')
                                        <span class="badge badge-default">- {{single_price($order->deposit_amount)}} {{__('Deposit')}}</span><br>
                                    @endif
                                    <span class="badge badge-success">= {{ single_price($order->required_to_pay + $order->extra_commission + $order->shipping_country_cost - $order->deposit_amount) }}</span>
                                    @if($order->discount_code != null)
                                    <br>
                                        <span class="badge badge-purple">
                                            كود الخصم {{ $order->discount_code }}
                                            /
                                            {{ single_price($order->discount) }}
                                        </span>
                                    @endif
                                </td>
                            @if($order_from_user_type == 'seller')
                                <td>
                                    <br>
                                    {{single_price($order->commission + $order->extra_commission)}}
                                    <hr>
                                    @if($order->commission_status == 'pending')
                                        <span class="badge badge-info">الرصيد معلق</span>
                                    @elseif($order->commission_status == 'requested')
                                        <span class="badge badge-warning">الرصيد مطلوب</span>
                                    @elseif($order->commission_status == 'delivered')
                                        <span class="badge badge-success">تم تسليم الرصيد</span>
                                    @endif
                                </td>
                            @endif
                            <td>
                                <br>
								<select class="form-control demo-select2"  data-minimum-results-for-search="Infinity" data-order_num="{{$order->code}}" onchange="update_delivery_status(this)">
									<option value="pending" @if ($order->delivery_status == 'pending') selected @endif>{{__('Pending')}}</option>
									<option value="on_review" @if ($order->delivery_status == 'on_review') selected @endif>{{__('On review')}}</option>
									<option value="on_delivery" @if ($order->delivery_status == 'on_delivery') selected @endif>{{__('On delivery')}}</option>
									<option value="delivered" @if ($order->delivery_status == 'delivered') selected @endif>{{__('Delivered')}}</option>
									<option value="delay" @if ($order->delivery_status == 'delay') selected @endif>{{__('Delay')}}</option>
									<option value="cancel"   @if($order->delivery_status == 'cancel') selected @endif >{{__('Cancel')}}</option>
								</select>
                                <br>
                                <span class="badge badge-mint">{{ __($order->payment_type) }}</span>
                                <br>
                                <span class="badge badge-dark">{{__(ucfirst($order->payment_status))}}</span>
                                <br>
                                @if($general_settings->delivery_system == 'wasla' && $order->sent_to_wasla)
                                    <span class="badge badge-success">  تم الارسال لوصلة</span>
                                @endif
                            </td>
                            <td>
                                <br>
                                <label class="switch">
                                <input onchange="update_calling(this)" value="{{ $order->id }}" type="checkbox" <?php if($order->calling == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            </td>
                            <td>
                                @if($order_from_user_type == 'seller')
                                    <br>
                                    <span class="badge badge-{{$generalsetting->added_by}}" style="margin: 2px;">
                                        <span>{{__('Seller')}}</span>
                                        <br>
                                        <small>{{ $order->user ? $order->user->email : ''}} </small>

                                    </span>
                                @endif
                                <br>
                                @if($order->DeliveryMan)
                                <span class="badge badge-{{$generalsetting->delivery_man}}" style="margin: 2px;">
                                    <span>{{__('Delivery Man')}}</span>
                                    <br>
                                    <small>{{ $order->DeliveryMan->email}} </small>

                                </span>
                                @endif
                            </td>
                            <td>
                                <br>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{ route('admin.orders.show', encrypt($order->id)) }}"><i class="fa fa-eye" style="color: #134261"></i>{{__('View Details')}}</a></li>
                                        <li><a href="{{route('admin.orders.edit',$order->id)}}" ><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit Order')}}</a></li>
                                        <li><a target="_blanc" href="{{ route('admin.orders.print', $order->id) }}"><i class="fa fa-print" style="color: #2ECC71"></i>{{__('Print')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('admin.orders.destroy',$order->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>

                                    </ul>
                                </div>
                                <hr>
                                @if($order->playlist_status == 'pending')
                                    <a class="btn btn-success btn-rounded"
                                        onclick="change_status('{{$order->code}}','design')">
                                        أرسال للديزاينر
                                    </a>
                                @else
                                    @if($order->playlist_status == 'design')
                                        <span class="badge badge-info" style="cursor: pointer" onclick="change_status('{{$order->code}}','design')">
                                            مرحلة الديزاين
                                        </span>
                                    @elseif($order->playlist_status == 'manufacturing')
                                        <span class="badge badge-warning" style="cursor: pointer" onclick="change_status('{{$order->code}}','manufacturing')">
                                            مرحلة التصنيع
                                        </span>
                                    @elseif($order->playlist_status == 'prepare')
                                        <span class="badge badge-danger" style="cursor: pointer" onclick="change_status('{{$order->code}}','prepare')">
                                            مرحلة التجهيز
                                        </span>
                                    @elseif($order->playlist_status == 'finish')
                                        <span class="badge badge-success" style="cursor: pointer" onclick="change_status('{{$order->code}}','finish')">
                                            جاهز للتوصيل
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        <div class="clearfix">
            <div class="pull-right">
                {{ $orders->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>


@endsection


@section('script')
    <script type="text/javascript">
        function sort_sellers_orders(el){
            $('#sort_sellers_orders').submit();
        }

        function update_delivery_status(obj){
            var status = $(obj).val();
            var order_num = $(obj).data('order_num');
            $.post('{{ route('admin.orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_num:order_num,status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'تم تعديل حالة الطلب');
                }else{
                    showAlert('error', 'Something Went Wrong');
                }
            });
        }

        function change_status(order_num,type){
            $.post('{{ route('admin.orders.playlist_users') }}', {_token:'{{ csrf_token() }}',order_num:order_num}, function(data){
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
            $.post('{{ route('admin.orders.calling') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Calling Client updated successfully');
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }
    </script>
@endsection
