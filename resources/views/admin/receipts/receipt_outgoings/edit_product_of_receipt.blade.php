@extends('layouts.app')

@section('content')

<div class="col-sm-6">
    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px;margin-bottom:80px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.outgoings.edit',$receipt->receipt_outgoings_id)}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                <br>
                <span class="badge badge-grey">{{$receipt->receipt_outgoings->order_num}}</span> 
                {{__('Update Receipt')}}
            </h3>
        </div>
        
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.outgoings.update_product') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$receipt->id}}" id="">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="description">{{__('Description')}}</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" value="{{$receipt->description}}" name="description" id="description" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="cost">{{__('Cost')}}</label>
                    <div class="col-sm-5">
                        <input type="number"  min="0" step="0.1" class="form-control" value="{{$receipt->cost}}" name="cost" id="cost" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="quantity">{{__('Quantity')}}</label>
                    <div class="col-sm-5">
                        <input type="number"  min="0" step="1" class="form-control" value="{{$receipt->quantity}}" name="quantity" id="quantity" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="quantity"> </label>
                    <div class="col-sm-5"> 
                        <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Update')}}</button>
                    </div>
                </div>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection
