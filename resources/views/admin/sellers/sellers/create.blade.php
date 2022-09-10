@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Seller Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('sellers.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="seller_type">{{__('Type')}}</label>
                    <div class="col-sm-9"> 
                        <select class="form-control" name="seller_type" id="seller_type" onchange="type_seller(this)">
                            <option value="seller">Seller</option>
                            <option value="social">Social</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: none" id="discount_div">
                    <label class="col-sm-3 control-label" for="discount_code">{{__('Discount Code')}}</label>
                    <div class="col-sm-3">
                        <input type="text"  id="discount_code" value="{{old('discount_code')}}" name="discount_code" class="form-control">
                    </div>
                    <label class="col-sm-3 control-label" for="discount">{{__('Discount')}}</label>
                    <div class="col-sm-3">
                        <input type="number" min="0" id="discount" value="{{old('discount')}}" name="discount" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="social_name">{{__('Social Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text"  id="social_name" value="{{old('social_name')}}" name="social_name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="social_link">{{__('Social Link')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="social_link" value="{{old('social_link')}}" name="social_link" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="email">{{__('Email Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text"  id="email" value="{{old('email')}}" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="password">{{__('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="password_confirmation">{{__('Confirm Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="phone">{{__('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="phone" value="{{old('phone')}}" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="address">{{__('Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="address" value="{{old('address')}}" name="address" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="qualification">{{__('Qualification')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="qualification" value="{{old('qualification')}}" name="qualification" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="identity_back">{{__('Identity Back')}}</label>
                    <div class="col-sm-9">
                        <input type="file" id="identity_back" name="identity_back" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="identity_front">{{__('Identity Front')}}</label>
                    <div class="col-sm-9">
                        <input type="file" id="identity_front" name="identity_front" class="form-control">
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
        function type_seller(elem){
            var seller_type = $('#seller_type').val();
            if(seller_type == 'seller'){
                $('#discount_div').css('display','none');
            }else{
                $('#discount_div').css('display','block');
            }
        }
    </script>
@endsection