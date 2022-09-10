@extends('layouts.app')

@section('content')

<div class="col-sm-12">
    <div class="panel mb-5">
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.client.update_product') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$receipt->id}}" id="">
            <div class="panel-body"> 
                <h3 class="text-center">
                    <a class="badge badge-default" href="{{route('receipt.client.edit',$receipt->receipt_client_id)}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                    <br>
                    <span class="badge badge-grey">{{$receipt->receipt_client->order_num}}</span> 
                    {{__('Update Receipt')}}
                </h3>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="product">{{__('Product')}}</label>
                    <div class="col-sm-5">
                        <select class="form-control demo-select2" data-size="7" data-live-search="true" data-title="Select Product..." data-width="100%" name="description" id="receipt_client_description" required>
                            <option value="">{{ ('Select Product') }}</option>
                            @foreach(\App\Models\ReceiptProduct::where('type','client')->get() as $product)
                                <option value="{{$product->name}}" @if($product->name == $receipt->description) selected @endif data-price="{{$product->price}}">{{ $product->name }} - {{single_price($product->price)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <p style="color: black;padding:8px" id="receipt_client_price2"></p>
                </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="quantity">{{__('Quantity')}}</label>
                    <div class="col-sm-5">
                        <input type="number"  min="0" step="1" class="form-control" value="{{$receipt->quantity}}" name="quantity" id="quantity" required>
                    </div>
                    
                    <input type="hidden" name="cost" id="receipt_client_price" value="{{$receipt->cost}}">
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
						<label class="col-sm-2 control-label" for="description"></label>
                        <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple" type="submit">{{__('Update')}}</button>
                    </div>
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
            $('#receipt_client_description').on('change',function(){
                let price = $(this).find(':selected').data('price');
                $('#receipt_client_price').val(price); 
            })
        });
</script>
@endsection
