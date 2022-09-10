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
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon mb-0 ">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
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

        
        <section class="py-4 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                        <div class="col-lg-8"> 
                    
                            <form class="" action="{{route('checkout.payment_select')}}" method="POST" enctype="multipart/form-data" id="choice_form">
                                @csrf
                                <div class="card">
                                    <div class="card-header"> 
                                        @include('admin.partials.error_message')
                                    </div>
                                    <div class="card-body"> 

                                        <div class="form-box bg-white mt-4"> 
                                            @if(auth()->user()->user_type == 'seller') 
                                                <div class="form-box-content p-3">
                                                    <div class="row"> 
                                                        <div class="col-md-3"> 
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;"> 
                                                            <span class="badge badge-default"> {{__('Date Of Receiving Order')}}</span>
                                                            <input type="text" disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;" >
                                                            <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" required>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="row">  
                                                        <div class="col-md-3"> 
                                                        </div>
                                                        <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                                            <span class="badge badge-default">{{__('Excepected Deliverd Date')}} </span>
                                                            <input type="text"  disabled id="excepected_deliverd_date_text" class="form-control" style="position: relative;">
                                                            <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="excepected_deliverd_date" id="excepected_deliverd_date" required> 
                                                        </div>  
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>{{__('Client Name')}} <span class="required-star">*</span></label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control mb-3" id="client_name" name="client_name" required  value="{{old('client_name')}}" >
                                                        </div>
                                                    </div>
                                                </div> 
                                            @endif  
                                        </div>  
                                            
                                        <div class="form-box-content p-3">
                                            @php 
                                                $name = explode(" ",auth()->user()->name);
                                            @endphp 
                                            <div class="row"> 
                                                <div class="col-md-6">
                                                <label>{{__('First Name')}} <span class="required-star">*</span></label>
                                                    <input type="text" class="form-control mb-3" id="first_name" name="first_name" required value="{{ $name[0] ?? ''}}">
                                                </div> 
                                                <div class="col-md-6">
                                                    <label>{{__('Last Name')}} <span class="required-star">*</span></label>
                                                    <input type="text" class="form-control mb-3" id="last_name" name="last_name" required value="{{ $name[1] ?? ''}}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>{{__('Phone Number')}} <span class="required-star">*</span></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control mb-3" id="phone_number" name="phone_number" required value="{{old('phone_number',auth()->user()->phone ?? '')}}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <label>{{__('Second Phone Number')}} </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control mb-3" id="phone_number2" name="phone_number2" value="{{old('phone_number2')}}">
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>{{__('Address Type')}} <span class="required-star">*</span></label>
                                                </div>
                                                @php
                                                    $countries = \App\Models\Country::where('type','countries')->where('status',1)->get(); 
                                                    $districts = \App\Models\Country::where('type','districts')->where('status',1)->get(); 
                                                    $metro = \App\Models\Country::where('type','metro')->where('status',1)->get(); 
                                                @endphp
                                                <div class="col-md-12">
                                                    <select class="form-control-xs selectpicker" name="shipping_country" id="shipping_country" >
                                                        <optgroup label="{{__('Districts')}}">
                                                            @foreach($districts as $district)
                                                                <option value={{$district->id}}>{{$district->name}} - {{single_price($district->cost)}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        <optgroup label="{{__('Countries')}}">
                                                            @foreach($countries as $country)
                                                                <option value={{$country->id}}>{{$country->name}} - {{single_price($country->cost)}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        <optgroup label="{{__('Metro')}}">
                                                            @foreach($metro as $raw)
                                                                <option value={{$raw->id}}>{{$raw->name}} - {{single_price($raw->cost)}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-2">
                                                    <label>{{__('Address')}} <span class="required-star">*</span></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <textarea class="form-control demo-select2-placeholder mb-3" name="shipping_address" id="shipping_address" cols="30" rows="5" required>{{old('shipping_address')}}</textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>كود الخصم </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control mb-3" id="discount_code" name="discount_code" value="{{old('discount_code')}}">
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                        
                                <div class="row align-items-center pt-4">
                                    <div class="col-md-6">
                                        <a href="{{ route('home') }}" class="link link--style-3">
                                            <i class="ion-android-arrow-back"></i>
                                            {{__('Return to shop')}}
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="submit" class="btn btn-styled btn-base-1">{{__('Continue to Payment')}}</a>
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

@section('script')
<script type="text/javascript">
    function add_new_address(){
        $('#new-address-modal').modal('show');
    }
</script>
@endsection
