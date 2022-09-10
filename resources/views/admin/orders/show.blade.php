@extends('layouts.app')

@section('content')

    <div class="panel">
		<div class="panel-body text-center">
			<h3 style="margin-bottom: 35px;">
				<a  target="_blanc"
					href="{{ route('admin.orders.print', $order->id) }}"
					style=" box-shadow: 1px 2px 14px #80808091; border-radius: 33px;"
					class="btn btn-default badge badge-default">

					<i class="demo-pli-printer icon-lg"></i>
				</a>
				{{$order->code}}
				{{ __('Order Details') }}
			</h3>
            <div class="row">
				{{-- info --}}
				<div class="col-md-3" style="margin-bottom:15px ">
					<div class="filteration-box text-center">
						@if($order->user->user_type == 'seller')
							<h3>{{__('Seller info') }}</h3>
							<div>
								<span class="badge badge-default">{{__('Seller Code') }}</span>{{ $order->user->seller->seller_code }}
							</div>
							<div>
								<span class="badge badge-default">{{__('Email') }}</span> {{ $order->user->email }}
							</div>
							<div>
								<span class="badge badge-default">{{__('Social Name') }}</span> {{ $order->user->seller->social_name }}
							</div>
							<div>
								<span class="badge badge-default">{{__('Social Link') }}</span> <a href="{{ $order->user->seller->social_link }}" target="_blanc">{{ $order->user->seller->social_link }}</a>
							</div>
							<div>
								<span class="badge badge-default">{{__('Commission') }}</span> {{ single_price($order->commission + $order->extra_commission) }}
							</div>
							<hr>
						@endif

						<h3>{{__('Client info')}}</h3>
						<div>
							<span class="badge badge-default">{{__('Client Name') }}</span> {{$order->client_name}}
						</div>
						<div>
							<span class="badge badge-default">{{__('Phone Number') }}</span> {{ $order->phone_number }} , {{ $order->phone_number2 }}
						</div>
						<div>
							<span class="badge badge-default">{{__('Address') }}</span>{{ $order->shipping_country_name}} , {{ $order->shipping_address }}
						</div>
						@if($order->user->user_type == 'customer')
							<div>
								<span class="badge badge-default">{{__('Email') }}</span> {{$order->user->email}}
							</div>
							<div>
								<span class="badge badge-default">{{__('Order #') }}</span> {{$order->code}}
							</div>
							<div>
								<span class="badge badge-default">{{__('Order Status') }}</span> {{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
							</div>
							<div>
								<span class="badge badge-default">{{__('Date Created') }}</span> {{format_Date_Time(strtotime($order->created_at))}}
							</div>
							<div>
								<span class="badge badge-default">الدفع</span> {{ __($order->payment_type) }}
							</div>
							<div>
								<span class="badge badge-default">حالة الدفع</span> {{__(ucfirst($order->payment_status))}}
							</div>
							@if($order->discount_code != null)
								<div>
									<span class="badge badge-default">كود الخصم </span> {{ $order->discount_code }}
								</div>
							@endif
						@endif

					</div>
				</div>

				{{-- note --}}
				<div class="col-md-3" style="margin-bottom:15px ">
					{{-- order note --}}
					<form action="{{route('admin.orders.update_order_note')}}" method="post">
						@csrf
						<input type="hidden" name="order_num" value="{{$order->code}}">
						<div class="filteration-box" >
							<div class="row">
								<h3 class="text-center">
									<span class="badge badge-default">
										{{__('Note')}}
									</span>
								</h3>
								<div class="col-md-12">
									<textarea name="note" id="note" cols="50" rows="5" class="form-control">{{$order->note}}</textarea>
									<br>
									<input type="submit" name="save" class="btn btn-info btn-rounded  btn-block" value="save">
								</div>
							</div>
						</div>
					</form>
				</div>


					{{-- filter --}}
					<div class="col-md-3" style="margin-bottom:15px ">
						<div class="filteration-box text-center">
							{{-- delivery man --}}
							<div style="margin-bottom: 10px">
								<label for="update_delivery_man">
									<span  class=" badge badge-default">
										{{__('Delivery Man')}}
									</span>
								</label>
								<select class="form-control demo-select2"  data-minimum-results-for-search="Infinity" id="update_delivery_man">
									<option value="0">{{__('Select Delivery Man ...')}}</option>
									@foreach($users_staff as $user)
										<option value="{{$user->id}}" @if ($order->delivery_man == $user->id) selected @endif>{{$user->email}}</option>
									@endforeach
								</select>
							</div>

							{{-- delivery status --}}
							<div style="margin-bottom: 10px">
								<label for="update_delivery_status">
									<span  class=" badge badge-default">
										{{__('Delivery Status')}}
									</span>
								</label>
								<select class="form-control demo-select2"  data-minimum-results-for-search="Infinity" id="update_delivery_status">
									<option value="pending" @if ($order->delivery_status == 'pending') selected @endif>{{__('Pending')}}</option>
									<option value="on_review" @if ($order->delivery_status == 'on_review') selected @endif>{{__('On review')}}</option>
									<option value="on_delivery" @if ($order->delivery_status == 'on_delivery') selected @endif>{{__('On delivery')}}</option>
									<option value="delivered" @if ($order->delivery_status == 'delivered') selected @endif>{{__('Delivered')}}</option>
									<option value="delay" @if ($order->delivery_status == 'delay') selected @endif>{{__('Delay')}}</option>
									<option value="cancel"   @if($order->delivery_status == 'cancel') selected @endif >{{__('Cancel')}}</option>
								</select>
							</div>

							{{-- payment status --}}
							<div style="margin-bottom: 10px">
								<label for="update_payment_status">
									<span  class=" badge badge-default">
										{{__('Payment Status')}}
									</span>
								</label>
								<select class="form-control demo-select2"  data-minimum-results-for-search="Infinity" id="update_payment_status">
									<option value="paid" @if ($order->payment_status == 'paid') selected @endif>{{__('Paid')}}</option>
									<option value="unpaid" @if ($order->payment_status == 'unpaid') selected @endif>{{__('Unpaid')}}</option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-6" style="margin-bottom:15px ">
						<div class="filteration-box text-center">
							@if(!$order->sent_to_wasla)
								<form action="{{route('admin.orders.send_to_wasla')}}"  class="form-horizontal" method="POST">
									@csrf
									<div class="form-group">
										<label class="col-sm-2 control-label" for="#">{{__('Countries')}}</label>
										<div class="col-sm-4">
											@if($response['data'] ?? null)
												<select class="form-control demo-select2" name="country_id" id="country_id" required>
													@foreach($response['data'] as $country)
															<option value="{{$country['id']}}">EGP {{$country['cost']}} - {{$country['name']}}  </option>
													@endforeach
												</select>
											@else
												not found
											@endif
										</div>

										<label class="col-sm-2 control-label" for="#">هل سيستلم الكابتن طرد من العميل ؟</label>
										<div class="col-sm-4">
											<select class="form-control demo-select2" name="type" id="type" required>
												<option value="no">لا</option>
												<option value="partial">مرتجع جزئي</option>
												<option value="change">مرتجع استبدال</option>
												<option value="return">مرتجع استرجاع</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="#">الحالة</label>
										<div class="col-sm-4">
											<select class="form-control demo-select2" name="status" id="status" required>
												<option value="draft">في المحفوطة</option>
												<option value="sent">مرسلة للشحن</option>
											</select>
										</div>
										<label class="col-sm-2 control-label" for="#">المنطقة</label>
										<div class="col-sm-4">
											<input type="text"  name="district" class="form-control" id="" required>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="#">المطلوب تحصيله شامل الشحن</label>
										<div class="col-sm-4">
											<input type="number"  name="cost" class="form-control" id="" value="{{$order->required_to_pay + $order->extra_commission + $order->shipping_country_cost - $order->deposit_amount}}" required>
										</div>
										<label class="col-sm-2 control-label" for="#">في حالة الأسترجاع</label>
										<div class="col-sm-4">
											<input type="number"  name="in_return_case" class="form-control" id="" required>
										</div>
									</div>

									<input type="hidden" name="order_id" value="{{$order->id}}" id="">

									<button type="submit" class="btn btn-danger btn-block btn-rounded">أرسال لوصلة</button>
								</form>
							@else
							<div class="row">
								<div class="col-md-6">
									<h4 class="text-center" style="padding:20px">
										<i class="fa fa-check-circle" style="font-size: 40px; color: green;padding:10px"></i>
										<br>
										تم أرسال الفاتورة لوصلة
									</h4>
								</div>
								<div class="col-md-6" style="padding:45px">
									<div>
										<span style="font-size: 20px;" class="badge badge-{{delivery_status_function($order->delivery_status)}}">{{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
										@if($order->delivery_status == 'delay')
											<span class="badge badge-defualt">{{$order->delay_reason}}</span>
										@elseif($order->delivery_status == 'delivered')
											<span class="badge badge-success">{{format_Date_time($order->done_time)}}</span>
										@elseif($order->delivery_status == 'cancel')
											<span class="badge badge-danger">{{$order->cancel_reason}}</span>
										@endif
									</div>
								</div>
							</div>
							@endif
						</div>
					</div>


				{{-- reasons --}}
				<div class="col-md-3" style="margin-bottom:15px ">

					{{-- delay reason --}}
					<form action="{{route('admin.orders.order_delay_reason')}}" method="post" id="delay_rason_form" style="display: none">
						@csrf
						<input type="hidden" name="order_id" value="{{$order->id}}">
						<div class="filteration-box" >
							<div class="row">
								<h5 class="text-center">
									<span class="badge badge-default">
										{{__('Delay Reason')}}
									</span>
								</h5>
								<div class="col-md-12">
									<textarea  name="delay_reason" id="delay_reason" class="form-control" cols="30" rows="5" >{{$order->delay_reason}}</textarea>
									<br>
									<input type="submit" name="save" class="btn btn-warning btn-rounded  btn-block" value="save">
								</div>
							</div>
						</div>
					</form>

					{{-- cancel reason --}}
					<form action="{{route('admin.orders.cancel_order_reason')}}" method="post" id="cancel_rason_form" style="display: none">
						@csrf
						<input type="hidden" name="order_id" value="{{$order->id}}">
						<div class="filteration-box" >
							<div class="row">
								<h3 class="text-center">
									<span class="badge badge-default">
										{{__('Cancel Reason')}}
									</span>
								</h3>
								<div class="col-md-12">
									<input type="text" value="{{$order->cancel_reason}}" class="form-control" name="cancel_reason" id="cancel_reason" required disabled>
									<br>
									<input type="submit" name="save" class="btn btn-danger btn-rounded  btn-block" value="save">
								</div>
							</div>
						</div>
					</form>

				</div>
			</div>

            <hr>

			<div class="row">
				@if($order->user->user_type == 'seller')
					<div class="col-md-4" style="margin-bottom:15px ">
							<span class="badge badge-default">{{__('Order #')}}</span>
							{{ $order->code }}
							<br>

							<span class="badge badge-default">{{__('Order Status')}}</span>
							<span class="badge badge-success">{{ __(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
							<br>

						<hr>
							<span class="badge badge-default">{{__('Deposit')}}</span>
							{{$order->deposit}}
							<br>

							<span class="badge badge-default">{{__('Deposit Amount')}}</span>
							{{single_price($order->deposit_amount)}}
							<br>

						<hr>
							<span class="badge badge-default">{{__('Date Created')}}</span>
							{{format_Date_Time(strtotime($order->created_at))}}
							<br>


							<span class="badge badge-default">{{__('Date Of Receiving Order')}}</span>
							{{format_Date($order->date_of_receiving_order)}}
							<br>

							<span class="badge badge-default">{{__('Excepected Deliverd Date')}}</span>
							{{format_Date($order->excepected_deliverd_date)}}
							<br>


						<hr>
							<span class="badge badge-default">{{__('Total cost')}}</span><small style="color: grey;"> ({{__('Added by seller')}})</small>
							{{ single_price($order->total_cost_by_seller) }}
							<br>

							<span class="badge badge-default">{{__('Shipping Cost')}}</span><small style="color: grey;"> ({{__('Added by seller')}})</small>
							{{ single_price($order->shipping_cost_by_seller) }}
							<br>

							<span class="badge badge-default">{{__('Free Shipping')}}</span>
									@if($order->free_shipping == 1)
										Yes
										<br> <b>السبب</b> :{{$order->free_shipping_reason}}
									@endif
									@if($order->free_shipping == 0)
										No
									@endif

						<hr>
					</div>
				@endif
				<div class="col-md-@if($order->user->user_type == 'seller') 8 @else 12 @endif table-responsive" style="margin-bottom:15px ">
					<table class="table table-bordered invoice-summary">
						<thead>
							<tr class="bg-trans-dark">
								<th class="min-col">#</th>
								<th width="10%">
									{{__('Photo')}}
								</th>
								<th class="min-col text-center text-uppercase">
									{{__('Products')}}
								</th>
								<th class="min-col text-center text-uppercase">
									{{__('Total')}}
								</th>
								@if($order->user->user_type == 'seller')
									<th class="min-col text-center text-uppercase">
										{{__('Commission')}}
									</th>
									<th class="min-col text-center text-uppercase">
										{{__('Extra Commission')}}
									</th>
								@endif
								<th class="min-col text-center text-uppercase">
									{{__('View Details')}}
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($order->orderDetails as $key => $orderDetail)
								<form action="{{route('admin.orders.update_extra_commission')}}" method="POST">
									@csrf
									<input type="hidden" name="order_detail_id" value="{{$orderDetail->id}}">
									<tr>
										<td>{{ $key+1 }}</td>
										<td>
											@if ($orderDetail->product != null)
												<a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"><img height="50" src={{ asset($orderDetail->chosen_photo ?? $orderDetail->product->thumbnail_img) }}/></a>
											@else
												<strong>{{ __('N/A') }}</strong>
											@endif
										</td>
										<td>
											@if ($orderDetail->product != null)
												<strong><a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">{{ $orderDetail->product->name }}</a></strong>
												<small>@if ($orderDetail->variation != null)
													({{$orderDetail->variation}})
												@endif</small>
											@else
												<strong>{{ __('Product Unavailable') }}</strong>
											@endif
										</td>
										<td class="text-center">
											<span class="badge badge-default">{{__('Qty')}} {{ $orderDetail->quantity }}</span><br>
											<span class="badge badge-default">{{__('Price')}} {{ single_price($orderDetail->price) }}</span><br>
											<span class="badge badge-success">= {{ single_price($orderDetail->total_cost) }}</span>
										</td>
										@if($order->user->user_type == 'seller')
											<td class="text-center">
												<span class="badge badge-info">{{ single_price($orderDetail->commission) }}</span><br>
											</td>
											<td class="text-center">
												<input type="number" step="0.1"  name="extra_commission" class="form-control" value="{{$orderDetail->extra_commission}}" required>
											</td>
										@endif
										<td class="text-center">
											@if($order->user->user_type == 'seller')
												<input type="submit" value="{{__('Update')}}" class="btn btn-warning btn-rounded">
											@endif
											<a class="btn btn-info btn-rounded"   onclick="show_details({{ $orderDetail->id }})">{{ __('View Details') }}</a>
											{{-- <a class="btn btn-primary btn-rounded" href="{{route('admin.orders.edit_product_of_order',$order->id)}}" ><i class="fa fa-edit"></i></a>  --}}
											<a class="btn btn-danger btn-rounded" title="Delete Product" onclick="confirm_modal('{{route('admin.orders.delete_product_of_order',$orderDetail->id)}}');"  >
												<i class="fa  fa-trash"></i>
											</a>
										</td>
									</tr>
								</form>
							@endforeach
						</tbody>
					</table>
					<div class="clearfix">
						<table class="table invoice-total">
						<tbody>
						<tr>
							<td>
								<strong>{{__('Sub Total')}} :</strong>
							</td>
							<td>
								+ {{ single_price($order->required_to_pay) }}
							</td>
						</tr>
						<tr>
							<td>
								<strong>{{__('Extra Commission')}} :</strong>
							</td>
							<td>
								+ {{ single_price($order->extra_commission) }}
							</td>
						</tr>
						<tr>
							<td>
								<strong>{{__('Deposit')}} :</strong>
							</td>
							<td>
								- {{ single_price($order->deposit_amount) }}
							</td>
						</tr>
						<tr>
							<td>
								<strong>{{__('Shipping')}} :</strong>
							</td>
							<td>
								+ {{ single_price($order->shipping_country_cost) }}
							</td>
						</tr>
						<tr style="background: #34828285">
							<td>
								<strong>{{__('TOTAL')}} :</strong>
							</td>
							<td class="text-bold h4">
								= {{ single_price($order->required_to_pay + $order->extra_commission + $order->shipping_country_cost - $order->deposit_amount) }}
								@if($order->discount_code != null)
									<br>
									<span class="badge badge-purple">
										كود الخصم {{ $order->discount_code }}
										/
										{{ single_price($order->discount) }}
									</span>
								@endif
							</td>
						</tr>
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="product-details">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div id="product-details-modal">

                </div>
            </div>
        </div>
	</div>

@endsection

@section('script')
	<script type="text/javascript">

		$(document).ready(function(){
            var status = $('#update_delivery_status').val();
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

        $('#update_delivery_status').on('change', function(){
            var status = $('#update_delivery_status').val();
            $.post('{{ route('admin.orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_num:"{{$order->code}}",status:status}, function(data){
                showAlert('success', 'Delivery status has been updated');
                location.reload();
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

        $('#update_payment_status').on('change', function(){
            var status = $('#update_payment_status').val();
            $.post('{{ route('admin.orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_num:"{{$order->code}}",status:status}, function(data){
                showAlert('success', 'Payment status has been updated');
            });
        });

        $('#update_delivery_man').on('change', function(){
            var user_id = $('#update_delivery_man').val();
            $.post('{{ route('admin.orders.update_delivery_man') }}', {_token:'{{ @csrf_token() }}',order_id:"{{$order->id}}",user_id:user_id}, function(data){
                showAlert('success', 'Delivery Man has been updated');
            });
        });

		function show_details(id){
			if(!$('#modal-size').hasClass('modal-lg')){
				$('#modal-size').addClass('modal-lg');
			}
			$('#product-details-modal').html(null);
			$('#product-details').modal();
			$('.c-preloader').show();
			$.post('{{ route('admin.orders.show_details') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
				$('.c-preloader').hide();
				$('#product-details-modal').html(data);
			});
		}
    </script>
@endsection
