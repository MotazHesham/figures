@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Seller Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('sellers.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="name" name="name" class="form-control" value="{{$user->name}}" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="seller_type">{{__('Type')}}</label>
                    <div class="col-sm-9"> 
                        <select class="form-control" name="seller_type" id="seller_type" onchange="type_seller()">
                            <option value="seller" @isset($user->seller) @if($user->seller->seller_type == 'seller') selected @endif @endisset>Seller</option>
                            <option value="social" @isset($user->seller) @if($user->seller->seller_type == 'social') selected @endif @endisset>Social</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" @if($user->seller && $user->seller->seller_type == 'seller') style="display: none" @endif id="discount_div">
                    <label class="col-sm-3 control-label" for="discount_code">{{__('Discount Code')}}</label>
                    <div class="col-sm-3">
                        <input type="text"  id="discount_code"  name="discount_code" class="form-control" value="{{$user->seller ? $user->seller->discount_code : ""}}">
                    </div>
                    <label class="col-sm-3 control-label" for="discount">{{__('Discount')}}</label>
                    <div class="col-sm-3">
                        <input type="number" min="0" id="discount"  name="discount" class="form-control" value="{{$user->seller ? $user->seller->discount : ""}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="email">{{__('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="email" name="email" class="form-control" value="{{$user->email}}" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="social_name">{{__('Social Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="social_name" name="social_name" class="form-control" value="{{$user->seller ? $user->seller->social_name : ""}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="social_link">{{__('Social Link')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="social_link" name="social_link" class="form-control" value="{{$user->seller ? $user->seller->social_link : ""}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="order_out_website">{{__('Orders out of website')}}</label>
                    <div class="col-sm-9">
                        <input type="number" required min="0" step="1" id="order_out_website" name="order_out_website" class="form-control" value="{{$user->seller ? $user->seller->order_out_website : ""}}" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="password">{{__('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password"  id="password" name="password" class="form-control">
                    </div>
                </div> 
                <div class="form-group"> 
                    <label class="col-sm-3 control-label" for="password_confirmation">{{__('Confirm Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="phone">{{__('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" value="{{$user->phone}}" id="phone" name="phone" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="address">{{__('Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text"  value="{{$user->address}}" id="address" name="address" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="qualification">{{__('Qualification')}}</label>
                    <div class="col-sm-9">
                        <input type="text"  value="{{$user->seller ?$user->seller->qualification : ""}}" id="qualification" name="qualification" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="identity_back">{{__('Identity Back')}}</label>
                    <div class="col-sm-9">
                        <input type="file" value="{{$user->seller ? $user->seller->identity_back : ""}}" id="identity_back" name="identity_back" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="identity_front">{{__('Identity Front')}}</label>
                    <div class="col-sm-9">
                        <input type="file" value="{{$user->seller ? $user->seller->identity_front : ""}}" id="identity_front" name="identity_front" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
                </div>
            </div> 
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection

@section('script')
    @parent
    <script>
        function type_seller(){
            var seller_type = $('#seller_type').val();
            if(seller_type == 'seller'){
                $('#discount_div').css('display','none');
            }else{
                $('#discount_div').css('display','block');
            }
        }
    </script>
@endsection