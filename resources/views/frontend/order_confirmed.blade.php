@extends('frontend.layouts.app')

@section('content') 
    <div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited justify-content-center">
                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Payment')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-check-circle"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">4. {{__('Confirmation')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center py-4 border-bottom mb-4">
                                    <i class="la la-check-circle la-3x text-success mb-3"></i>
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
                                                    <td class="w-50 strong-600">{{__('Email')}}:</td>
                                                    <td>{{ $order->user ? $order->user->email : '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="w-50 strong-600">{{__('Shipping address')}}:</td>
                                                    <td>{{$order->shipping_country_name}} , {{$order->shipping_address}}</td>
                                                </tr>
                                                @if($order->discount_code)
                                                    <tr>
                                                        <td class="w-50 strong-600">كود الخصم:</td>
                                                        <td>{{$order->discount_code}} </td>
                                                    </tr>
                                                @endif
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
                                                    <td>{{ __(ucfirst(str_replace('_', ' ', $order->payment_status))) }}</td>
                                                </tr>
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
                                                            @php
                                                                $sum = $order->required_to_pay + $order->shipping_country_cost - $order->deposit_amount;
                                                            @endphp
                                                            <strong><span>{{ single_price($sum) }}</span></strong>
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
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript"> 
        fbq('track', 'Purchase', {currency: "egp", value: '{{$sum}}'});
    </script>
@endsection