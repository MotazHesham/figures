@extends('layouts.app')

@section('content')

<div class="col-sm-6">
    <div class="panel mb-5 text-center">
        <div class="panel-heading" style="padding:10px;margin-bottom:40px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.outgoings')}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                <br>
                <span class="badge badge-grey">{{$receipt->order_num}}</span> 
                {{__('Update Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.outgoings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$receipt->id}}" id="">
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="form-group"> 
                    <label class="col-sm-2 control-label" for="date"> </label>
                    <div class="col-sm-12">
                        <span class="badge badge-default">{{__('Date')}}</span>
                        <input type="text" value="{{format_Date($receipt->date_of_receiving_order)}}" disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;">
                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" >
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-12">
                        <span class="badge badge-default">{{__('Client Name')}}</span>
                        <input type="text" class="form-control" value="{{$receipt->client_name}}" name="client_name" id="client_name" >
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-12">
                        <span class="badge badge-default">{{__('Phone Number')}}</span>
                        <input type="text" class="form-control" value="{{$receipt->phone}}" name="phone" id="phone" >
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-12">
                        <h3 class="text-center">{{__('Note')}}</h3>
                        <textarea  class="editor" name="note" ><?php echo $receipt->note;?></textarea>
                    </div>
                </div>
                <div class="form-group">  
                    <div class="col-sm-12">
                        <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-block" type="submit">{{__('Update')}}</button> 
                    </div>
                </div>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>


<div class="col-sm-6">
    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h3 class="text-center">{{__('Products Of Receipt')}}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>{{__('#')}}</th>
                        <th>{{__('Description')}}</th>
                        <th>{{__('Cost')}}</th>
                        <th>{{__('Quantity')}}</th>
                        <th>{{__('Total')}}</th>
                        <th>{{__('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                        <tr>
                            <td>{{ $product ->id }}</td>
                            <td>{{ $product ->description }}</td>
                            <td>{{ single_price($product ->cost) }}</td>
                            <td>{{ $product ->quantity }}</td>
                            <td>{{ single_price($product ->total) }}</td>
                            <td>
                                <a class="btn btn-info btn-rounded" href="{{route('receipt.outgoings.edit_product', $product->id)}}">{{__('Edit')}}</a>
                                <a class="btn btn-danger btn-rounded" onclick="confirm_modal('{{route('receipt.outgoings.product.destroy', $product->id)}}');">{{__('Delete')}}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $products->appends(request()->input())->links() }} 
                </div>
            </div>
            <div class="text-center">
                <span class="badge badge-success">= {{ single_price($receipt->total) }} {{__('Total')}}  </span>
            </div>
        </div>
    </div>

    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h3 class="text-center">{{__('Add Product To Receipt')}}</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="{{ route('receipt.outgoings.store_product') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="hidden" class="form-control" name="receipt_outgoings_id" id= "receipt_outgoings_id" value="{{$receipt->id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="description">{{__('Description')}}</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="description" id="descrption" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="quantity">{{__('Quantity')}}</label>
                        <div class="col-sm-6">
                            <input type="number" min="0" step="1" class="form-control" name="quantity" id="quantity" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="cost">{{__('Cost')}}</label>
                        <div class="col-sm-6">
                            <input type="number" min="0" step="0.1" class="form-control" name="cost" id="cost" required>
                        </div>
                    </div>
                    
                    <div class="form-group"> 
                        <label class="col-sm-3 control-label" for="description"></label>
                        <div class="col-sm-6">
                            <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-success btn-lg btn-rounded btn-block" type="submit">{{__('Add')}}</button>
                        </div> 
                    </div> 
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
