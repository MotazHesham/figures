@extends('frontend.layouts.app')

@section('content') 
    <div id="page-content">
        
        <section class="py-4">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-12 mx-auto">
                        <div class="main-content">
                            <!-- Page title -->
                            <div class="page-title">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                            {{__('Track Order')}}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <form class="" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                                <div class="form-box bg-white mt-4">
                                    <div class="form-box-title px-3 py-2">
                                        {{__('Order Info')}}
                                    </div>
                                    <div class="form-box-content p-3">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>{{__('Order Code')}} <span class="required-star">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control mb-3" placeholder="{{__('Order Code')}}" name="order_code" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{__('Track Order')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @isset($order)
                    <div class="row">
                        <div class="col-xl-8 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center py-4 border-bottom mb-4">
                                        <div class="mb-4">
                                            @if($order->delivery_status != 'cancel')
                                            <ul class="process-steps clearfix">
                                                <li @if($order->delivery_status == 'pending') class="active" @else class="done" @endif>
                                                    <div class="icon">1</div>
                                                    <div class="title">{{__('Order placed')}}</div>
                                                </li>
                                                <li @if($order->delivery_status == 'on_review') class="active" @elseif($order->delivery_status == 'on_delivery' || $order->delivery_status == 'delivered') class="done" @endif>
                                                    <div class="icon">2</div>
                                                    <div class="title">{{__('On review')}}</div>
                                                </li>
                                                <li @if($order->delivery_status == 'on_delivery') class="active" @elseif($order->delivery_status == 'delivered') class="done" @endif>
                                                    <div class="icon">3</div>
                                                    <div class="title">{{__('On delivery')}}</div>
                                                </li>
                                                <li @if($order->delivery_status == 'delivered') class="done" @endif>
                                                    <div class="icon">4</div>
                                                    <div class="title">{{__('Delivered')}}</div>
                                                </li>
                                            </ul>
                                            @else
                                            <ul class="process-steps clearfix">
                                                <li @if($order->delivery_status == 'cancel') class="active" @else class="done" @endif>
                                                    <div class="icon" style="background:brown"></div>
                                                    <div class="title" style="color:black">{{__('Order Canceld')}}</div>
                                                    <span>Cancel Reason : {{$order->cancel_reason}}</span>
                                                </li>
                                            </ul>
                                            @endif
                                        </div>
                                        <h1 class="h3 mb-3">{{__('Thank You for Your Order!')}}</h1>
                                        <h2 class="h5 strong-700">{{__('Order Code:')}} {{ $order->code }}</h2>
                                        <p class="text-muted text-italic">{{ __('A copy or your order summary has been sent to') }} {{ $order->user ? $order->user->email : ''}}</p>
                                    </div>
                                    <div class="mb-4">
                                        <h5 class="strong-600 mb-3 border-bottom pb-2">{{__('Order Summary')}}</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="details-table table">
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Order Code')}}:</td>
                                                        <td>{{ $order->code }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Name')}}:</td>
                                                        <td>{{ $order->client_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Phone Number')}}:</td>
                                                        <td>{{ $order->phone_number }} , {{$order->phone_number2}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Email')}}:</td>
                                                        <td>{{ $order->user ? $order->user->email : '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Shipping address')}}:</td>
                                                        <td>{{$order->shipping_country_name}} , {{$order->shipping_address}}<</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="details-table table">
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Order date')}}:</td>
                                                        <td>{{ format_Date_time(strtotime($order->created_at)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Order status')}}:</td>
                                                        <td>{{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Payment method')}}:</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 strong-600">{{__('Payment Status')}}:</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                                                    </tr>
                                                    @if($order->discount_code)
                                                        <tr>
                                                            <td class="w-50 strong-600">كود الخصم:</td>
                                                            <td>{{$order->discount_code}} </td>
                                                        </tr>
                                                    @endif
                                                    @if($order->discount)
                                                        <tr>
                                                            <td class="w-50 strong-600">{{__('Discount')}}:</td>
                                                            <td>{{single_price($order->discount)}} </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="strong-600 mb-3 border-bottom pb-2">{{__('Order Details')}}</h5>
                                        <div>
                                            <table class="details-table table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th width="30%">{{__('Product')}}</th>
                                                        <th>{{__('Variation')}}</th>
                                                        <th>{{__('Quantity')}}</th>
                                                        <th class="text-right">{{__('Price')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->orderDetails as $key => $orderDetail)
                                                        <tr>
                                                            <td>{{ $key+1 }}</td>
                                                            <td>
                                                                @if ($orderDetail->product != null)
                                                                    <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                                        {{ $orderDetail->product->name }}
                                                                    </a>
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
                                                            <td class="text-right">{{ single_price($orderDetail->price) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-5 col-md-6 ml-auto">
                                                <table class="table details-table">
                                                    <tbody>
                                                        @if($order->discount > 0)
                                                            <tr>
                                                                <th>{{__('Discount')}}</th>
                                                                <td class="text-right">
                                                                    <span class="strong-600">{{ single_price($order->discount) }}</span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <th>{{__('Subtotal')}}</th>
                                                            <td class="text-right">
                                                                <span class="strong-600">{{ single_price($order->required_to_pay) }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{__('Shipping')}}</th>
                                                            <td class="text-right">
                                                                <span class="text-italic">{{ single_price($order->shipping_country_cost) }}</span>
                                                            </td>
                                                        </tr> 
                                                        <tr>
                                                            <th>{{__('Deposit')}}</th>
                                                            <td class="text-right">
                                                                <span class="strong-600">-{{ single_price($order->deposit_amount) }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr style="background: #34828285">
                                                            <th><span class="strong-600">{{__('Total')}}</span></th>
                                                            <td class="text-right">
                                                                <strong><span>{{ single_price($order->required_to_pay + $order->shipping_country_cost - $order->deposit_amount) }}</span></strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset
            </div>
        </section>
    </div>
@endsection
