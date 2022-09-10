@extends('frontend.layouts.app')

@section('styles')
<style>
    a:hover,a:focus{
        text-decoration: none;
        outline: none;
    }
    #accordion2 .panel{
        border: none;
        box-shadow: none;
        border-radius: 0;
        margin: 0 0 15px 10px;
    }
    #accordion2 .panel-heading{
        padding: 0;
        border-radius: 30px;
    }
    #accordion2 .panel-title a{
        display: block;
        padding: 12px 20px 12px 50px;
        background: #1ABC9C;
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        border: 1px solid transparent;
        border-radius: 30px;
        position: relative;
        transition: all 0.3s ease 0s;
    }
    #accordion2 .panel-title a.collapsed{
        background: #fff;
        color: #0d345d;
        border: 1px solid #ddd;
    }
    #accordion2 .panel-title a:after,
    #accordion2 .panel-title a.collapsed:after{
        content: "-";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        width: 55px;
        height: 55px;
        line-height: 55px;
        border-radius: 50%;
        background: #1ABC9C;
        font-size: 25px;
        color: #fff;
        text-align: center;
        border: 1px solid transparent;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.58);
        position: absolute;
        top: -5px;
        left: -20px;
        transition: all 0.3s ease 0s;
    }
    #accordion2 .panel-title a.collapsed:after{
        content: "+";
        background: #fff;
        color: #0d345d;
        border: 1px solid #ddd;
        box-shadow: none;
    }
    #accordion2 .panel-body{
        padding: 20px 25px 10px 9px;
        background: transparent;
        font-size: 14px;
        color: #8c8c8c;
        line-height: 25px;
        border-top: none;
        position: relative;
    }
    #accordion2 .panel-body p{
        padding-left: 25px;
        border-left: 1px dashed #8c8c8c;
    }

    .badge-default {
        background-color: #e9eeef;
        color: #7a878e;
    }
</style>
@endsection

@section('content')


    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @elseif(Auth::user()->user_type == 'designer')
                        @include('frontend.inc.designer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Orders')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('user.orders.index') }}">{{__('Orders')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <form class="mt-3" id="sort_orders" action="" method="GET">

                            <div class="row justify-content-md-center">
                                <div class="col-md-3 text-center">
                                    <span class="badge badge-default">{{__('Order Code')}}</span>
                                    <input type="text" class="form-control" id="code" name="code" @isset($code) value="{{ $code }}" @endisset >
                                </div>

                                <div class="col-md-3 text-center">
                                    <span class="badge badge-default">حالة الطلب</span>
                                    <select class="form-control demo-select2" name="delivery_status" id="delivery_status" onchange="sort_orders()">
                                        <option value="">{{__('Delivery Status')}}</option>
                                        <option value="pending"   @isset($delivery_status) @if($delivery_status == 'pending') selected @endif @endisset>{{__('Pending')}}</option>
                                        <option value="on_review"   @isset($delivery_status) @if($delivery_status == 'on_review') selected @endif @endisset>{{__('On review')}}</option>
                                        <option value="on_delivery"   @isset($delivery_status) @if($delivery_status == 'on_delivery') selected @endif @endisset>{{__('On delivery')}}</option>
                                        <option value="delivered"   @isset($delivery_status) @if($delivery_status == 'delivered') selected @endif @endisset>{{__('Delivered')}}</option>
                                        <option value="delay"   @isset($delivery_status) @if($delivery_status == 'delay') selected @endif @endisset>{{__('Delay')}}</option>
                                        <option value="cancel"   @isset($delivery_status) @if($delivery_status == 'cancel') selected @endif @endisset>{{__('Cancel')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-md-center">

                                {{-- start date --}}
                                <div class="col-md-3 text-center" style="margin-bottom:10px">
                                    <span class="badge badge-default">من تاريخ الأضافة</span>
                                    <input type="text" @isset($from) value="{{format_date($from)}}" @endisset  disabled id="from_date_text" class="form-control @isset($from) isset @endisset" style="position: relative;">
                                    <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="from" id="from_date" >
                                </div>


                                {{-- end date --}}
                                <div class="col-md-3 text-center" style="margin-bottom:10px">
                                    <span class="badge badge-default">الي تاريخ الأضافة</span>
                                    <input type="text" @isset($to) value="{{format_date($to)}}" @endisset  disabled id="to_date_text" class="form-control @isset($to) isset @endisset" style="position: relative;">
                                    <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="to" id="to_date" >
                                </div>

                                <div class="col-md-2 text-center">
                                    <div>&nbsp;</div>
                                    <button type="submit" name="search" class="btn btn-success">{{__('Search')}}</button>
                                    <button type="submit" name="download" class="btn btn-info">{{__('Download')}}</button>
                                </div>
                            </div>
                        </form>

                        <!-- Order history table -->
                        <div style="background: #fff">
                            <div class="pagination-wrapper py-4">
                                <div class="row justify-content-md-center">

                                    <div class="col col-md-6">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">

                                        @if (count($orders) > 0)
                                            @foreach ($orders as $key => $order)
                                            <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingOne{{$order->id}}">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne{{$order->id}}" aria-expanded="false" aria-controls="collapseOne{{$order->id}}">
                                                                <div class="row">
                                                                    <div class=" col-md-4">{{$order->code}}</div>
                                                                    <div class=" col-md-4">{{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</div>
                                                                    <div class=" col-md-4">{{ format_date_time(strtotime($order->created_at)) }}</div>
                                                                </div>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseOne{{$order->id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne{{$order->id}}">
                                                        <div class="panel-body">

                                                            <div style="box-shadow: 2px 2px 10px 0px rgb(124, 201, 162);padding-bottom:20px">
                                                                <div class="pt-4">
                                                                    @if($order->delivery_status != 'cancel')
                                                                    <ul class="process-steps clearfix">
                                                                        <li @if($order->delivery_status == 'pending') class="active" @else class="done" @endif>
                                                                            <div class="icon">1</div>
                                                                            <div class="title">{{__('Order placed')}}</div>
                                                                        </li>
                                                                        <li @if($order->delivery_status == 'on_review') class="active" @elseif($order->delivery_status == 'on_delivery' || $order->delivery_status == 'delivered') class="done" @endif>
                                                                            <div class="icon">2</div>
                                                                            <div class="title">{{__('On review')}}</div>
                                                                        </li>
                                                                        <li @if($order->delivery_status == 'on_delivery') class="active" @elseif($order->delivery_status == 'delivered') class="done" @endif>
                                                                            <div class="icon">3</div>
                                                                            <div class="title">{{__('On delivery')}}</div>
                                                                        </li>
                                                                        <li @if($order->delivery_status == 'delivered') class="done" @endif>
                                                                            <div class="icon">4</div>
                                                                            <div class="title">{{__('Delivered')}}</div>
                                                                        </li>
                                                                    </ul>
                                                                    @else
                                                                    <ul class="process-steps clearfix">
                                                                        <li @if($order->delivery_status == 'cancel') class="active" @else class="done" @endif>
                                                                            <div class="icon" style="background:brown"></div>
                                                                            <div class="title" style="color:black">{{__('Order Canceld')}}</div>
                                                                            <span>Cancel Reason : {{$order->cancel_reason}}</span>
                                                                        </li>
                                                                    </ul>
                                                                    @endif
                                                                </div>

                                                                <div class="card mt-4">
                                                                    <div class="card-body pb-0">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div>
                                                                                    @if($order->delivery_status == 'pending')
                                                                                        <a class="btn btn-info" href="{{route('user.orders.edit',$order->id)}}" title="{{__('Edit Order')}}"><i class="fa fa-edit"></i></a>
                                                                                        <a class="btn btn-danger c-white" style="cursor: pointer" onclick="confirm_modal('{{route('user.orders.destroy',$order->id)}}');" title="{{__('Delete Order')}}"><i class="fa fa-trash"></i></a>
                                                                                    @endif

                                                                                    <a class="btn btn-success" target="_blanc" href="{{ route('user.orders.print', $order->id) }}" title="{{__('Print')}}"><i class="fa fa-print"></i></a>
                                                                                </div>
                                                                                <div>
                                                                                    <span class="badge badge-default">{{__('Phone Number') }}</span> {{ $order->phone_number }} , {{ $order->phone_number2 }}
                                                                                </div>
                                                                                <div>
                                                                                    <span class="badge badge-default">{{__('Address') }}</span>{{ $order->shipping_country_name}} , {{ $order->shipping_address }}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="card-header py-2 px-3 heading-6 strong-600" style="display: flex; flex-direction: row; justify-content: space-between;">
                                                                                    <div>
                                                                                        {{__('Total')}}
                                                                                    </div>
                                                                                    <div>
                                                                                        @if($order->discount)
                                                                                            <span class="badge badge-success">{{__('Discount')}} {{ single_price($order->discount ) }} </span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div>
                                                                                        @if($order->discount_code)
                                                                                            <span class="badge badge-info">كود الخصم {{ $order->discount_code }} </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <table class="table details-table">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <th>{{__('Subtotal')}}</th>
                                                                                            <td class="text-right">
                                                                                                <span class="strong-600">+{{ single_price($order->required_to_pay ) }}</span>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>{{__('Shipping')}}</th>
                                                                                            <td class="text-right">
                                                                                                <span class="text-italic">+{{ single_price($order->shipping_country_cost) }}</span>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <th>
                                                                                                <span class="strong-600">مجموع بعد الخصم</span>
                                                                                            </th>
                                                                                            <td class="text-right">
                                                                                                <strong><span>={{ single_price($order->required_to_pay + $order->extra_commission + $order->shipping_country_cost) }}</span></strong>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @foreach($order->orderDetails as $orderDetail)
                                                                    <div class="container mb-3">
                                                                        <div class="row" style="margin: 4%; box-shadow: 1px 1px 6px #678996;border-radius:27px; padding: 7px;">
                                                                            <div class="col-md-3">
                                                                                <img  src="@if ($orderDetail->product != null) {{asset($orderDetail->chosen_photo ?? $orderDetail->product->thumbnail_img)}} @endif" width="80" height="80" />
                                                                            </div>
                                                                            <div class="col-md-9">
                                                                                <div class="row">
                                                                                    <div class="col my-auto">
                                                                                        <h6 class="mb-0">
                                                                                            @if ($orderDetail->product != null)
                                                                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                                                                    {{ $orderDetail->product->name }}
                                                                                                    @if ($orderDetail->variation != null)
                                                                                                        ({{$orderDetail->variation}})
                                                                                                    @endif
                                                                                                </a>
                                                                                            @else
                                                                                                <strong>{{ __('Product Unavailable') }}</strong>
                                                                                            @endif
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col my-auto"> <h6>{{__('Qty')}} : {{ $orderDetail->quantity }}</h6></div>
                                                                                    <div class="col my-auto">
                                                                                        <h6 class="mb-0">{{single_price($orderDetail->total_cost)}}</h6>
                                                                                    </div>
                                                                                    <div class="col my-auto">
                                                                                        @if($order->delivery_status == 'pending')
                                                                                            <a class="btn btn-light quick-view" title="Edit Product" href="{{route('user.orders.products.edit',$orderDetail->id)}}">
                                                                                                <i class="fa  fa-edit"></i>
                                                                                            </a>
                                                                                            <a class="btn btn-light quick-view" title="Delete Product" onclick="confirm_modal('{{route('user.orders.products.destroy',$orderDetail->id)}}');"  >
                                                                                                <i class="fa  fa-trash"></i>
                                                                                            </a>
                                                                                        @endif
                                                                                        <button class="btn quick-view" title="Quick view" onclick="show_details({{ $orderDetail->id }})">
                                                                                            <i class="la la-eye"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="pagination-wrapper py-4">
                                <ul class="pagination justify-content-end">
                                    {{ $orders->links() }}
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Make Payment')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="payment_modal_body"></div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">

        $(document).ready(function () {
            $('.order-card').hover(function(){
                var order_id = $(this).data('order_id');
                $('#order-card-actions-'+order_id).css('top','10px');
            });
            $('.order-card').mouseleave(function(){
                var order_id = $(this).data('order_id');
                $('#order-card-actions-'+order_id).css('top','-30px');
            });
        });

        function sort_orders(el){
            $('#sort_orders').submit();
        }


        $('#order_details').on('hidden.bs.modal', function () {
            location.reload();
        })

        function show_order_details(order_id)
        {
            $('#order-details-modal-body').html(null);

            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('user.orders.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id}, function(data){
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
            });
        }


        $(document).on('change','#choice_form #product_variant input', function(){
            $.ajax({
                type:"POST",
                url: '{{ route('products.variant_price') }}',
                data: $('#choice_form').serializeArray(),
                success: function(data){
                    $('#product_price').val(null);
                    $('#product_price').val(data.price);
                    $('#available_quantity').val(null);
                    $('#available_quantity').val(data.quantity);
                    $('#available_quantity2').val(null);
                    $('#available_quantity2').val(data.quantity);
                    $('#commission').val(null);
                    $('#commission').val(data.commission);
                }
            });
        });

        $('input[name="free_shipping"]').on('change', function() {
            if($('input[name="free_shipping"]').is(':checked')){
                $('#free_Shipping_reason_row').css('display', 'inline');
                $('#shipping_cost').prop('disabled', true);
            }
            else{
                $('#free_Shipping_reason_row').css('display', 'none');
                $('#shipping_cost').prop('disabled', false);
            }
        });

        //var i = 0;
        function add_more_customer_choice_option(i, name){
    		$('#customer_choice_options').append('<div class="row mb-3"><div class="col-8 col-md-3 order-1 order-md-0"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="Choice Title" readonly></div><div class="col-12 col-md-7 col-xl-8 order-3 order-md-0 mt-2 mt-md-0"><input type="text" class="form-control tagsInput" name="choice_options_'+i+'[]" placeholder="Enter choice values" onchange="update_sku()"></div><div class="col-4 col-xl-1 col-md-2 order-2 order-md-0 text-right"><button type="button" onclick="delete_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button></div></div>');
    		// i++;
            $('.tagsInput').tagsinput('items');
    	}

    	$('input[name="colors_active"]').on('change', function() {
    	    if(!$('input[name="colors_active"]').is(':checked')){
    			$('#colors').prop('disabled', true);
    		}
    		else{
    			$('#colors').prop('disabled', false);
    		}
    		update_sku();
    	});

    	$('#colors').on('change', function() {
    	    update_sku();
    	});

    	$('input[name="unit_price"]').on('keyup', function() {
    	    update_sku();
    	});

        $('input[name="name"]').on('keyup', function() {
    	    update_sku();
    	});

        $('#choice_attributes').on('change', function() {
    		$('#customer_choice_options').html(null);
    		$.each($("#choice_attributes option:selected"), function(){
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
    		update_sku();
    	});

    	function delete_row(em){
    		$(em).closest('.row').remove();
    		update_sku();
    	}

    	function update_sku(){
            $.ajax({
    		   type:"POST",
    		   url:'{{ route('products.sku_combination') }}',
    		   data:$('#choice_form').serialize(),
    		   success: function(data){
    			   $('#sku_combination').html(data);
    			   if (data.length > 1) {
    				   $('#quantity').hide();
    			   }
    			   else {
    			   		$('#quantity').show();
    			   }
    		   }
    	   });
    	}

        var photo_id = 2;
        function add_more_slider_image(){
            var photoAdd =  '<div class="row">';
            photoAdd +=  '<div class="col-2">';
            photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>';
            photoAdd +=  '</div>';
            photoAdd +=  '<div class="col-10">';
            photoAdd +=  '<input type="file" name="photos[]" id="photos-'+photo_id+'" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
            photoAdd +=  '<label for="photos-'+photo_id+'" class="mw-100 mb-3">';
            photoAdd +=  '<span></span>';
            photoAdd +=  '<strong>';
            photoAdd +=  '<i class="fa fa-upload"></i>';
            photoAdd +=  "{{__('Choose image')}}";
            photoAdd +=  '</strong>';
            photoAdd +=  '</label>';
            photoAdd +=  '</div>';
            photoAdd +=  '</div>';
            $('#product-images').append(photoAdd);

            photo_id++;
            imageInputInitialize();
        }
        function delete_this_row(em){
            $(em).closest('.row').remove();
        }

    </script>

@endsection
