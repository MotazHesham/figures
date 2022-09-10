@extends('frontend.layouts.app')

@section('content')
    @if(count($errors)>0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{$error}}
        </div>
    @endforeach
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
    @endif
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
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
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (count($orders) > 0)
                            <!-- Order history table -->
                            <div class="card no-border mt-4">
                                <div class="card-header py-3">
                                </div>
                                <div>
                                    <table class="table table-sm table-hover table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th>{{__('Code')}}</th>
                                                <th>{{__('Phone Number')}}</th>
                                                <th>{{__('Client Name')}}</th>
                                                <th>{{__('Delivery Status')}}</th>
                                                <th>{{__('Date')}}</th>
                                                <th>{{__('Options')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $key => $order)
                                                @if (count($order->orderDetails) > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="#{{ $order->code }}" onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                                        </td>
                                                        <td>{{$order->phone_number}} <br> {{$order->phone_number2}}</td>
                                                        <td>{{$order->client_name}}</td>
                                                        <td>
                                                            {{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }} 
                                                        </td>
                                                        <td>{{ format_Date($order->date) }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>

                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">
                                                                    <button onclick="show_order_details({{ $order->id }})" class="dropdown-item">{{__('Order Details')}}</button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $orders->links() }}
                            </ul>
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
        $('#order_details').on('hidden.bs.modal', function () {
            location.reload();
        })


    function get_subcategories_by_category(){
		var category_id = $('#category_id').val();
		$.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
		    $('#subcategory_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#subcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		    get_subsubcategories_by_subcategory();
		});
	}

	function get_subsubcategories_by_subcategory(){
		var subcategory_id = $('#subcategory_id').val();
		$.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
		    $('#subsubcategory_id').html(null);
			$('#subsubcategory_id').append($('<option>', {
				value: null,
				text: null
			}));
		    for (var i = 0; i < data.length; i++) {
		        $('#subsubcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		    //get_brands_by_subsubcategory();
			//get_attributes_by_subsubcategory();
		});
        
        $.post('{{ route('products.by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
		    $('#product_id').html(null);
            $('#product_id').append($('<option>', {
				value: null,
				text: null
			}));
            for (var i = 0; i < data.length; i++) {
		        $('#product_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		});
	}

        $(document).ready(function(){
            get_subcategories_by_category();
            $('#subcategory_list').hide();
            $('#subsubcategory_list').hide();
        });

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

        $('#category_id').on('change', function() {
            $('#product_price').val(null);
            $('#available_quantity').val(null);
            $('#available_quantity2').val(null);
            $('#commission').val(null);
            $('#product_variant').html(null);
            get_subcategories_by_category();
        });

        $('#subcategory_id').on('change', function() {
            $('#product_price').val(null);
            $('#available_quantity').val(null);
            $('#available_quantity2').val(null);
            $('#commission').val(null);
            $('#product_variant').html(null);
            get_subsubcategories_by_subcategory();
        });

        $('#product_id').on('change', function() {
            var product_id = $('#product_id').val();
            $.post('{{ route('product.by_id') }}',{_token:'{{ csrf_token() }}', product_id:product_id}, function(data){
                $('#product_price').val(null);
                $('#product_price').val(data.price);
                $('#available_quantity').val(null);
                $('#available_quantity').val(data.quantity);
                $('#available_quantity2').val(null);
                $('#available_quantity2').val(data.quantity);
                $('#commission').val(null);
                $('#commission').val(data.commission);
            });
            $.post('{{ route('product.variant.by_id') }}',{_token:'{{ csrf_token() }}', product_id:product_id}, function(data){
                $('#product_variant').html(null);
                $('#product_variant').html(data);
            });
        });

        $('#subsubcategory_id').on('change', function() {
            var subsubcategory_id = $('#subsubcategory_id').val();
            $.post('{{ route('products.by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
                $('#product_id').html(null);
                $('#product_id').append($('<option>', {
                    value: null,
                    text: null
                }));
                for (var i = 0; i < data.length; i++) {
                    $('#product_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                    $('.demo-select2').select2();
                }
                $('#product_price').val(null);
                $('#available_quantity').val(null);
                $('#available_quantity2').val(null);
                $('#commission').val(null);
                $('#product_variant').html(null);
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
