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
			font-weight:bolder;
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

		@php
			$generalsetting = \App\Models\GeneralSetting::first();
		@endphp

		<div style="background: #eceff4;padding: 1.5rem;">
			<table>
				<tr>
					<td>

                        @if($generalsetting->logo != null)
                            <img loading="lazy"  src="{{ asset($generalsetting->logo) }}" height="100" style="display:inline-block;">
                        @else
                            <img loading="lazy"  src="{{ asset('receipt_logo.png') }}" height="40" style="display:inline-block;">
                        @endif
					</td>
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
				</tr>
				<tr>
					<td class="gry-color small" >Phone: {{ $generalsetting->phone }}</td>
					<td class="text-right small"><span class="gry-color small">Date:</span> <span class=" strong">{{format_Date(strtotime($receipt_client->created_at))}}</span></td>
				</tr>
			</table>

		</div>

		<div style="padding: 1.5rem;">
			<div class="text-right" style="padding:10px">
				<span>{{format_Date($receipt_client->date_of_receiving_order)}} </span> <span style="float: right;">: التاريخ</span> <br>
				<span>{{ $receipt_client->Staff ? $receipt_client->Staff->email : ''}}</span> <span style="float: right;">: الموظف</span> <br>
				<span style="color: red">{{str_replace('receipt-',' ',$receipt_client->order_num)}}</span> <span style="float: right;color:red">: رقم الفاتورة</span> <br>
				<span>{{$receipt_client->client_name}}</span> <span style="float: right;">: اسم العميل</span>  <br>
				<span>{{$receipt_client->phone}}</span> <span style="float: right;">: رقم العميل</span> <br>
				<span>{{$receipt_client->deposit}}</span> <span style="float: right;">: العربون </span>
			</div>

			<table class="padding text-left small border-bottom">

			<img src="{{ asset($generalsetting->logo) }}" alt="" style="position: absolute;opacity:0.15;top:180px;">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="35%">الصنف</th>
	                    <th width="15%">الكمية</th>
	                    <th width="10%">السعر</th>
	                    <th width="15%" class="text-right">الأجمالي</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach ($receipt_client->receipt_client_products as $key => $product)
		                @if ($receipt_client->receipt_client_products != null)
							<tr class="">
								<td>{{ $product->description }}</td>
								<td class="gry-color">{{ $product->quantity }}</td>
								<td class="gry-color currency">{{ $product->cost }} @if($product->cost != null) EGP @endif</td>
			                    <td class="text-right currency">{{ single_price($product->total) }} </td>
							</tr>
						@endif
					@endforeach
	            </tbody>
			</table>
		</div>

		@php
			$discount = round( ( ($receipt_client->total/100) * $receipt_client->discount ) , 2);
		@endphp

	    <div style="padding:0 1.5rem;">
	        <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
		        <tbody>
		            <tr>
		                <td>+ {{single_price($receipt_client->total)}}</td>
						<th width="35%" class="gry-color text-right">إجمالي</th>
		            </tr>
		            <tr>
		                <td>- {{$receipt_client->deposit}}</td>
		                <th width="35%" class="gry-color text-right">العربون</th>
		            </tr>
		            <tr>
		                <td>- {{$discount}}</td>
		                <th width="35%" class="gry-color text-right">الخصم</th>
		            </tr>

			        <tr>

			            <td class="currency">= {{ single_price($receipt_client->total - $discount - $receipt_client->deposit) }}</td>
			            <th width="35%" class="gry-color text-right">أجمالي الحساب</th>
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
