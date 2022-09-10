@php
    if(auth()->user()->user_type == 'delivery_man'){
        $extend = 'delivery_man.app';
    }else{
        $extend = 'layouts.app';
    }
@endphp

@extends($extend)  

@section('content')

    <div class="panel">
    	<div class="panel-body">
    		<div class="panel-heading">  
                <h3 style="margin-bottom: 35px;" class="text-center">
                    <a  target="_blanc" 
                        href="{{ route('deliveryman.orders.print',encrypt($order['order_num'])) }}" 
                        style=" box-shadow: 1px 2px 14px #80808091; border-radius: 33px;" 
                        class="btn btn-default badge badge-default">
    
                        <i class="demo-pli-printer icon-lg"></i>
                    </a>
                    {{$order['order_num']}}
                    {{ __('Order Details') }}   
                </h3> 
    		</div>
            @php 
                $delivery_status = $order['delivery_status'];
                $payment_status = $order['payment_status']; 
                $cancel = $order['cancel_reason'];
                $delay = $order['delay_reason'];
            @endphp 
            <div class="row " style="margin-top: 30px">
                <div class="col-md-3 col-lg-offset-3  filteration-box">
                    <div>
                        <label for="update_payment_status_delivery">{{__('Payment Status')}}</label>
                        <select class="form-control demo-select2"  data-minimum-results-for-search="Infinity" id="update_payment_status_delivery">
                            <option value="paid" @if ($payment_status == 'paid') selected @endif>{{__('Paid')}}</option>
                            <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>{{__('Unpaid')}}</option>
                        </select>
                    </div> 
                    <div> 
                        <label for="update_delivery_status_delivery">{{__('Delivery Status')}}</label>
                        <select class="form-control demo-select2"  data-minimum-results-for-search="Infinity" id="update_delivery_status_delivery">
                            @if(Auth::user()->user_type != 'delivery_man' )
                                <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{__('Pending')}}</option>
                                <option value="on_review" @if ($delivery_status == 'on_review') selected @endif>{{__('On review')}}</option>
                            @endif
                            <option value="on_delivery" @if ($delivery_status == 'on_delivery') selected @endif>{{__('On delivery')}}</option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{__('Delivered')}}</option>
                            <option value="delay"   @if($delivery_status == 'delay') selected @endif >{{__('Delay')}}</option>
                            <option value="cancel"   @if($delivery_status == 'cancel') selected @endif >{{__('Cancel')}}</option>
                        </select> 
                    </div>
                </div> 
                <div class="col-md-3" style="margin-bottom:15px ">


					{{-- cancel reason --}}
					<form  method="post" id="cancel_rason_form" style="display: none">
						@csrf
						<input type="hidden" name="order_num" value="{{$order['order_num']}}">
						<div class="filteration-box" >
							<div class="row">
								<h3 class="text-center">
									<span class="badge badge-default">
										{{__('Cancel Reason')}} 
									</span>
								</h3>
								<div class="col-md-12"> 
									<input type="text" value="{{$cancel}}" class="form-control" name="cancel_reason" id="cancel_reason" required>
									<br>
									<input type="submit" name="save" class="btn btn-danger btn-rounded  btn-block" value="save"> 
								</div> 
							</div>
						</div>
					</form>

                    
					{{-- delay reason --}}
					<form method="post" id="delay_rason_form" style="display: none">
						@csrf
						<input type="hidden" name="order_num" value="{{$order['order_num']}}">
						<div class="filteration-box" >
							<div class="row">
								<h5 class="text-center">
									<span class="badge badge-default">
										{{__('Delay Reason')}} 
									</span>
								</h5>
								<div class="col-md-12">
									<textarea name="delay_reason" id="delay_reason" class="form-control" cols="30" rows="5" >{{$delay}}</textarea>
									<br>
									<input type="submit" name="save" class="btn btn-warning btn-rounded  btn-block" value="save">
								</div> 
							</div>
						</div>
					</form> 
				</div>
            </div> 
            <hr>
            <div class="row text-center">
                <div class="col-md-5">
                    <div>   
                        <span class="badge badge-default">{{__('Code')}}</span>
                        {{$order['order_num']}}
                    </div>
                    <div>   
                        <span class="badge badge-default">{{__('Client Name')}}</span>
                        {{$order['client_name']}}
                    </div>
                    <div>   
                        <span class="badge badge-default">{{__('Phone')}}</span>
                        {{$order['phone']}} 
                    </div>
                    <div>   
                        <span class="badge badge-default">{{__('Address')}}</span>
                        <br>
                        {{$order['address']}}
                    </div>
                </div>
                <div class="col-md-2">
                    <div>   
                        <span class="badge badge-default">{{__('Shipping Cost')}}</span>
                        {{single_price($order['shipping_cost'])}}
                    </div>
                    <h3 class="text-center">
                        {{__('Description')}}
                    </h3>
                    <?php echo $order['description'] ?></p>
                </div>
                <div class="col-md-5">
                    <div>    
                        <span class="badge badge-default">{{__('Deposit')}}</span>
                        {{single_price($order['deposit'])}}
                    </div>
                    <div>   
                        <span class="badge badge-default">{{__('Order Cost')}}</span>
                        {{single_price($order['order_cost'])}}
                    </div>
                    <div>   
                        <span class="badge badge-default">{{__('Total')}}</span>
                        {{single_price($order['total'])}}
                    </div>
                </div>
            </div>  
        </div>
	</div>
	

	<div class="modal fade" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
	</div>
	
@endsection

@section('script')

    @php
        $route_delivery_status = $order['route'] . '.update_delivery_status';
        $route_payment_status = $order['route'] . '.update_payment_status';
        $route_order_delay_reason = $order['route'] . '.order_delay_reason';
        $route_cancel_order_reason = $order['route'] . '.cancel_order_reason';
    @endphp
	<script type="text/javascript">

		$(document).ready(function(){
            var status = $('#update_delivery_status_delivery').val();
			if(status == 'cancel'){
				$('#cancel_rason_form').css('display','inline');
			}else{
				$('#cancel_rason_form').css('display','none');
			} 

			if(status == 'delay'){
				$('#delay_rason_form').css('display','inline');
			}else{
				$('#delay_rason_form').css('display','none');
			}
        });

        $('#delay_rason_form').on('submit', function(e){
            e.preventDefault();
            var form_data = $(this).serialize();
            $.post('{{ route($route_order_delay_reason) }}', form_data, function(data){ 
                showAlert('success', 'تم تعديل سبب التأجيل');  
            });
        });   
        
        $('#cancel_rason_form').on('submit', function(e){
            e.preventDefault();
            var form_data = $(this).serialize();
            $.post('{{ route($route_cancel_order_reason) }}', form_data, function(data){ 
                showAlert('success', 'تم تعديل سبب الألغاء');  
            });
        });   

        $('#update_delivery_status_delivery').on('change', function(){
            var status = $('#update_delivery_status_delivery').val();
            $.post('{{ route($route_delivery_status) }}', {_token:'{{ @csrf_token() }}',order_num:"{{$order['order_num']}}",status:status}, function(data){ 
                showAlert('success', 'تم تعديل حالة التوصيل');
            });
			if(status == 'cancel'){
				$('#cancel_rason_form').css('display','inline');
			}else{
				$('#cancel_rason_form').css('display','none');
			}
            if(status == 'delay'){
				$('#delay_rason_form').css('display','inline');
			}else{
				$('#delay_rason_form').css('display','none');
			}
        });

        $('#update_payment_status_delivery').on('change', function(){
            var status = $('#update_payment_status_delivery').val();
            $.post('{{ route($route_payment_status) }}', {_token:'{{ @csrf_token() }}',order_num:"{{$order['order_num']}}",status:status}, function(data){
                showAlert('success', 'تم تعديل حالة الدفع');
            });
        }); 
        
    </script>
@endsection
