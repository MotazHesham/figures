@extends('layouts.app')

@section('content')

<div class="col-sm-12">
    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h2 class="text-center" >
                <a class="badge badge-default" href="{{route('receipt.company')}}">
                    <i class="fa fa-chevron-circle-left"></i> 
                    back
                </a> 
                {{__('Add Company Receipt')}} 
            </h2>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.company.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="row">
                    <div class="col-md-5" style="margin-top: 80px;">
                        <div class="row" style="margin-bottom: 10px;"> 
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;" >
                                <span class="badge badge-default">{{__('Client Name')}}</span>
                                <input type="text" class="form-control" name="client_name" id="client_name" value="{{$receipt->client_name ?? ''}}">
                            </div> 
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;"> 
                                <span class="badge badge-default">{{__('Receipt Type')}}</span>
                                <select class="form-control" name="type" id="type" >
                                    <option value="individual" @isset($receipt) @if($receipt->type == 'individual') selected @endif @endisset>Individual</option>
                                    <option value="corporate" @isset($receipt) @if($receipt->type == 'corporate') selected @endif @endisset>Corporate</option>
                                </select>
                            </div>  
                        </div> 

                        
                        <div class="row" style="margin-bottom: 10px"> 
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">{{__('Phone Number')}}</span>
                                <input type="text" class="form-control" value="{{$receipt->phone ?? $phone}}"  name="phone" id="phone" >
                            </div>
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">{{__('Phone Number 2')}}</span>
                                <input type="text" class="form-control" value="{{$receipt->phone2 ?? ''}}" name="phone2" id="phone2" >
                            </div>
                        </div>  

                        
                        <div class="row" style="margin-bottom: 10px">   
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">{{__('Delivery Date')}}</span>
                                <input type="text"  disabled id="deliver_date_text" class="form-control" style="position: relative;">
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="deliver_date" id="deliver_date" > 
                            </div>  
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;"> 
                                <span class="badge badge-default">{{__('Date Of Receiving Order')}}</span>
                                <input type="text" disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;" >
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" >
                            </div> 
                        </div>  

                        
                    
                        
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">{{__('Shipping Cost')}}</span>
                                <select class="form-control demo-select2" name="shipping_country" id="shipping_country" >
                                    <optgroup label="{{__('Districts')}}">
                                        @foreach($districts as $district)
                                            <option value={{$district->id}} @isset($receipt) @if($receipt->shipping_country_id  == $district->id) selected @endif @endisset>{{$district->name}} - {{single_price($district->cost)}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{__('Countries')}}">
                                        @foreach($countries as $country)
                                            <option value={{$country->id}} @isset($receipt) @if($receipt->shipping_country_id == $country->id) selected @endif @endisset>{{$country->name}} - {{single_price($country->cost)}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{__('Metro')}}">
                                        @foreach($metro as $raw)
                                            <option value={{$raw->id}} @isset($receipt) @if($receipt->shipping_country_id == $raw->id) selected @endif @endisset>{{$raw->name}} - {{single_price($raw->cost)}}</option>
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
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">{{__('Order Cost')}}</span>
                                <input type="number" min="0" step="0.1" class="form-control" required name="order_cost" id="order_cost" value="{{old('order_cost')}}">
                            </div>
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">{{__('Deposit')}}</span>
                                <input type="number" min="0" step="0.1" class="form-control" required name="deposit" id="deposit" value="{{old('deposit')}}">
                            </div>
                        </div> 

                        

                    </div>
                    <div class="col-md-3">  
                        <div class=" text-center" style="margin-top: 60px;"> 
                            <h3 class="text-center">{{__('Address')}}</h3></span><textarea  class="form-control" rows="7" name="address" >{{$receipt->address ?? ''}}</textarea> 
                        </div> 
                        <hr>
                        
                        <div class="form-group">  
                            <div id="photos">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">    
                        <h3 class="text-center">{{__('Description')}}</h3>
                        <textarea  class="editor" name="description" > </textarea> 
                    </div>
                    
                    <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
                </div>  
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

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
    });

</script>

@endsection
