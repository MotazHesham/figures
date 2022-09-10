@php
    if(auth()->user()->user_type == 'delivery_man'){
        $extend = 'delivery_man.app';
    }else{
        $extend = 'layouts.app';
    }
@endphp

@extends($extend)  

@section('styles')
    <style>
        td{
            font-size: 15px
        }
        .order-card{
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
            overflow: hidden;
            max-width: 700px;
            padding:15px;
            border-radius:14px;
            box-shadow: 2px 1px 19px #48484863;
        }
        .order-card-left-side{
            background-image:linear-gradient(#348282,#3580b3);
            border-radius:14px;
            padding:15px;
        } 
        .order-card:hover {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }  
        .order-card-actions{
            position:absolute;
            right: 0;
            top: -30px;
            visibility: hidden;
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
        }
        .order-card-actions a{ 
            background-color: rgb(228, 227, 227);
            border-radius: 25px;
            padding:10px; 
            margin: 3px;
        }
        .order-card-actions i{
            color:rgb(62, 170, 62)
        }
        .badge-default {
            background-color: #e9eeef;
            color: #7a878e;
        }
        .address-scrollable::-webkit-scrollbar {
            width: 5px;
        }

        .address-scrollable::-webkit-scrollbar-track {
            background:rgba(184, 34, 34, 0); 
            border-radius: 10px;
        }

        .address-scrollable::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background: rgba(159, 53, 53, 0.8); 
        }
        .address-scrollable::-webkit-scrollbar-thumb:hover {
            background: black; 
        }
    </style>
@endsection

@section('content') 


<div class="panel"> 
    <div class="panel-body">

        <h2 class="text-center">
            {{__('Delivery Orders')}}  
        </h2> 

        
        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_delivery_orders" action="" method="GET" >  
                    <h5 class="text-center">{{__('Search In Delivery Orders')}}</h5>  
                    <div class="row">
                        {{-- sort by (delivery_man - payment_status  ) --}}
                        <div class="col-md-4"> 
                        @if(Auth::user()->user_type != 'delivery_man')
                            <span>&nbsp;</span>
                            <div class="@isset($delivery_man_id) isset @endisset" style="min-width: 200px;margin-bottom: 10px">
                                <select class="form-control demo-select2" name="delivery_man_id" id="delivery_man_id" onchange="sort_delivery_orders()">
                                    <option value="">{{__('Choose Delivery Man')}}</option> 
                                    @foreach($delivery_man_users as $user)
                                        <option value="{{$user->id}}"
                                                @isset($delivery_man_id) @if($delivery_man_id == $user->id) selected @endif @endisset>
                                                {{$user->email}}
                                        </option>
                                    @endforeach 
                                </select>
                            </div>  
                        @endif

                            
                            <div class="row"> 
                                <div class="col-md-12"> 
                                    <span>&nbsp;</span>
                                    <div class="@isset($payment_status) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                        <select class="form-control  demo-select2" name="payment_status" id="payment_status" onchange="sort_delivery_orders()">
                                            <option value="">{{__('Payment Status')}}</option> 
                                            @foreach(\App\Models\Order::PAYMENT_STATUS_SELECT as $key => $label)
                                                <option value="{{$key}}"   @isset($payment_status) @if($payment_status == $key) selected @endif @endisset>{{__($label)}}</option>
                                            @endforeach 
                                        </select>
                                    </div>  
                                </div>
                            </div>  

                            @php
                                $countries = \App\Models\Country::where('type','countries')->where('status',1)->get(); 
                                $districts = \App\Models\Country::where('type','districts')->where('status',1)->get(); 
                                $metro = \App\Models\Country::where('type','metro')->where('status',1)->get(); 
                            @endphp
                            {{-- country_id --}}
                            <div style="min-width: 200px;margin-bottom: 10px">
                                <span>&nbsp;</span> 
                                <div class="@isset($country_id) isset @endisset" style="min-width: 100px;margin-bottom: 10px">
                                    <select class="form-control  demo-select2" name="country_id" id="country_id" onchange="sort_delivery_orders()">
                                        <option value="">{{__('Shipping Country')}}</option> 
                                        <optgroup label="{{__('Countries')}}">
                                            @foreach($countries as $country)
                                                <option value={{$country->id}} @if($country->id == $country_id) selected @endif>{{$country->name}} - {{single_price($country->cost)}}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="{{__('Districts')}}">
                                            @foreach($districts as $district)
                                                <option value={{$district->id}} @if($district->id == $country_id) selected @endif>{{$district->name}} - {{single_price($district->cost)}}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="{{__('Metro')}}">
                                            @foreach($metro as $raw)
                                                <option value={{$raw->id}} @if($raw->id == $country_id) selected @endif>{{$raw->name}} - {{single_price($raw->cost)}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>  
                            </div> 
                            
                        </div> 

                        {{-- sort by (phone - order_num - client_name - calling) --}}
                        <div class="col-md-4">
                            
                            {{-- phone --}}
                            <div style="min-width: 200px;margin-bottom: 10px">
                                <span>&nbsp;</span>
                                <input  type="text" 
                                        class="form-control @isset($phone) isset @endisset" 
                                        id="phone" 
                                        name="phone"
                                        @isset($phone) value="{{ $phone }}" @endisset 
                                        placeholder="{{__('Phone Number')}}">
                            </div>  

                            {{-- client_name --}}
                            <div style="min-width: 200px;margin-bottom: 10px">
                                <span>&nbsp;</span>
                                <input  type="text" 
                                        class="form-control @isset($client_name) isset @endisset" 
                                        id="client_name" 
                                        name="client_name"
                                        @isset($client_name) value="{{ $client_name }}" @endisset 
                                        placeholder="{{__('Client Name')}}">
                            </div>  

                            {{-- order_num - calling --}}
                            <div class="row">
                                <div class="col-md-6"> 
                                    <span>&nbsp;</span>
                                    <div class=" @isset($calling) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="calling" id="calling" onchange="sort_delivery_orders()">
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
                                <input type="text" @isset($to) value="{{format_date($to)}}" @endisset  disabled id="to_date_text" class="form-control  @isset($to) isset @endisset" style="position: relative;">
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="to" id="to_date" >   
                            </div>

                            <div style="margin-top: 10px">
                                <span>&nbsp;</span>
                                <div class="row">
                                    <div class="col-md-4"> 
                                        <input type="submit" value="{{__('Search')}}" name="search" class="btn btn-success btn-rounded btn-block" >
                                    </div> 
                                    <div class="col-md-4"> 
                                        <a class="btn btn-warning btn-rounded btn-block" href="{{route('deliveryman.orders.index',$type)}}">{{__('Reset')}}</a>
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
                        <div> 
                            @if($phone == null && 
                                $payment_status == null &&
                                $client_name == null &&  
                                $order_num == null &&  
                                $calling == null &&
                                $delivery_man_id == null && 
                                $country_id == null && 
                                $from == null && $to == null)

                                <span class="text-center badge badge-danger">All</span>

                            @else
                            <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                <span class="text-center badge badge-grey">{{$phone}}</span>
                                <span class="text-center badge badge-grey">{{__(ucfirst($payment_status))}}</span>
                                <span class="text-center badge badge-grey">{{$client_name}}</span> 
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
                                <span class="text-center badge badge-grey">@php
                                    if($delivery_man_id){
                                        $user = \App\Models\User::find($delivery_man_id);
                                        if($user){ 
                                            echo $user->email;
                                        }
                                    }
                                @endphp</span>
                                <span class="text-center badge badge-grey">{{$from ? 'من تاريخ الأضافة ' . format_Date($from) : ''}}</span>
                                <span class="text-center badge badge-grey">{{$to ? 'الي تاريخ الأضافة ' . format_Date($to) : ''}}</span>
                            </div>
                            @endif
                        </div>
                    </h5>  
                </div>
            </div>

        </div>

    </div>
</div>
<div class="panel">
    <div class="panel-body">
        
        <div class="clearfix">
            <div class="pull-right">
                {{ $items->appends(request()->input())->links() }}
            </div>
        </div>

        <div class="row">
            @foreach ($items as $key => $deliver_order)
                {{-- order card --}}
                <div class="col-md-4"> 
                    <div class="card order-card" data-id="{{$key}}" id="order-card-{{$key}}" style="margin-bottom:30px">
                        {{-- code --}}
                        <div class=" order-card-left-side text-center mb-3" style="color: white;margin-bottom:20px"> 
                            <div class="row">
                                <div class="col-xs-4 col-md-4">
                                    {{$deliver_order['order_num']}}
                                </div>
                                <div class="col-xs-4 col-md-4">
                                    <span class="badge badge-{{delivery_status_function($deliver_order['delivery_status'])}}">{{ __(ucfirst(str_replace('_', ' ', $deliver_order['delivery_status']))) }}</span>
                                    <br>
                                    <span class="badge badge-grey">{{__(ucfirst($deliver_order['payment_status']))}}</span>
                                </div>
                                <div class="col-xs-4 col-md-4">
                                    <span class="badge badge-default">{{__('Date Created')}}</span><br>
                                    {{ format_Date(strtotime($deliver_order['send_to_deliveryman_date'])) }}
                                </div>
                            </div> 
                        </div> 
                        {{-- order info --}}
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="row mb-3">
                                    <div class="col-xs-4 col-md-4">
                                        <span class="badge badge-default">{{__('Shipping Cost')}}</span><br>
                                        <span style="color: grey;font-weight:400">
                                            {{ single_price($deliver_order['shipping_cost']) }}
                                        </span> <br>
                                    </div>
                                    <div class="col-xs-4 col-md-4">
                                        <span class="badge badge-default">{{__('Client Name')}}</span><br>
                                        <span style="color: grey;font-weight:400">{{$deliver_order['client_name']}}</span><br>
                                        <span class="badge badge-default">{{__('Phone Number')}}</span><br>
                                        <div style="color: grey;font-weight:400;height:18px">{{$deliver_order['phone']}}</div>
                                    </div>
                                    <div class="col-xs-4 col-md-4">
                                        <span class="badge badge-default">{{__('Total')}}</span><br>
                                        <div>
                                            <span class="badge badge-default">- {{ single_price($deliver_order['deposit']) }}{{__('Deposit')}}</span><br>
                                            <span class="badge badge-default">+ {{ single_price($deliver_order['order_cost']) }}{{__('Order Cost')}}</span><br> 
                                            <span class="badge badge-success">= {{ single_price($deliver_order['total']) }}</span>
                                        </div>

                                    </div>
                                </div>
                                @php
                                    $route = route($deliver_order['route'] . '.supplied');
                                @endphp
                                <div class="row" style="margin-top:20px">
                                    <div class="col-xs-6 col-md-6">
                                        @if(Auth::user()->user_type != 'delivery_man')
                                            <span class="badge badge-default">{{__('Supplied')}}</span><br>
                                            <label class="switch">
                                                <input onchange="update_supplied_delivery('{{$route}}','{{ $deliver_order['order_num'] }}',this)" type="checkbox" <?php if($deliver_order['supplied'] == 1) echo "checked";?> >
                                                <span class="slider round"></span></label>
                                        @endif
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        @if($type == 'delivered' || $type == 'supplied')
                                            <span class="badge badge-default">{{__('Done Time')}}</span><br>
                                            {{format_Date_time($deliver_order['done_time'])}}
                                        @endif
                                    </div>
                                </div>
                                <hr style="width: 80%">
                                <div style="text-align: end">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <span class="badge badge-default">{{__('Address')}}</span>
                                            <br>
                                            <div style="color: rgb(110, 110, 206);    
                                                        height: 60px;
                                                        padding: 5px ;
                                                        overflow-y: scroll;
                                                        box-shadow: 0px 4px 5px #bd808063;
                                                        border-radius: 7px;" class="address-scrollable">({{$deliver_order['address']}})</div> 
                                        </div>
                                        <div class="col-md-5">
                                            @if(Auth::user()->user_type != 'delivery_man')
                                                <span class="badge badge-default">{{__('Delivery Man')}}</span>
                                                <br>
                                                <span class="badge badge-{{$generalsetting->delivery_man}}" style="margin: 2px;">
                                                    {{ $deliver_order['delivery_man_id']}}  
                                                </span> 
                                            @endif
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div> 

                        {{-- action Buttons --}}
                        <div class="order-card-actions" id="order-card-actions-{{$key}}">
                            @if(in_array($deliver_order['delivery_status'],['on_delivery','delay']))
                                <a style="cursor: pointer" href="{{route('deliveryman.orders.show',encrypt($deliver_order['order_num']))}}" title="{{__('Order Details')}}"><i class="fa fa-tag"></i></a> 
                            @else
                                @if(Auth::user()->user_type != 'delivery_man')
                                    <a style="cursor: pointer" href="{{route('deliveryman.orders.show',encrypt($deliver_order['order_num']))}}" title="{{__('Order Details')}}"><i class="fa fa-tag"></i></a> 
                                @endif
                            @endif
                            

                            @if(Auth::user()->user_type != 'delivery_man')
                                {{-- <a style="cursor: pointer" onclick="confirm_modal('{{route('deliveryman.orders.delete',encrypt($deliver_order['order_num']))}}');" title="{{__('Delete Order')}}"><i class="fa fa-trash"></i></a> --}}
                            @endif
                        
                            <a target="_blanc" href="{{route('deliveryman.orders.print',encrypt($deliver_order['order_num']))}}" title="{{__('Print')}}"><i class="fa fa-print"></i></a>
                        
                        </div>
                    
                    </div>
                </div>
            @endforeach 
        </div>
        
        <div class="clearfix">
            <div class="pull-right">
                {{ $items->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div> 

@endsection 
@section('script')
    <script type="text/javascript"> 

        $(document).ready(function () {  
            $('.order-card').hover(function(){ 
                var id = $(this).data('id'); 
                $('#order-card-actions-'+id).css('top','10px');
                $('#order-card-actions-'+id).css('visibility','visible');
            });  
            $('.order-card').mouseleave(function(){ 
                var id = $(this).data('id');  
                $('#order-card-actions-'+id).css('top','-30px');
                $('#order-card-actions-'+id).css('visibility','hidden');
            });  
        }); 

        function sort_delivery_orders(el){
            $('#sort_delivery_orders').submit();
        }  
        
        function update_supplied_delivery(route,value,el){  
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post(route, {_token:'{{ csrf_token() }}', order_num:value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Supplied updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }
        
    </script>
@endsection
