@extends('layouts.app')

@section('content')

<div class="col-sm-12">
<div class="container">
    <div class="panel mb-5">
        <div class="panel-heading" style="padding:10px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.social',['confirm' => 'all' , 'receipt_type' => $receipt_type])}}">
                    <i class="fa fa-chevron-circle-left"></i>
                    back
                </a>
                {{__('Add Social Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.social.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                <input type="hidden" value="{{$receipt_type}}" name="receipt_type">
                <div class="row">
                    <div class="col-md-6" style="margin-top: 80px;">
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
                                            <option value={{$district->id}} @isset($receipt) @if($receipt->shipping_country_id  == $district->id) selected @endif @endisset>{{$district->name}} -   EGP{{($district->cost)}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{__('Countries')}}">
                                        @foreach($countries as $country)
                                            <option value={{$country->id}} @isset($receipt) @if($receipt->shipping_country_id == $country->id) selected @endif @endisset>{{$country->name}} -   EGP{{($country->cost)}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="{{__('Metro')}}">
                                        @foreach($metro as $raw)
                                            <option value={{$raw->id}} @isset($receipt) @if($receipt->shipping_country_id == $raw->id) selected @endif @endisset>{{$raw->name}} -   EGP{{($raw->cost)}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-sm-6 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">{{__('Deposit')}}</span>
                                <input type="number" min="0" step="0.1" class="form-control" required name="deposit" id="deposit" value="{{old('deposit')}}">
                            </div>
                            <div class="col-sm-12 text-center" style="margin-bottom: 10px;">
                                <span class="badge badge-default">سوشيال</span>
                                <select class="form-control" name="socials[]" id="socials[]" multiple>
                                    @php
                                        if($receipt){
                                            $selected_socials = $receipt->socials ? $receipt->socials()->get()->pluck('id')->toArray() : [];
                                        }else{
                                            $selected_socials = [];
                                        }
                                    @endphp
                                    @foreach($socials as $raw)
                                        <option value={{$raw->id}} @isset($receipt) @if(in_array($raw->id,$selected_socials)) selected @endif @endisset>{{$raw->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                    </div>
                    <div class="col-md-6">
                        <div class=" text-center" style="margin-top: 60px;">
                            <h3 class="text-center">{{__('Address')}}</h3></span><textarea  class="form-control" rows="7" name="address" >{{$receipt->address ?? ''}}</textarea>
                        </div>
                        <h3 class="text-center">{{__('Note')}}</h3>
                        <textarea rows="4"  class="form-control" name="note" ><?php echo $receipt->note ?? '' ?></textarea>
                    </div>
                </div>
                <button style="padding: 8px 58px;font-size: 20px;margin-top:15px" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>
</div>

@endsection

