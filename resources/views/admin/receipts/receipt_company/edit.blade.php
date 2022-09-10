@extends('layouts.app')

@section('content')

<div class="col-sm-6 col-md-6 pull-right">
        @if(\App\Models\GeneralSetting::first()->delivery_system == 'ebtekar')
            <div class="panel" style="border-radius:15px">
                <div class="panel-heading" style="padding: 3px">
                    <h3 class="text-center">{{__('Delivery Man')}}</h3>
                </div>
                <div class="panel-body">
                    <form action="{{route('receipt.company.update_delivery_man')}}"  class="form-horizontal" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="shipping_cost_for_delivery">{{__('Delivery Man')}}</label>
                            <div class="col-sm-6">
                                <select class="form-control demo-select2" name="delivery_man_id" id="delivery_man_id" >
                                    <option value="0">{{__('Select Delivery Man ...')}}</option>
                                    @foreach($users as $user) 
                                            <option value="{{$user->id}}" @if ($receipt->delivery_man == $user->id) selected @endif>{{$user->email}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="receipt_id" value="{{$receipt->id}}" id="">
                        
                        <button type="submit" class="btn btn-info btn-block btn-rounded">{{__('Update')}}</button>
                    </form>
                </div>

            </div>
        @elseif(\App\Models\GeneralSetting::first()->delivery_system == 'wasla') 
        
            @if($response['data'] ?? null) 
                <div class="panel" style="border-radius:15px">
                    <div class="panel-heading" style="padding: 3px">
                        <h3 class="text-center">وصلة</h3>
                    </div>
                    <div class="panel-body">  
                        @if(!$receipt->sent_to_wasla)
                            <form action="{{route('receipt.company.send_to_wasla')}}"  class="form-horizontal" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="#">{{__('Countries')}}</label>
                                    <div class="col-sm-4">
                                        @if($response['data'] ?? null)
                                            <select class="form-control demo-select2" name="country_id" id="country_id" required> 
                                                @foreach($response['data'] as $country) 
                                                        <option value="{{$country['id']}}">EGP {{$country['cost']}} - {{$country['name']}}  </option> 
                                                @endforeach
                                            </select>
                                        @else 
                                            not found
                                        @endif
                                    </div>
                                    
                                    <label class="col-sm-2 control-label" for="#">هل سيستلم الكابتن طرد من العميل ؟</label>
                                    <div class="col-sm-4">
                                        <select class="form-control demo-select2" name="type" id="type" required>
                                            <option value="no">لا</option>
                                            <option value="partial">مرتجع جزئي</option>
                                            <option value="change">مرتجع استبدال</option>
                                            <option value="return">مرتجع استرجاع</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="#">الحالة</label>
                                    <div class="col-sm-4">
                                        <select class="form-control demo-select2" name="status" id="status" required>
                                            <option value="draft">في المحفوطة</option>
                                            <option value="sent">مرسلة للشحن</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 control-label" for="#">المنطقة</label>
                                    <div class="col-sm-4">
                                        <input type="text"  name="district" class="form-control" id="" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="#">المطلوب تحصيله شامل الشحن</label>
                                    <div class="col-sm-4">
                                        <input type="number"  name="cost" class="form-control" id="" value="{{$receipt->need_to_pay}}" required>
                                    </div>
                                    <label class="col-sm-2 control-label" for="#">في حالة الأسترجاع</label>
                                    <div class="col-sm-4">
                                        <input type="number"  name="in_return_case" class="form-control" id="" required>
                                    </div>
                                </div>

                                <input type="hidden" name="receipt_id" value="{{$receipt->id}}" id="">
                                
                                <button type="submit" class="btn btn-danger btn-block btn-rounded">أرسال لوصلة</button>
                            </form>
                        @else 
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-center" style="padding:20px">
                                    <i class="fa fa-check-circle" style="font-size: 40px; color: green;padding:10px"></i>
                                    <br>
                                    تم أرسال الفاتورة لوصلة 
                                </h4>
                            </div>
                            <div class="col-md-6" style="padding:45px">
                                <div>
                                    <span style="font-size: 20px;" class="badge badge-{{delivery_status_function($receipt->delivery_status)}}">{{ __(ucfirst(str_replace('_', ' ', $receipt->delivery_status))) }}</span>
                                    @if($receipt->delivery_status == 'delay')
                                        <span class="badge badge-defualt">{{$receipt->delay_reason}}</span>
                                    @elseif($receipt->delivery_status == 'delivered')
                                        <span class="badge badge-success">{{format_Date_time($receipt->done_time)}}</span>
                                    @elseif($receipt->delivery_status == 'cancel')
                                        <span class="badge badge-danger">{{$receipt->cancel_reason}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            @else 
                <div class="panel" style="border-radius:15px">
                    <div class="panel-heading" style="padding: 3px">
                        <h3 class="text-center">وصلة</h3>
                    </div>
                    <div class="panel-body">  
                    <a href="{{ route('profile.index') }}"><i class="demo-pli-male icon-lg icon-fw"></i> {{__('Try login to Wasla again')}}</a>
                </div>
            @endif
        @endif 
</div>

<div style="clear: both"></div>

<div class="col-sm-12" style="margin-bottom: 80px">
    <div class="row">
        <div class="col-md-12">
            
            <div class="panel mb-5">
                <div class="panel-heading" style="padding:10px;margin-bottom:20px">
                    <h3 class="text-center">
                        <a class="badge badge-default" href="{{route('receipt.company')}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                        <br>
                        <span class="badge badge-grey">{{$receipt->order_num}}</span> 
                        {{__('Update Receipt')}}
                    </h3>
                </div>
                <!--Horizontal Form-->
                <!--===================================================-->
                <form class="form-horizontal" action="{{ route('receipt.company.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$receipt->id}}" id="">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5" style="margin-top: 80px;">

                                @include('admin.partials.error_message')
                                
                                <div class="row" style="margin-bottom: 10px;"> 
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;" >
                                        <span class="badge badge-default">{{__('Client Name')}}</span>
                                        <input type="text" class="form-control" name="client_name" id="client_name" value="{{$receipt->client_name}}">
                                    </div> 
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;"> 
                                        <span class="badge badge-default">{{__('Receipt Type')}}</span>
                                        <select class="form-control" name="type" id="type" >
                                            <option value="individual" @if($receipt->type == 'individual') selected @endif>Individual</option>
                                            <option value="corporate" @if($receipt->type == 'corporate') selected @endif>Corporate</option>
                                        </select>
                                    </div>  
                                </div> 
        
                                
                                <div class="row" style="margin-bottom: 10px">   
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                        <span class="badge badge-default">{{__('Phone Number')}}</span>
                                        <input type="text" class="form-control" value="{{$receipt->phone}}"  name="phone" id="phone" >
                                    </div>
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                        <span class="badge badge-default">{{__('Phone Number 2')}}</span>
                                        <input type="text" class="form-control" value="{{$receipt->phone2}}" name="phone2" id="phone2" >
                                    </div>
                                </div>  
        
                                
                                <div class="row" style="margin-bottom: 10px">   
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;"> 
                                        <span class="badge badge-default">{{__('Delivery Date')}}</span>
                                        <input type="text" value="{{format_Date($receipt->deliver_date)}}"  disabled id="deliver_date_text" class="form-control">
                                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="deliver_date" id="deliver_date" >  
                                    </div>   
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;">  
                                        <span class="badge badge-default">{{__('Date Of Receiving Order')}}</span>
                                        <input type="text" value="{{format_Date($receipt->date_of_receiving_order)}}" disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;" >
                                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" >
                                    </div> 
                                </div>  
        
                                
                                
                                <div class="row" style="margin-bottom: 10px">   
                                    
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                        <span class="badge badge-default">{{__('Shipping Cost')}}</span>
                                        <select class="form-control demo-select2" name="shipping_country" id="shipping_country" >
                                            <optgroup label="{{__('Districts')}}">
                                                @foreach($districts as $district)
                                                    <option value={{$district->id}} @if($receipt->shipping_country_id == $district->id) selected @endif>{{$district->name}} - {{single_price($district->cost)}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="{{__('Countries')}}">
                                                @foreach($countries as $country)
                                                    <option value={{$country->id}} @if($receipt->shipping_country_id == $country->id) selected @endif>{{$country->name}} - {{single_price($country->cost)}}</option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="{{__('Metro')}}">
                                                @foreach($metro as $raw)
                                                    <option value={{$raw->id}} @if($receipt->shipping_country_id == $raw->id) selected @endif>{{$raw->name}} - {{single_price($raw->cost)}}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 text-center" style="margin-bottom: 10px;">  
                                        <span class="badge badge-default">{{__('Note')}}</span>
                                        <textarea rows="4"  class="form-control" name="note" ><?php echo $receipt->note ?? '' ?></textarea> 
                                    </div>
                                </div> 
                                
                                <div class="row" style="margin-bottom: 10px">    
                                    <div class="col-sm-4 text-center" style="margin-bottom: 10px;">
                                        <span class="badge badge-default">{{__('Order Cost')}}</span>
                                        <input type="number" min="0" step="0.1" value="{{$receipt->order_cost}}" class="form-control" required  name="order_cost" id="order_cost" ="{{__('Order Cost')}}">
                                    </div>
                                    <div class="col-sm-4 text-center" style="margin-bottom: 10px;">
                                        <span class="badge badge-default">{{__('Deposit')}}</span>
                                        <input type="number" min="0" step="0.1" value="{{$receipt->deposit}}" class="form-control" required name="deposit" id="deposit" >
                                    </div>
                                    <div class="col-sm-4 text-center" style="margin-bottom: 10px;">
                                        <span class="badge badge-default">{{__('Total Cost')}}</span>
                                        <div style="padding:10px">
                                            <span class="badge badge-{{$generalsetting->total_cost}}">{{single_price($receipt->need_to_pay)}}</span>
                                        </div> 
                                    </div>
                                </div> 
                                

                            </div>
                            <div class="col-md-3">  
                                <div class=" text-center" style="margin-top: 60px;"> 
                                    <h3 class="text-center">{{__('Address')}}</h3></span><textarea  class="form-control" rows="7" name="address" >{{$receipt->address}}</textarea> 
                                </div>  
                                <hr>
                                
                                <div class="form-group">  
                                    <div id="photos">
                                        @if(is_array(json_decode($receipt->photos)))
                                            @foreach (json_decode($receipt->photos) as $key => $photo)
                                                <div class="col-md-4 col-sm-4 col-xs-6">
                                                    <div class="img-upload-preview">
                                                        <img loading="lazy"  src="{{ asset($photo) }}" alt="" class="img-responsive">
                                                        <input type="hidden" name="previous_photos[]" value="{{ $photo }}">
                                                        <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">    
                                <h3 class="text-center">{{__('Description')}}</h3>
                                <textarea  class="editor" name="description" ><?php echo $receipt->description; ?></textarea> 
                            </div>
                            
                                <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Update')}}</button> 
                        </div>  
                    </div>
                </form>
                <!--===================================================-->
                <!--End Horizontal Form-->

            </div>

        </div> 
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">

    $(document).ready(function(){ 
        $("#photos").spartanMultiImagePicker({
            fieldName:        'photos[]',
            maxCount:         10,
            rowHeight:        '200px',
            groupClassName:   'col-md-4 col-sm-4 col-xs-6',
            maxFileSize:      '',
            dropFileLabel : "Drop Here",
            onExtensionErr : function(index, file){
                console.log(index, file,  'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr : function(index, file){
                console.log(index, file,  'file size too big');
                alert('File size too big');
            }
        }); 
		$('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
    });

</script>

@endsection