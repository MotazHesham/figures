@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6 pull-right">
        <a href="{{ route('products.create')}}" class="btn btn-rounded btn-info btn-lg pull-right">{{__('Add New Product')}}</a>
    </div>
    <div class="col-lg-6 pull-left">
        <a data-toggle="modal" data-target="#update_stock" class="btn btn-rounded btn-lg btn-purple pull-left">{{__('Update Stock')}}</a>
    </div>
</div>

<br>

<div class="panel">
    <!--Panel heading-->
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{ __('Products') }}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_products" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 200px;">
                        <select class="form-control demo-select2" name="categories" id="type" onchange="sort_products()">
                            <option value="">Sort by category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" @isset($sort_categories) @if($sort_categories == $category->id) selected @endif @endisset>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 200px;">
                        <select class="form-control demo-select2" name="subcategories" id="type" onchange="sort_products()">
                            <option value="">Sort by subcategory</option>
                            @foreach($subcategories as $category)
                                <option value="{{$category->id}}" @isset($sort_subcategories) @if($sort_subcategories == $category->id) selected @endif @endisset>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 200px;">
                        <select class="form-control demo-select2" name="subsubcategories" id="type" onchange="sort_products()">
                            <option value="">Sort by subsubcategory</option>
                            @foreach($subsubcategories as $category)
                                <option value="{{$category->id}}" @isset($sort_subsubcategories) @if($sort_subsubcategories == $category->id) selected @endif @endisset>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 150px;">
                        <select class="form-control demo-select2" name="featured" id="type" onchange="sort_products()">
                            <option value="">Sort by Featured</option>
                            <option value="1"  @isset($featured) @if($featured == 1) selected @endif @endisset>Featured</option>
                            <option value="0"  @isset($featured) @if($featured == 0) selected @endif @endisset>Not Featured</option>
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 150px;">
                        <select class="form-control demo-select2" name="special" id="type" onchange="sort_products()">
                            <option value="">Sort by Special</option>
                            <option value="1"  @isset($special) @if($special == 1) selected @endif @endisset>Special</option>
                            <option value="0"  @isset($special) @if($special == 0) selected @endif @endisset>Not Special</option>
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{__('Product Name')}}">
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="panel-body">
        <div class="clearfix">
            <div class="pull-right">
                {{ $products->appends(request()->input())->links() }}
            </div>
        </div>
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="20%">{{__('Name')}}</th>
                    <th></th>
                    <th>{{__('Num of Sale')}}</th>
                    <th>{{__('Total Stock')}}</th>
                    <th>{{__('Base Price')}}</th>
                    <th>{{__('Todays Deal')}}</th>
                    <th>{{__('Rating')}}</th>
                    <th>{{__('Published')}}</th>
                    <th>{{__('Featured')}}</th>
                    <th>{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $key => $product)
                    <tr>
                        <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                        <td>
                            <a href="{{ route('product', $product->slug) }}" target="_blank" class="media-block">
                                <div class="media-left">
                                    <img loading="lazy"  class="img-md" src="{{ asset($product->thumbnail_img)}}" alt="Image">
                                </div>
                                <div class="media-body">{{ __($product->name) }}</div>
                                @if($product->added_by == 'designer')
                                    <br>
                                    {{ \App\Models\Listing::find($product->listing_id)->user->email ?? ''}}
                                @endif
                            </a>
                        </td>
                        <td>
                            @if($product->special == 1)
                                <span class="badge badge-dark">Special</span>
                            @else
                                <span class="badge badge-mint">Non Special</span>
                            @endif
                        </td>
                        <td>{{ $product->num_of_sale }} {{__('times')}}</td>
                        <td>
                            @php
                                $qty = 0;
                                if($product->variant_product){
                                    foreach ($product->stocks as $key => $stock) {
                                        $qty += $stock->qty;
                                    }
                                }
                                else{
                                    $qty = $product->current_stock;
                                }
                                echo $qty;
                            @endphp
                        </td>
                        <td>{{ number_format($product->unit_price,2) }}</td>
                        <td><label class="switch">
                                <input onchange="update_todays_deal(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->todays_deal == 1) echo "checked";?> >
                                <span class="slider round"></span></label></td>
                        <td>{{ $product->rating }}</td>
                        <td><label class="switch">
                                <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                                <span class="slider round"></span></label></td>
                        <td><label class="switch">
                                <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->featured == 1) echo "checked";?> >
                                <span class="slider round"></span></label></td>

                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('products.edit', encrypt($product->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i>{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('products.destroy', $product->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i>{{__('Delete')}}</a></li>
                                    <li><a href="{{route('products.duplicate', $product->id)}}"><i class="fa fa-clone" style="color: hsl(295, 41%, 45%)"></i>{{__('Duplicate')}}</a></li>
                                </ul>
                            </div>
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
    </div>
</div>

<!-- update stock modal -->
<div class="modal fade" id="update_stock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class=" text-center" id="exampleModalLabel">{{__('Update Stock')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('products.update_stock')}}" class="form-horizontal"  method="post">
                    @csrf
                    <div class="form-group" >
						<label class="col-lg-2 control-label">{{__('Category')}}</label>
						<div class="col-lg-7">
							<select class="form-control demo-select2-placeholder" name="category_id"  required>
								@foreach($categories as $category)
									<option value="{{$category->id}}">{{__($category->name)}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group" >
						<label class="col-lg-2 control-label">{{__('Sub Category')}}</label>
						<div class="col-lg-7">
							<select class="form-control demo-select2-placeholder" name="subcategory_id">
                                <option value="">{{__('Choose SubCategory')}}</option>
                                @foreach($subcategories as $category)
                                    <option value="{{$category->id}}">{{__($category->name)}}</option>
                                @endforeach
							</select>
						</div>
					</div>
					<div class="form-group" >
						<label class="col-lg-2 control-label">{{__('Sub SubCategory')}}</label>
						<div class="col-lg-7">
							<select class="form-control demo-select2-placeholder" name="subsubcategory_id" >
                                <option value="">{{__('Choose Sub SubCategory')}}</option>
                                @foreach($subsubcategories as $category)
                                    <option value="{{$category->id}}">{{__($category->name)}}</option>
                                @endforeach
							</select>
						</div>
					</div>
                    <div class="form-group" >
						<label class="col-lg-2 control-label">{{__('Quantity')}}</label>
						<div class="col-lg-4">
							<input class="form-control" type="number" value="1" name="quantity" step="1" required>
						</div>
					</div>
                    <div class="form-group" >
						<label class="col-lg-2 control-label">{{__('Unit')}}</label>
						<div class="col-lg-4">
							<input class="form-control" type="number" value="1" name="unit" step="1" required>
						</div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <label class="col-sm-2 control-label" for="description"></label>
                            <button style="padding: 8px 58px;font-size: 20px;" class="btn btn-purple btn-rounded btn-lg" type="submit">{{__('Update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Todays Deal updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Published products updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Featured products updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function sort_products(el){
            $('#sort_products').submit();
        }

    </script>
@endsection
