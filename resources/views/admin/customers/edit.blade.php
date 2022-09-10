@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Customer Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
            @csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" id="name" name="name" class="form-control" value="{{$customer->name}}" >
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="email">{{__('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" id="email" name="email" class="form-control" value="{{$customer->email}}" >
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="password">{{__('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" id="password" name="password" class="form-control">
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
                        <input type="text" value="{{$customer->phone}}" id="phone" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="address">{{__('Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text"  value="{{$customer->address}}" id="address" name="address" class="form-control" required>
                    </div>
                </div> 
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
