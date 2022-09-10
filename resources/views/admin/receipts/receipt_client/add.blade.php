@extends('layouts.app')

@section('content')

<div class="col-sm-12">
<div class="container">
    <div class="panel mb-5">
        <div class="panel-heading" style="padding:10px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.client')}}">
                    <i class="fa fa-chevron-circle-left"></i> 
                    back
                </a> 
                {{__('Add Client Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.client.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="row">
                    <div class="col-md-4">
                        <h3 class="text-center">&nbsp;</h3> 
                        <div class="form-group">  
                            <div class="col-sm-12 text-center"> 
                                <span class="badge badge-default">{{__('Date')}}</span>
                                <input type="text"  disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;">
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" >
                            </div>
                        </div>
                        <div class="form-group">  
                            <div class="col-sm-12 text-center">
                                <span class="badge badge-default">{{__('Client Name')}}</span>
                                <input type="text" class="form-control" name="client_name" id="client_name" value="{{$receipt->client_name ?? ''}}">
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-12 text-center">
                                <span class="badge badge-default">{{__('Phone Number')}}</span>
                                <input type="text" class="form-control" name="phone" id="phone" value="{{$receipt->phone ?? $phone}}">
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-12 text-center">
                                <span class="badge badge-default">{{__('Deposit')}}</span>
                                <input type="number" min="0" step="0.1" class="form-control" name="deposit" id="deposit" required value="0"> 
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-12 text-center">
                                <span class="badge badge-default">{{__('Discount')}}</span>
                                <input type="number" min="0" step="0.1" max="100" class="form-control" name="discount" id="discount" required value="0">
                            </div>
                        </div> 
                        <div class="form-group"> 
                            <div class="col-sm-12">
                                <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Save')}}</button> </div>
                            </div> 
                    </div>
                    <div class="col-md-8"> 
                        <h3 class="text-center">{{__('Note')}}</h3> 
                        <textarea  class="editor" name="note" ></textarea> 
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

