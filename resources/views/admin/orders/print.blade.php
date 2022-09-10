<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ertgal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style media="all">
		@font-face {
            font-family: 'DINNextLTArabic-Medium';
            src: url("{{ asset('css/DINNextLTArabic-Medium.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
		@page {
			size: auto;   /* auto is the initial value */
			margin: 0;  /* this affects the margin in the printer settings */
		}
        *{
            margin: 0;
            padding: 0;
            line-height: 1.3;
            color: #333542;
        }
		body{
			font-size: .875rem;
            font-family: 'DINNextLTArabic-Medium';
		}
		.gry-color *,
		.gry-color{
			color:#878f9c;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .5rem .7rem;
		}
		table.padding td{
			padding: .7rem;
		}
		table.sm-padding td{
			padding: .2rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:left;
		}
		.text-right{
			text-align:right;
		}
		.small{
			font-size: .85rem;
		}
		.currency{

		}
	</style>
</head>
<body>
	<div>
		<div style="background: #eceff4;padding: 1.5rem;">
			<table>
				<tr>
					<td>
						@if($generalsetting->logo != null)
							<img loading="lazy"  src="{{ asset($generalsetting->logo) }}" height="40" style="display:inline-block;">
						@else
							<img loading="lazy"  src="{{ asset('frontend/images/logo/logo.png') }}" height="40" style="display:inline-block;">
						@endif
					</td>
					<td style="font-size: 2.5rem;" class="text-right strong">INVOICE</td>
				</tr>
			</table>
			<table>
				<tr>
					<td style="font-size: 1.2rem;" class="strong">{{ $generalsetting->site_name }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ $generalsetting->address }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">Email: {{ $generalsetting->email }}</td>
					<td class="text-right small"><span class="gry-color small">Order Code:</span> <span class="strong">{{ $order->code }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small">Phone: {{ $generalsetting->phone }}</td>
					<td class="text-right small"><span class="gry-color small">Order Date:</span> <span class=" strong">{{ format_Date_Time(strtotime($order->created_at)) }}</span></td>
				</tr>
			</table>

		</div>

		<div style="padding: 1.5rem;padding-bottom: 0">
			<table>
				@if($order->user->user_type == 'seller')
					<strong class="text-main">{{__('Seller info') }}</strong><br>
					{{__('Email') }} :<strong class="text-main">{{ $order->user->email }}</strong><br>
					{{__('Social Name') }} :<strong class="text-main">{{ $order->user->social_name }}</strong><br>
				@endif
				<br><strong class="text-main">{{__('Client info')}} :</strong><br>
				<span style="float: left">{{__('Name') }}</span>:<strong class="text-main">{{$order->client_name}}</strong><br>
				@if($order->user->user_type == 'customer')
					<span style="float: left">{{__('Email') }}</span>:<strong class="text-main">{{$order->user->email}}</strong><br>
				@endif
				<span style="float: left">{{__('Phone Number') }}</span>:<strong class="text-main"><span>{{ $order->phone_number }} , {{ $order->phone_number2 }}</span></strong><br>
				<span style="float: left">{{__('Address') }}</span>:<strong class="text-main">{{ $order->shipping_country_name}} , {{ $order->shipping_address }}</strong><br>
				<span style="float: left">الدفع</span>:<strong class="text-main">{{ __($order->payment_type) }}</strong><br>
				<span style="float: left">حالة الدفع</span>:<strong class="text-main">{{__(ucfirst($order->payment_status))}}</strong><br>
			</table>
		</div>

	    <div style="padding: 1.5rem;">
			<table class="padding text-left small border-bottom">

			<img src="{{ asset($generalsetting->logo) }}" alt="" style="position: absolute;opacity:0.15;top:180px;">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="35%">Product Name</th>
	                    <th width="35%">Photo</th>
	                    <th width="35%">Description</th>
	                    <th width="10%">Qty</th>
	                    <th width="15%">Unit Price</th>
	                    <th width="15%" class="text-right">Total</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach ($order->orderDetails as $key => $orderDetail)
		                @if ($orderDetail->product != null)
							<tr class="">
								<td>{{ $orderDetail->product->name }} - {{ $orderDetail->variation }}</td>
								<td>
									@if ($orderDetail->product != null)
										<a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"><img height="50" src={{ asset($orderDetail->product->thumbnail_img) }}/></a>
									@else
										<strong>{{ __('N/A') }}</strong>
									@endif
								</td>
								<td><?php echo $orderDetail->description; ?></td>
								<td class="gry-color">{{ $orderDetail->quantity }}</td>
								<td class="gry-color currency">{{ single_price($orderDetail->price) }}</td>
			                    <td class="text-right currency">{{ single_price($orderDetail->total_cost) }}</td>
							</tr>
		                @endif
					@endforeach
	            </tbody>
			</table>
		</div>
	    <div style="padding:0 1.5rem;">
	        <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
		        <tbody>
			        <tr>
			            <th class="gry-color text-left">Sub Total</th>
			            <td class="currency">+ {{ single_price($order->required_to_pay + $order->extra_commission) }}</td>
			        </tr>
			        <tr>
			            <th class="gry-color text-left">Deposit</th>
			            <td class="currency">- {{ single_price($order->deposit_amount) }}</td>
			        </tr>
			        <tr>
			            <th class="gry-color text-left">Shipping Cost</th>
			            <td class="currency">+ {{ single_price($order->shipping_country_cost) }}</td>
			        </tr>
			        <tr>
			            <th class="text-left strong">Total</th>
			            <td class="currency">= {{ single_price($order->required_to_pay + $order->extra_commission + $order->shipping_country_cost - $order->deposit_amount) }}</td>
			        </tr>
		        </tbody>
		    </table>
	    </div>

	</div>

	<script>
		window.print()
	</script>
</body>
</html>
