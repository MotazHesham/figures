
<div style="display: flex;justify-content:center">
    <div style="text-align: center;
                width: 100%;
                align-self: center;
                align-content: center;
                align-items: center;
                box-shadow: 1px 2px 9px grey;
                padding: 30px;
                background: #8080800a;
                border-radius: 8px;">

            <div  style="text-align: start; ">
                <h1 style="float: left;display:inline">
                    @if($generalsetting->logo != null)
                        <img loading="lazy"  src="{{ asset($generalsetting->logo) }}" height="40" style="display:inline-block;">
                    @else
                        <img loading="lazy"  src="{{ asset('frontend/images/logo/logo.png') }}" height="40" style="display:inline-block;">
                    @endif {{ $generalsetting->site_name }}
                </h1>
            </div>
                
            <div style="float: right;display:inline" >
                
                <small >
                    {{ $generalsetting->address }} <br>
                    {{ $generalsetting->email }} <br>
                    {{ $generalsetting->phone }}
                </small>
            </div>
            <div style="clear: both"></div>
        <hr>

        <div> 
            <h1 class="h3">{{__('Thank You for Your Order!')}}</h1>
            <h2 class="h5">{{__('Order Code:')}} {{ $order->code }}</h2> 
            <small>{{ __('You can track your order here') }} <a href="{{route('orders.track')}}">Track Order</a></small>
        </div>
        <br> <br>
        <h3 style="float:left;margin-left:5%">{{__('Order Summary')}}</h3> 
        <div style="clear: both"></div>
        <hr style="float: left;margin-left:5%" width="150">
        <br><br>
        <div >
        

            <table style="width: 50%;text-align:center; ">
                <tr>
                    <th>{{__('Order date')}}</th>
                    <th>{{ format_Date_time(strtotime($order->created_at)) }}</th>
                </tr>
                <tr>
                    <th>{{__('Payment method')}}</th>
                    <th>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</th>
                </tr>
                <tr>
                    <th>{{__('Phone Number')}}</th>
                    <th>{{ $order->phone_number }} , {{$order->phone_number2}}</th>
                </tr>
                <tr>
                    <th>{{__('Order status')}}</th>
                    <th>{{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</th>
                </tr>
                <tr>
                    <th>{{__('Shipping address')}}</th>
                    <th>{{ $order->shipping_country_name}} , {{$order->shipping_address }}</th>
                </tr>
            </table> 

        </div>  
        <br><br><br>
        <div>
            <h3>{{__('Order Details')}}</h3>
            <hr width="50">
            <div>
                <table style="width: 100%;text-align:center"> 
                    <thead>
                        <tr>
                            <th style="padding: 20px">#</th>
                            <th>{{__('Product')}}</th>
                            <th>{{__('Variation')}}</th>
                            <th>{{__('Quantity')}}</th>
                            <th>{{__('Price')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $key => $orderDetail)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>
                                    @if ($orderDetail->product != null)
                                        <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                            {{ $orderDetail->product->name }}
                                        </a>
                                        <br>
                                        <img src="{{asset($orderDetail->product->thumbnail_img)}}" width="75" height="75" alt="">
                                    @else
                                        <strong>{{ __('Product Unavailable') }}</strong>
                                    @endif
                                </td>
                                <td>
                                    {{ $orderDetail->variation }}
                                </td>
                                <td>
                                    {{ $orderDetail->quantity }}
                                </td>
                                <td>{{ single_price($orderDetail->price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
            <br><br><br>
            <div>
                <table style="width: 50%;text-align:end;float: right;">
                    <tbody>
                        <tr>
                            <td>
                                <span>{{ single_price($order->required_to_pay) }}</span>
                            </td>
                            <th>{{__('Subtotal')}}</th>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-italic">{{ single_price($order->shipping_country_cost) }}</span>
                            </td>
                            <th>{{__('Shipping')}}</th>
                        </tr> 
                        <tr>
                            <td>
                                <span>-{{ single_price($order->deposit_amount) }}</span>
                            </td>
                            <th>{{__('Deposit')}}</th>
                        </tr>
                        <tr>
                            <td>
                                <strong><span>{{ single_price($order->required_to_pay + $order->shipping_country_cost - $order->deposit_amount) }}</span></strong>
                            </td>
                            <th><span>{{__('Total')}}</span></th>
                        </tr>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>  
</div>
