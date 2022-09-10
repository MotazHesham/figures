<div class="collapse mt-3" id="add_to_existing_order" aria-labelledby="headingOne" data-parent="#accordionExample">
    <div class="card card-body" style="background-color: #e8e8e8; border-radius: 37px;">
        <form class="" action="{{route('user.orders.products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
            @csrf
            <input type="hidden" name="added_by" value="seller"> 
            
            <input class="chosen_price_input" type="hidden" name="price">
            <input class="chosen_variant" type="hidden" name="variant">
            <input type="hidden" name="product_id" value="{{$detailedProduct->id}}">
            <input class="chosen_quntity_input" type="hidden" name="quantity" value="1">
            
            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__('General')}}
                </div>
                <div class="form-box-content p-3">
                    <div class="row" id="order_code">
                        <div class="col-md-2">
                            <label>{{__('Order')}} <span class="required-star">*</span></label>
                        </div>
                        @php
                            if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff'){
                                $orders_existing = \App\Models\Order::orderBy('created_at', 'desc')->get();
                            }else{ 
                                $orders_existing = \App\Models\Order::where('delivery_status','pending')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
                            }
                        @endphp         
                        <div class="col-md-10">
                            <select class="form-control demo-select2-placeholder mb-3" name="order_code" id="order_code" required>
                                @foreach($orders_existing as $order)  
                                        <option value="{{$order->id}}">{{$order->code}} - {{$order->client_name}} - {{$order->phone_number}}</option> 
                                @endforeach
                            </select>
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
                                <label>{{__('Main Images')}}</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="photos[]" id="photos-2" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                <label for="photos-2" class="mw-100 mb-3">
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
                            <input type="file" name="pdf" id="file-6" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
                            <label for="file-6" class="mw-100 mb-3">
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
                            <label>{{__('Link')}}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control mb-3" id="link" name="link" value="{{old('link')}}">
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
                                <textarea rows="8" cols="50"  name="description">{{old('description')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-box mt-4 text-right">
                <button type="submit" class="btn btn-info btn-block">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>