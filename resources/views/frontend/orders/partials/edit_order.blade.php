<form class="" action="{{route('user.orders.update',$order->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
    <input name="_method" type="hidden" value="PATCH">
    @csrf
    <div class="form-box bg-white mt-4">
        <div class="form-box-title px-3 py-2">
            {{__('General')}}
        </div>
        <div class="form-box-content p-3"> 

            
                <div class="row" @if(Auth::check() && auth()->user()->user_type == 'customer') style="display:none" @endif> 
                    <div class="col-md-3"> 
                    </div>
                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;"> 
                        <span class="badge badge-default"> {{__('Date Of Receiving Order')}}</span>
                        <input type="text" value="{{format_Date($order->date_of_receiving_order)}}" disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;" >
                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order">
                    </div> 
                </div>
                
                <div class="row" @if(Auth::check() && auth()->user()->user_type == 'customer') style="display:none" @endif>  
                    <div class="col-md-3"> 
                    </div>
                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                        <span class="badge badge-default">{{__('Excepected Deliverd Date')}} </span>
                        <input type="text" value="{{format_Date($order->excepected_deliverd_date)}}"  disabled id="excepected_deliverd_date_text" class="form-control" style="position: relative;">
                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="excepected_deliverd_date" id="excepected_deliverd_date"> 
                    </div>  
                </div>


                <div class="row" @if(Auth::check() && auth()->user()->user_type == 'customer') style="display:none" @endif>
                    <div class="col-md-2">
                        <label>{{__('Client Name')}} <span class="required-star">*</span></label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control mb-3" value="{{$order->client_name}}" id="client_name" name="client_name"  required>
                    </div>
                </div> 

            <div class="row">
                <div class="col-md-2">
                    <label>{{__('Phone Number')}} <span class="required-star">*</span></label>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control mb-3" value="{{$order->phone_number}}" id="phone_number" name="phone_number"   required>
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-2">
                    <label>{{__('Second Phone Number')}} </label>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control mb-3" id="phone_number2" value="{{$order->phone_number2}}" name="phone_number2" >
                </div>
            </div>

        </div>
    </div>

    
    <div class="form-box bg-white mt-4">
        <div class="form-box-title px-3 py-2">
            {{__('Shipping Address')}}
        </div>
        
        <div class="form-box-content p-3">
            <div class="row">
                <div class="col-md-2">
                    <label>{{__('Address Type')}} <span class="required-star">*</span></label>
                </div>
                
                <div class="col-md-10">
                    @php
                        $countries = \App\Models\Country::where('type','countries')->where('status',1)->get(); 
                        $districts = \App\Models\Country::where('type','districts')->where('status',1)->get(); 
                        $metro = \App\Models\Country::where('type','metro')->where('status',1)->get(); 
                    @endphp
                    <div class="col-md-12">
                        <select class="form-control-xs  selectpicker" name="shipping_country" id="shipping_country" >
                            <optgroup label="{{__('Countries')}}">
                                @foreach($countries as $country)
                                    <option value={{$country->id}} @if ($order->shipping_country_id == $country->id) selected @endif>{{$country->name}} - {{single_price($country->cost)}}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="{{__('Districts')}}">
                                @foreach($districts as $district)
                                    <option value={{$district->id}} @if ($order->shipping_country_id == $district->id) selected @endif>{{$district->name}} - {{single_price($district->cost)}}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="{{__('Metro')}}">
                                @foreach($metro as $raw)
                                    <option value={{$raw->id}} @if ($order->shipping_country_id == $raw->id) selected @endif>{{$raw->name}} - {{single_price($raw->cost)}}</option>
                                @endforeach
                            </optgroup>
                        </select> 
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label>{{__('Address')}} <span class="required-star">*</span></label>
                </div>
                <div class="col-md-10">
                    <textarea class="form-control mb-3" name="shipping_address" id="shipping_address" cols="30" rows="5" required>{{$order->shipping_address}}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="form-box bg-white mt-4" @if(Auth::check() && auth()->user()->user_type == 'customer') style="display:none" @endif>
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
                        <option value="Vodafon cash" @if($order->deposit == 'Vodafon cash') selected @endif>Vodafon cash</option>
                        <option value="Etisalat cash"  @if($order->deposit == 'Etisalat cash') selected @endif>Etisalat cash</option>
                        <option value="Bank Account" @if($order->deposit == 'Bank Account') selected @endif>Bank Account</option>
                        <option value="Cash" @if($order->deposit == 'Cash') selected @endif>Cash</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>{{__('Deposit Amount')}} <span class="required-star">*</span></label>
                </div>
                <div class="col-md-4">
                    <input type="number" min="0" step="0.01" value="{{$order->deposit_amount}}"  class="form-control mb-3" name="deposit_amount" id="deposit_amount"  required>
                </div>
            </div>
            
            <div class="form-box-content p-3">
                <div class="row">
                    <div class="col-md-2">
                        <label>{{__('Free Shipping')}}</label>
                    </div>
                    <div class="col-md-2">
                        <label class="switch" style="margin-top:5px;">
                            <input class="form-control mb-3" type="checkbox" name="free_shipping" @if($order->free_shipping == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label>{{__('Shipping Cost')}}</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" min="0" step="0.01" value="{{$order->shipping_cost}}" class="form-control mb-3" name="shipping_cost" id="shipping_cost" value="0" >
                    </div>
                </div>
                <div class="row" id="free_Shipping_reason_row" style="display: none">
                        <div class="col-md-3">
                            <label>{{__('Free Shipping Reason')}} <span class="required-star">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-3" value="{{$order->free_shipping_reason}}" id="free_shipping_reason" name="free_shipping_reason" placeholder="Explain your reason">
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>{{__('Total Order Cost')}} <span class="required-star">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <input type="number" min="0" step="0.01" value="{{$order->total_cost_by_seller}}" class="form-control mb-3" name="total_cost_by_seller" id="total_cost_by_seller"  required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    



    <div class="form-box mt-4 text-right">
        <button type="submit" class="btn btn-info btn-block">{{ __('Update') }}</button>
    </div>

</form> 