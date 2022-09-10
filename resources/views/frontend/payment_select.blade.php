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
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Payment')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
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
        <section class="py-3 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-8">
                        <form action="{{ route('payment.checkout') }}" class="form-default" data-toggle="validator" role="form" method="POST" id="checkout-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="client_name" value="{{$client_name}}">
                            <input type="hidden" name="date_of_receiving_order" value="{{$date_of_receiving_order}}">
                            <input type="hidden" name="excepected_deliverd_date" value="{{$excepected_deliverd_date}}">

                            @if(auth()->check() && auth()->user()->user_type == 'seller')
                                {{-- payment --}}
                                <div class="form-box bg-white mt-4">
                                    <div class="form-box-title px-3 py-2">
                                        {{__('Payment')}}
                                    </div>

                                    <div class="form-box-content p-3">

                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>{{__('Deposit')}} <span class="required-star">*</span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control demo-select2-placeholder mb-3" name="deposit" id="deposit" required>
                                                    <option value="Vodafone cash">Vodafone cash</option>
                                                    <option value="Etisalat cash">Etisalat cash</option>
                                                    <option value="Bank Account">Bank Account</option>
                                                    <option value="Cash">Cash</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>{{__('Deposit Amount')}} <span class="required-star">*</span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" min="0" step="0.01" class="form-control mb-3" name="deposit_amount" id="deposit_amount"  required value="{{old('deposit_amount')}}">
                                            </div>
                                        </div>

                                        <div class="form-box-content p-3">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Free Shipping')}}</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="switch" style="margin-top:5px;">
                                                        <input class="form-control mb-3" type="checkbox" name="free_shipping" value="{{old('free_shipping')}}">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>{{__('Shipping Cost')}}</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" min="0" step="0.01" required class="form-control mb-3" name="shipping_cost_by_seller" id="shipping_cost_by_seller" value="{{old('shipping_cost_by_seller')}}" >
                                                </div>
                                            </div>
                                            <div class="row" id="free_Shipping_reason_row" style="display: none">
                                                    <div class="col-md-3">
                                                        <label>{{__('Free Shipping Reason')}} <span class="required-star">*</span></label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control mb-3" id="free_shipping_reason" name="free_shipping_reason" placeholder="Explain your reason" value="{{old('free_shipping_reason')}}">
                                                    </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Total Order Cost')}} <span class="required-star">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" min="0" step="0.01"  class="form-control mb-3" name="total_cost_by_seller" id="total_cost_by_seller"  required value="{{old('total_cost_by_seller')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-title px-4 py-3">
                                    <h3 class="heading heading-5 strong-500">
                                        {{__('Select a payment option')}}
                                    </h3>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">

                                        <div class="col-md-6 mx-auto">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="payment_option mb-4" data-toggle="tooltip" data-title="Cash on Delivery">
                                                        <input type="radio" id="" name="payment_option" value="cash_on_delivery" checked>
                                                        <span>
                                                            <img loading="lazy"  src="{{ asset('frontend/images/icons/cards/cod.png')}}" class="img-fluid">
                                                        </span>
                                                    </label>
                                                </div>
                                                {{-- <div class="col-6">
                                                    <label class="payment_option mb-4" data-toggle="tooltip" data-title="Online Payment">
                                                        <input type="radio" id="" name="payment_option" value="paymob">
                                                        <span>
                                                            <img loading="lazy"  src="{{ asset('frontend/images/icons/cards/VisaMaster.jpg')}}" class="img-fluid">
                                                        </span>
                                                    </label>
                                                </div>  --}}
                                            </div>
                                        </div>

                                        <input type="hidden" name="shipping_country_id" value="{{$shipping_country_id}}">
                                        <input type="hidden" name="shipping_address" value="{{$shipping_address}}">
                                        <input type="hidden" name="phone_number" value="{{$phone_number}}">
                                        <input type="hidden" name="phone_number2" value="{{$phone_number2}}">
                                        <input type="hidden" name="first_name" value="{{$first_name}}">
                                        <input type="hidden" name="last_name" value="{{$last_name}}">
                                        <input type="hidden" name="discount_code" value="{{$discount_code ?? ''}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center pt-4">
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__('Return to shop')}}
                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{__('Complete Order')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4 ml-lg-auto">
                        @include('frontend.partials.cart_summary')
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
