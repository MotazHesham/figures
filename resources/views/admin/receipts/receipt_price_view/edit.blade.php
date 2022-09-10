@extends('layouts.app')

@section('content')

<div class="col-sm-6">
    <div class="panel mb-5 text-center">
        <div class="panel-heading" style="padding:10px;margin-bottom:40px">
            <h3 class="text-center">
                <a class="badge badge-default" href="{{route('receipt.price_view')}}"><i class="fa fa-chevron-circle-left"></i> back</a>
                <br>
                <span class="badge badge-grey">{{$receipt->order_num}}</span>
                {{__('Update Receipt')}}
            </h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('receipt.price_view.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$receipt->id}}" id="">
            <div class="panel-body">

                @include('admin.partials.error_message')

                <div class="form-group">
                    <div class="col-sm-12">
                        <span class="badge badge-default">{{ __('Client Name') }}</span>
                        <input type="text" class="form-control" name="client_name" id="client_name"
                            value="{{ old('client_name', $receipt->client_name) }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <span class="badge badge-default">{{ __('Phone Number') }}</span>
                        <input type="text" class="form-control" name="phone" id="phone"
                            value="{{ old('phone', $receipt->phone) }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <span class="badge badge-default">مدة الأرتباط</span>
                        <input type="text" class="form-control" name="relate_duration" id="relate_duration"
                            value="{{ old('relate_duration', $receipt->relate_duration) }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <span class="badge badge-default">مدة التوريد</span>
                        <input type="text" class="form-control" name="supply_duration" id="supply_duration"
                            value="{{ old('supply_duration', $receipt->supply_duration) }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <span class="badge badge-default">الدفع</span>
                        <input type="number" class="form-control" name="payment" id="payment"
                            value="{{ old('payment', $receipt->payment) }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <span class="badge badge-default">مكان التسليم</span>
                        <input type="text" class="form-control" name="place" id="place"
                            value="{{ old('place', $receipt->place) }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center" style="margin-bottom: 10px;">
                        <span class="badge badge-default">قيمة (14%)</span>
                        <select class="form-control" name="added_value" id="added_value">
                            <option value="1" @if ($receipt->added_value == 1) selected @endif>شامل</option>
                            <option value="0" @if ($receipt->added_value == 0) selected @endif>غير شامل</option>
                        </select>
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
                                <a class="btn btn-info btn-rounded" href="{{route('receipt.price_view.edit_product', $product->id)}}">{{__('Edit')}}</a>
                                <a class="btn btn-danger btn-rounded" onclick="confirm_modal('{{route('receipt.price_view.product.destroy', $product->id)}}');">{{__('Delete')}}</a>
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
                @php
                    if($receipt->added_value == 1){
                        $extra_value = ($receipt->total * 14) / 100;
                    }else{
                        $extra_value = 0;
                    }
                @endphp
                @if($receipt->added_value == 1)
                    <span class="badge badge-info">+ {{ single_price($receipt->total) }} الأجمالي  </span> <br>
                    <span class="badge badge-mint">+ {{ single_price($extra_value) }} (قيمة %14)  </span> <br>
                @endif
                <span class="badge badge-success">= {{ single_price($receipt->total + $extra_value) }} {{__('Total')}}  </span>
            </div>
        </div>
    </div>

    <div class="panel mb-5">
        <div class="panel-heading" style="padding: 10px">
            <h3 class="text-center">{{__('Add Product To Receipt')}}</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" action="{{ route('receipt.price_view.store_product') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="hidden" class="form-control" name="receipt_price_view_id" id= "receipt_price_view_id" value="{{$receipt->id}}">
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
