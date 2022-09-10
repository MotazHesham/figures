@extends('layouts.app')

@section('content')

<div style="padding:20px">
    @if($type == 'social' || $type == 'figures')
        <h1 class="text-center">{{__('Products Of '.ucfirst($type).' Receipt')}}</h1>
    @else
        <h1 class="text-center">{{__('Products Of Clients Receipt')}}</h1>
    @endif
    <div class="row">
        <div class="col-md-8">
            <div class="panel">
                <div class="panel-body">
                    <form action="" method="get" style="padding: 15px">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" placeholder="أسم المنتج أو السعر" @isset($name) value="{{$name}}" @endisset name="name" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-success btn-rounded btn-block" value="{{__('Search')}}">
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{__('#')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Price')}}</th>
                                @if($type == 'social' || $type == 'figures')
                                    <th>{{__('Commission')}}</th>
                                    <th>{{__('Photos')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $key => $product)
                            <form action="{{route('receipt.product.update')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$product->id}}">
                                <tr>
                                    <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                                    <td><input type="text" class="form-control" name="name" value="{{ $product ->name }}"></td>
                                    <td><input type="number" class="form-control" min="0" step="0.1" name="price" value="{{ $product ->price }}"></td>
                                    @if($type == 'social' || $type == 'figures')
                                        <td><input type="number" class="form-control" min="0" step="0.1" name="commission" value="{{ $product ->commission }}"></td>
                                        <td>
                                            <a class="btn btn-dark btn-rounded" href="{{route('receipt.product.edit_photos', $product->id)}}">تعديل الصور</a>
                                            <br>
                                            <div>
                                                @if(is_array(json_decode($product->photos)) && count(json_decode($product->photos)) > 0)
                                                    <a href="{{ asset(json_decode($product->photos)[0]) }}">
                                                        <img src="{{ asset(json_decode($product->photos)[0]) }}" alt="" class="img-responsive" width="80" height="80">
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <input type="submit" value="{{__('Edit')}}" class="btn btn-info btn-rounded">
                                        <a class="btn btn-danger btn-rounded" onclick="confirm_modal('{{route('receipt.product.destroy', $product->id)}}');">{{__('Delete')}}</a>
                                    </td>
                                </tr>
                            </form>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="pull-right">
                            {{ $products->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body">
                    <h3 class="text-center" style="margin-bottom:30px">{{__('Add Product')}}</h3>
                    <form id="add_receipt_product"  action="{{route('receipt.product.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" required placeholder="{{__('Product Name')}}" id="add_receipt_product_name">
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" min="0.1" required step="0.1" class="form-contol" name="price" placeholder="{{__('Cost')}}" id="add_receipt_product_price">
                        </div>
                        @if($type == 'client')
                            <input type="hidden"  name="commission" id="">
                        @else
                            <div class="form-group">
                                <input type="number" class="form-control" min="0.1" required step="0.1" class="form-contol" name="commission" placeholder="{{__('Commission')}}" id="add_receipt_product_price">
                            </div>
                            <div class="form-group">
                                <div id="photos">

                                </div>
                            </div>
                        @endif
                        <input type="hidden" value="{{$type}}" name="type" id="">
                        <div class="form-group">
                            <input type="submit" class="btn btn-pink btn-rounded btn-lg btn-block" value="{{__('Save')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>


@endsection



@section('script')

<script type="text/javascript">

    $(document).ready(function(){
        $("#photos").spartanMultiImagePicker({
            fieldName:        'photos[]',
            maxCount:         10,
            rowHeight:        '200px',
            groupClassName:   'col-md-4 col-sm-4 col-xs-6',
            maxFileSize:      '',
            dropFileLabel : "Drop Here",
            onExtensionErr : function(index, file){
                console.log(index, file,  'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr : function(index, file){
                console.log(index, file,  'file size too big');
                alert('File size too big');
            }
        });
    });

</script>

@endsection
