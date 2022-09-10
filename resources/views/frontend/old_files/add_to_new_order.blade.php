<div class="collapse mt-3" id="add_to_new_order" aria-labelledby="headingOne" data-parent="#accordionExample">
    <div class="card card-body" style="background-color: #8e44ad82; border-radius: 37px;">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0" style="color: white">
                        {{__('Request New Order')}}
                    </h2>
                </div>
            </div>
        </div>
        <form class="" action="{{route('user.orders.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
            @csrf
            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__('General')}}
                </div>
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
                        <div class="col-md-5">
                            <input type="text" class="form-control mb-3" id="client_name" name="client_name" required  value="{{old('client_name')}}" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{__('Phone Number')}} <span class="required-star">*</span></label>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control mb-3" id="phone_number" name="phone_number" required value="{{old('phone_number')}}">
                        </div>
                        <div class="col-md-4" id="phone_num">
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-md-3">
                            <label>{{__('Second Phone Number')}} </label>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control mb-3" id="phone_number2" name="phone_number2" value="{{old('phone_number2')}}">
                        </div>
                        <div class="col-md-4" id="phone_num_2">
                        </div>
                    </div>

                </div>
            </div>

            <input class="chosen_price_input" type="hidden" name="price">
            <input class="chosen_variant" type="hidden" name="variant">
            <input type="hidden" name="product_id" value="{{$detailedProduct->id}}">
            <input class="chosen_quntity_input" type="hidden" name="quntity" value="1">
            
            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__('Shipping Address')}}
                </div>
                
                <div class="form-box-content p-3">
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
                                <optgroup label="{{__('Countries')}}">
                                    @foreach($countries as $country)
                                        <option value={{$country->id}}>{{$country->name}} - {{single_price($country->cost)}}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="{{__('Districts')}}">
                                    @foreach($districts as $district)
                                        <option value={{$district->id}}>{{$district->name}} - {{single_price($district->cost)}}</option>
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
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('Address')}} <span class="required-star">*</span></label>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control demo-select2-placeholder mb-3" name="shipping_address" id="shipping_address" cols="30" rows="5" required>{{old('shipping_address')}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

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

            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__('Images')}}
                </div>
                <div class="form-box-content p-3">
                    <div id="product-images2">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Main Images')}} </label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="photos[]" id="photos2-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                <label for="photos2-1" class="mw-100 mb-3">
                                    <span></span>
                                    <strong>
                                        <i class="fa fa-upload"></i>
                                        {{__('Choose image')}}
                                    </strong>
                                </label>
                            </div>
                            
                            <div class="col-md-4">
                                <input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image2()">{{ __('Add More') }}</button>
                    </div>
                    
                </div>
            </div>
            
            
            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__('Specification')}}
                </div>
                <div class="form-box-content p-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('PDF')}}</label>
                        </div>
                        <div class="col-md-10">
                            <input type="file" name="pdf" id="file2-6" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
                            <label for="file2-6" class="mw-100 mb-3">
                                <span></span>
                                <strong>
                                    <i class="fa fa-upload"></i>
                                    {{__('Choose PDF')}}
                                </strong>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('Link')}} </label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control mb-3" id="link2" name="link" value="{{old('link')}}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('File Sent To Email')}}</label>
                        </div>
                        <div class="col-md-2">
                            <label class="switch" style="margin-top:5px;">
                                <input type="checkbox" name="file_sent">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__('Description')}}
                </div>
                <div class="form-box-content p-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('Description')}}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <textarea class="editor" name="description">{{old('description')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-box bg-white mt-4">
                <div class="form-box mt-4 text-right">
                    <button type="submit" class="btn btn-info btn-block">{{ __('Order') }}</button>
                </div>
                
            </div>

        </form> 

    </div>
</div>