@extends('layouts.app')

@section('content')

<div class="col-sm-6">
    <div class="panel mb-5 text-center">
        <div class="panel-heading" style="padding:10px;margin-bottom:60px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.client')}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                <br>
                <span class="badge badge-grey">{{$receipt->order_num}}</span> 
                {{__('Update Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.client.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$receipt->id}}" id="">
            <div class="panel-body">

                @include('admin.partials.error_message')
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="date"> </label>
                    <div class="col-sm-8 ">
                        <span class="badge badge-default">{{__('Date')}}</span>
                        <input type="text" value="{{format_Date($receipt->date_of_receiving_order)}}" disabled id="date_of_receiving_order_text" class="form-control" style="position: relative;">
                        <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="date_of_receiving_order" id="date_of_receiving_order" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="client_name"></label>
                    <div class="col-sm-8 ">
                        <span class="badge badge-default">{{__('Client Name')}}</span>
                        <input type="text" class="form-control" value="{{$receipt->client_name}}" name="client_name" id="client_name" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="phone"></label>
                    <div class="col-sm-8">
                        <span class="badge badge-default">{{__('Phone Number')}}</span>
                        <input type="text" class="form-control" value="{{$receipt->phone}}" name="phone" id="phone" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="phone"></label>
                    <div class="col-sm-8">
                        <span class="badge badge-default">{{__('Deposit')}}</span>
                        <input type="number" min="0" step="0.1" class="form-control" value="{{$receipt->deposit}}" name="deposit" id="deposit" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="phone"></label>
                    <div class="col-sm-8">
                        <span class="badge badge-default">{{__('Discount')}}</span>
                        <input type="number" min="0" step="0.1" max="100" class="form-control" value="{{$receipt->discount}}" name="discount" id="discount" required>
                    </div>
                </div>
                <div class="form-group "> 
                    <h3 >{{__('Note')}}</h3>
                    <div class="col-sm-12">
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
                                <a class="btn btn-info btn-rounded" href="{{route('receipt.client.edit_product', $product->id)}}">{{__('Edit')}}</a>
                                <a class="btn btn-danger btn-rounded" onclick="confirm_modal('{{route('receipt.client.product.destroy', $product->id)}}');">{{__('Delete')}}</a>
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
            @php 
                $discount = round( ( ($receipt->total/100) * $receipt->discount ) , 2); 
            @endphp
            <div class="text-center"> 
                <span class="badge badge-default">{{ single_price($receipt->deposit) }}{{__('Deposit')}}</span> 
                <span class="badge badge-default">{{ $receipt->discount }}% {{__('Discount')}}</span> 
                <span class="badge badge-default">{{ single_price($receipt->total) }} {{__('Total')}}</span> 
                <span class="badge badge-success">= {{ single_price($receipt->total - $discount - $receipt->deposit) }}</span>
            </div> 
        </div>
    </div>

    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h3 class="text-center">{{__('Add Product To Receipt')}}</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="{{ route('receipt.client.store_product') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-5">
                            <input type="hidden" class="form-control" name="receipt_id" id= "receipt_id" value="{{$receipt->id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="product">{{__('Product')}}</label>
                        <div class="col-sm-8">
                            <select class="form-control demo-select2" data-size="7" data-live-search="true" data-title="Select Product..." data-width="100%" name="description" id="receipt_client_description" required>
								<option value="">{{ ('Select Product') }}</option>
                                @foreach(\App\Models\ReceiptProduct::where('type','client')->get() as $product)
                                    <option value="{{$product->name}}" data-price="{{$product->price}}">{{ $product->name }} - {{single_price($product->price)}}</option>
                                @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="quantity">{{__('Quantity')}}</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" step="1" class="form-control" name="quantity" id="quantity" required>
                        </div> 
                        
                        <input type="hidden" name="cost" id="receipt_client_price">
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for=" "> </label>
                        <div class="col-sm-5"> 
                            <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-success btn-lg btn-rounded" type="submit">{{__('Add')}}</button>
                        </div>
                        <div class="col-sm-5"> 
                            <a style="padding: 8px 58px;font-size: 20px;" class="btn btn-info btn-lg btn-rounded" target="_blanc" href="{{route('receipt.client.print', $receipt->id)}}">{{__('Print')}}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">

        $(document).ready(function(){
            $('#receipt_client_description').on('change',function(){
                let price = $(this).find(':selected').data('price');
                $('#receipt_client_price').val(price); 
            })
        });
</script>
@endsection

