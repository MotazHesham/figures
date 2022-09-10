<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ebtekar</title>
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
							<img loading="lazy"  src="{{ asset($generalsetting->logo) }}" height="40" style="display:inline-block;">
						@else
							<img loading="lazy"  src="{{ asset('frontend/images/logo/logo.png') }}" height="40" style="display:inline-block;">
						@endif
					</td>
					<td style="font-size: 2.5rem;" class="text-right strong">Receipt</td>
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
				</tr>
			</table>

		</div>
		<table style="padding: 1.5rem;float: right;position: relative;">
			<img src="{{ asset($generalsetting->logo) }}" alt="" style="position: absolute;opacity:0.25;top:180px;">
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{format_Date(strtotime($delivery['send_to_deliveryman_date']))}}</span> <span class="gry-color strong" style="float:right">: تحرير في </span></td>
			</tr>
			<tr >
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px"><span >{{$delivery['order_num']}}</span> <span class="gry-color strong" style="float:right" >: رقم الأوردر</span></td>
			</tr>
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{$delivery['client_name']}}</span> <span class="gry-color strong" style="float:right" >: اسم العميل</span></td>
			</tr>
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{$delivery['phone']}}</span> <span class="gry-color strong" style="float:right" >: رقم التليفون</span></td>
			</tr>
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{format_Date($delivery['deliver_date'])}} </span> <span class="gry-color strong" style="float:right">:  ميعاد التوصيل</span></td>
			</tr>
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{$delivery['address']}}</span> &nbsp; <span class="gry-color strong" style="float:right"  >: مكان التوصيل</span></td>
			</tr>
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span > <span>{{$delivery['order_cost']}} EGP </span>  </span> <span class="gry-color strong" style="float:right"  >: حساب الأوردر </span><small style="color: grey;float: right;"> (شامل الشحن) </small></td>
			</tr> 
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{$delivery['deposit']}} </span> <span class="gry-color strong" style="float:right" >: العربون</span></td>
			</tr>
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span >{{$delivery['total']}}EGP</span> <span class="gry-color strong" style="float:right" >: المطلوب دفعه</span></td>
			</tr>
			<tr>
				<td class="text-right small" style="font-size: 1.2rem;padding-bottom:18px" ><span class="gry-color strong">: تفاصيل الأوردر</span><br> <span class=" strong" ><?php echo $delivery['description'];?></span></td>
		</table>
		

	</div>

	<script>
		window.print()
	</script>
	
</body>
</html>