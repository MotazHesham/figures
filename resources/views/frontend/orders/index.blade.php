@extends('frontend.layouts.app')

@section('styles')
<style> 
    .order-card{
        transition: all 0.3s;
        -webkit-transition: all 0.3s;
        overflow: hidden;
        max-width: 700px;
        padding:15px;
        border-radius:14px;
        box-shadow: 2px 1px 19px #adadad;
    }
    .order-card-left-side{
        background-image:linear-gradient(#348282,#3580b3);
        border-radius:14px;
        padding:15px;
    } 
    .order-card:hover {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }  
    .order-card-actions{
        position:absolute;
        right: 0;
        top: -30px;
        transition: all 0.3s;
        -webkit-transition: all 0.3s;
    }
    .order-card-actions a{ 
        background-color: rgb(228, 227, 227);
        border-radius: 25px;
        padding:10px; 
        margin: 3px;
    }
    .order-card-actions i{
        color:rgb(62, 170, 62)
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

                        @if(Auth::user()->user_type == 'seller')
                            @php
                                $seller = \App\Models\Seller::where('user_id',Auth::id())->first();
                                if($seller && $seller->seller_type == 'social'){
                                    $calculate_commission = calculate_commission(auth()->user()->social_orders);
                                }else{
                                    $calculate_commission = calculate_commission(auth()->user()->orders);
                                }
                            @endphp
                            <div class="row">
                                <div class="col-md-3 ">
                                    <div class="dashboard-widget text-center red-widget text-white mt-4 c-pointer">
                                        <i class="fa fa-dollar"></i>
                                        <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['pending'])}}</span>
                                        <span class="d-block sub-title">{{ __('pending_commission') }}</span>

                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="dashboard-widget text-center blue-widget text-white mt-4 c-pointer">
                                        <i class="fa fa-dollar"></i>
                                        <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['available'])}}</span>
                                        <span class="d-block sub-title">{{ __('available_commission') }}</span>

                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="dashboard-widget text-center yellow-widget text-white mt-4 c-pointer">
                                        <i class="fa fa-dollar"></i>
                                        <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['requested'])}}</span>
                                        <span class="d-block sub-title">{{ __('requested_commission') }}</span>

                                    </div>
                                </div> 
                                <div class="col-md-3 ">
                                    <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer">
                                        <i class="fa fa-dollar"></i>
                                        <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['delivered'])}}</span>
                                        <span class="d-block sub-title">{{ __('delivered_commission') }}</span>

                                    </div>
                                </div> 
                            </div>
                        @endif

                        <form class="mt-3" id="sort_orders" action="" method="GET"> 

                            <div class="row justify-content-md-center">
                                <div class="col-md-3 text-center">
                                    <span class="badge badge-default">{{__('Order Code')}}</span>
                                    <input type="text" class="form-control" id="code" name="code" @isset($code) value="{{ $code }}" @endisset >
                                </div>
                                @if(Auth::user()->user_type == 'seller')
                                    <div class="col-md-3 text-center">
                                        <span class="badge badge-default">{{__('Phone Number')}}</span>
                                        <input type="text" class="form-control" id="phone" name="phone" @isset($phone) value="{{ $phone }}" @endisset >
                                    </div>
                                @endif
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
                                    @if(Auth::user()->user_type == 'seller')
                                        <div class="col col-md-6">
                                            <a data-toggle="modal" data-target="#commission_modal" class="btn btn-rounded btn-lg btn-danger" style="color:white">{{__('Request Commission')}}</a>
                                        </div>
                                    @endif
                                    <div class="col col-md-6">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                            <form action="{{route('orders.request_commission.store')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="modal fade" id="commission_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{__('Request Commission')}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body"> 
                                                    <div class="form-group">
                                                        <label class=" control-label" for="payment_method">{{__('Payment Method')}}</label>
                                                        <div class="">
                                                            <div style=" margin-bottom: 10px">
                                                                <select class="form-control demo-select2" name="payment_method"> 
                                                                    <option value="in_company">في الشركة</option> 
                                                                    <option value="bank_account">حساب بنكي</option> 
                                                                    <option value="vodafon_cache">فودافون كاش</option> 
                                                                </select>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class=" control-label" for="payment_method">{{__('Transfer Number')}}</label>
                                                        <div class="">
                                                            <div style=" margin-bottom: 10px">
                                                                <input type="text" class="form-control" name="transfer_number" required>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div>
                                                            <input type="submit" value="{{__('Request Commission')}}" class="btn btn-warning btn-rounded">
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (count($orders) > 0)
                                        @foreach ($orders as $key => $order) 
                                            <div class="col-md-6"> 
                                                {{-- order card --}}
                                                <div class="card mb-3 order-card " data-order_id="{{$order->id}}" id="order-card-{{$order->id}}">
                                                    {{-- code --}}
                                                    <div class=" order-card-left-side text-center mb-3" style="color: white"> 
                                                        <div class="row">
                                                            <div class="col">
                                                                {{$order->code}} 
                                                                @if(Auth::user()->user_type == 'seller')
                                                                    @if($order->commission_status == 'pending' && $order->delivery_status == 'delivered')
                                                                        <input class="form-control" type="checkbox" name="orders[]" value="{{$order->id}}">
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-{{delivery_status_function($order->delivery_status)}}">{{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
                                                                <br>
                                                                <span class="badge badge-grey">{{__(ucfirst($order->payment_status))}}</span>
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-default">{{__('Date Created')}}</span><br>
                                                                {{ format_date_time(strtotime($order->created_at)) }}
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                    {{-- order info --}}
                                                    <div class="row"> 
                                                        <div class="col-md-12">
                                                            <div class="row mb-3">
                                                                <div class="col">
                                                                    <span class="badge badge-default">{{__('Client Name')}}</span><br>
                                                                    <span style="color: black;font-weight:400">{{$order->client_name}}</span>
                                                                </div>
                                                                <div class="col">
                                                                    <span class="badge badge-default">{{__('Phone Number')}}</span><br>
                                                                    <span style="color: black;font-weight:400">{{$order->phone_number}} <br> {{$order->phone_number2}}</span>
                                                                </div>
                                                                <div class="col">
                                                                    <span class="badge badge-default">{{__('Total Cost')}}</span><br>
                                                                    <span style="color: black;font-weight:400">{{ single_price($order->required_to_pay + $order->extra_commission + $order->shipping_country_cost -$order->deposit_amount) }}</span><br>
                                                                    <span class="badge badge-default">{{__('Commission')}}</span><br>
                                                                    <span style="color: black;font-weight:400">
                                                                        {{ single_price($order->commission + $order->extra_commission) }}
                                                                        &nbsp; 
                                                                        @if($order->commission_status == 'pending')
                                                                            <span class="badge badge-info">الرصيد معلق</span>
                                                                        @elseif($order->commission_status == 'requested')
                                                                            <span class="badge badge-warning">الرصيد مطلوب</span>
                                                                        @elseif($order->commission_status == 'delivered')
                                                                            <span class="badge badge-success">تم تسليم الرصيد</span>
                                                                        @endif
                                                                    </span> 

                                                                </div>
                                                            </div>
                                                            <hr style="width: 80%">
                                                            <div style="text-align: end">
                                                                <span style="color: rgb(110, 110, 206)">({{ $order->shipping_country_name}} , {{ $order->shipping_address }} )</span> 
                                                                <span class="badge badge-default">{{__('Address')}}</span>
                                                            </div>
                                                        </div>
                                                    </div> 

                                                    {{-- action Buttons --}}
                                                    <div class="order-card-actions" id="order-card-actions-{{$order->id}}">
                                                        
                                                        <a style="cursor: pointer" onclick="show_order_details({{ $order->id }})" title="{{__('Order Details')}}"><i class="fa fa-tag"></i></a> 

                                                        @if($authenticated_to_edit)
                                                            @if($order->delivery_status == 'pending')
                                                                <a href="{{route('user.orders.edit',$order->id)}}" title="{{__('Edit Order')}}"><i class="fa fa-edit"></i></a>
                                                                <a style="cursor: pointer" onclick="confirm_modal('{{route('user.orders.destroy',$order->id)}}');" title="{{__('Delete Order')}}"><i class="fa fa-trash"></i></a>
                                                            @endif
                                                        
                                                            <a target="_blanc" href="{{ route('user.orders.print', $order->id) }}" title="{{__('Print')}}"><i class="fa fa-print"></i></a>
                                                        @endif
                                                    
                                                    </div>
                                                
                                                </div>
                                            </div>
                                        @endforeach 
                                    @endif
                                </div> 
                            </form>
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

            $.post('{{ route('user.orders.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id,authenticated_to_edit:'{{$authenticated_to_edit}}'}, function(data){
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
