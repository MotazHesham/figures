@extends('layouts.app')

@section('content')

<div class="container">
<div class="col-sm-8">
    <div class="panel mb-5 text-center">
        <div class="panel-heading" style="padding: 10px;margin-bottom:40px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.outgoings')}}">
                    <i class="fa fa-chevron-circle-left"></i> 
                    back
                </a> 
                {{__('Add Outgoing Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.outgoings.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="row ">
                <div class="panel-body">

                    @include('admin.partials.error_message')
                    
                    <div class="form-group"> 
                        <div class="col-sm-12"> 
                            <span class="badge badge-default">{{__('Date')}}</span>
                            <input type="text"  disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;">
                            <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" >
                        </div>
                    </div>
                    <div class="form-group"> 
                        <div class="col-sm-12">
                            <span class="badge badge-default">{{__('Client Name')}}</span>
                            <input type="text" class="form-control" name="client_name" id="client_name" value="{{old('client_name')}}">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <div class="col-sm-12">
                            <span class="badge badge-default">{{__('Phone Number')}}</span>
                            <input type="text" class="form-control" name="phone" id="phone" value="{{old('phone')}}">
                        </div>
                    </div>
                    <div class="form-group"> 
                        <div class="col-sm-12">
                            <h3 class="text-center">{{__('Note')}}</h3>
                            <textarea  class="editor" name="note" >{{old('note')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12"> 
                            <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>
</div>

@endsection

