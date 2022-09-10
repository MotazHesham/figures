<form class="" action="{{route('user.orders.products.update',$order_details->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
    <input name="_method" type="hidden" value="PATCH">
    @csrf
    <input type="hidden" name="order_id" value="{{$order->id}}">
    <input type="hidden" name="product_id" value="{{$order_details->product_id}}">
    @if($order_details->product->added_by == 'designer')

        <div class="form-box-content p-3">
            <div class="row">
                <div class="col-md-2">
                    <label>{{__('Quanitity')}}</label>
                </div>
                <div class="col-md-4">
                    <input type="number" step="1" min="1"  class="form-control mb-3" id="quantity" name="quantity" value="{{$order_details['quantity']}}"  >
                </div>
            </div>
        </div>
    @else
        <div class="form-box bg-white mt-4">
            <div class="form-box-title px-3 py-2">
                {{__('Images To Print in Product')}}
            </div>
            <div class="form-box-content p-3">
                <div id="product-images">
                    
                    @if(is_array(json_decode($order_details->photos)) && count(json_decode($order_details->photos)) > 0)
                        @foreach (json_decode($order_details->photos) as $key => $photo)
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>
                                </div> 
                                <input type="hidden" name="previous_photos[]"  value="{{$photo}}">
                                <div class="col-md-2">
                                    <img src="{{asset($photo)}}" width="50" height="50" alt="">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" value="{{json_decode($order_details->photos_note)[$key]?? ''}}" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                                </div> 
                            </div>
                        @endforeach
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>
                        </div>
                        <div class="col-md-4">
                            <input type="file" name="photos[]" id="photos-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*"/>
                            <label for="photos-1" class="mw-100 mb-3">
                                <span></span>
                                <strong>
                                    <i class="fa fa-upload"></i>
                                    {{__('Choose image')}}
                                </strong>
                            </label>
                        </div>
                        
                        <div class="col-md-4">
                            <input type="text"  name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                        </div> 
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{ __('Add More') }}</button>
                </div>
                
            </div>
        </div>

        @php
            $has_unit_attribute = false;
            $detailedProduct = \App\Models\Product::find($order_details->product_id);
        @endphp
        @if ($detailedProduct->choice_options != null)
            @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)

                @php
                    if(\App\Models\Attribute::find($choice->attribute_id)->name == 'unit'){
                        $has_unit_attribute = true;
                    }
                @endphp

            @endforeach
        @endif

        
        @if(!$has_unit_attribute)
            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__(' ')}}
                </div>
                <div class="form-box-content p-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('Quanitity')}}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="number" step="1" min="1"  class="form-control mb-3" id="quantity" name="quantity" value="{{$order_details->quantity}}"  >
                        </div>
                    </div>
                </div>
            </div> 
        @else 
            <input type="hidden" name="quantity" value="{{$order_details->quantity}}">
        @endif
        
        @if(Auth::check() && auth()->user()->user_type == 'seller')
            <div class="form-box bg-white mt-4">
                <div class="form-box-title px-3 py-2">
                    {{__('Specification')}}
                </div>
                <div class="form-box-content p-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('PDF')}}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="file" name="pdf" id="file-6" value="{{$order_details->pdf}}" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
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
                            <input type="text" class="form-control mb-3" id="link" name="link" value="{{$order_details->link}}">
                        </div>
                    </div>  
                    
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{__('File Sent To Email')}}</label>
                        </div>
                        <div class="col-md-2">
                            <label class="switch" style="margin-top:5px;">
                                <input  type="checkbox" name="file_sent" <?php if($order_details->email_sent == 1) echo "checked";?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                            <textarea class="editor" name="description">{{$order_details->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
    <div class="form-box mt-4 text-right">
        <button type="submit" class="btn btn-info btn-block">{{ __('Save') }}</button>
    </div>
</form> 