<div class="modal-header">
    <h5 class="modal-title strong-600 heading-5">{{__('Order id')}}: {{ $order->code }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@php
    $status = $order->delivery_status;
@endphp

<div class="modal-body gry-bg px-3 pt-0">
    <div class="pt-4">
        @if($status != 'cancel')
        <ul class="process-steps clearfix">
            <li @if($status == 'pending') class="active" @else class="done" @endif>
                <div class="icon">1</div>
                <div class="title">{{__('Order placed')}}</div>
            </li>
            <li @if($status == 'on_review') class="active" @elseif($status == 'on_delivery' || $status == 'delivered') class="done" @endif>
                <div class="icon">2</div>
                <div class="title">{{__('On review')}}</div>
            </li>
            <li @if($status == 'on_delivery') class="active" @elseif($status == 'delivered') class="done" @endif>
                <div class="icon">3</div>
                <div class="title">{{__('On delivery')}}</div>
            </li>
            <li @if($status == 'delivered') class="done" @endif>
                <div class="icon">4</div>
                <div class="title">{{__('Delivered')}}</div>
            </li>
        </ul>
        @else
        <ul class="process-steps clearfix">
            <li @if($status == 'cancel') class="active" @else class="done" @endif>
                <div class="icon" style="background:brown"></div>
                <div class="title" style="color:black">{{__('Order Canceld')}}</div>
                <span>Cancel Reason : {{$order->cancel_reason}}</span>
            </li>
        </ul>
        @endif
    </div>
    <div class="card mt-4">
        <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
            <div class="float-left">{{__('Order Summary')}}</div>
        </div>
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-lg-6">
                    <table class="details-table table">
                        <tr>
                            <td class="w-50 strong-600">{{__('Order Code')}}:</td>
                            <td>{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Client Name')}}:</td>
                            <td>{{$order->client_name}}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Seller Email')}}:</td>
                            @if ($order->user_id != null)
                                <td>{{ $order->user->email }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Date Of Receiving Order')}}:</td>
                            <td>{{ format_Date($order->date_of_receiving_order) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Shipping address')}}:</td>
                            <td>{{$order->shipping_country_name}} , {{$order->shipping_address}}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Deposit')}}:</td>
                            <td>{{$order->deposit}}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Phone Number')}}:</td>
                            <td>{{$order->phone_number}} , {{$order->phone_number2}}</td>
                        </tr>
                        @if($order->discount_code)
                            <tr>
                                <td class="w-50 strong-600">كود الخصم:</td>
                                <td>{{$order->discount_code}} </td>
                            </tr>
                        @endif
                        @if($order->discount)
                            <tr>
                                <td class="w-50 strong-600">{{__('Discount')}}:</td>
                                <td>{{single_price($order->discount)}} </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="details-table table">
                        <tr>
                            <td class="w-50 strong-600">{{__('Date Created')}}:</td>
                            <td>{{ format_Date_time(strtotime($order->created_at)) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Order status')}}:</td>
                            <td>{{ __(ucfirst(str_replace('_', ' ', $status))) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Total Order Cost')}}:</td>
                            <td>{{ single_price($order->total_cost_by_seller)}}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Excepected Deliverd Date')}}:</td>
                            <td>{{ format_Date($order->excepected_deliverd_date)}}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Free Shipping')}}:</td>
                            <td>
                                @if($order->free_shipping == 1)
                                    Yes 
                                    <br> <b>السبب</b> :{{$order->free_shipping_reason}}
                                @endif
                                @if($order->free_shipping == 0)
                                    No 
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Deposit Amount')}}:</td>
                            <td>{{single_price($order->deposit_amount)}}</td>
                        </tr>
                        <tr>
                            <td class="w-50 strong-600">{{__('Shipping Cost')}}:<br><small>({{__('Added by seller')}})</small></td>
                            <td>{{single_price($order->shipping_cost_by_seller)}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div class="card mt-4">
                <div class="card-header py-2 px-3 heading-6 strong-600">{{__('Order Details')}}</div>
                <div class="card-body pb-0">
                    <table class="details-table table table-responsive-md">
                        <thead>
                            <tr>
                                <th>{{__('View Details')}}</th>
                                <th width="30%">{{__('Product')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Total')}}</th>
                                <th>{{__('Commission')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>
                                        @if($authenticated_to_edit)
                                            @if($status == 'pending')
                                                <a class="btn btn-light quick-view" title="Edit Product" href="{{route('user.orders.products.edit',$orderDetail->id)}}">
                                                    <i class="fa  fa-edit"></i>
                                                </a> 
                                                <a class="btn btn-light quick-view" title="Delete Product" onclick="confirm_modal('{{route('user.orders.products.destroy',$orderDetail->id)}}');"  >
                                                    <i class="fa  fa-trash"></i>
                                                </a> 
                                            @endif
                                        @endif
                                        <button class="btn quick-view" title="Quick view" onclick="show_details({{ $orderDetail->id }})">
                                            <i class="la la-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                {{ $orderDetail->product->name }} 
                                                <br>
                                                @if ($orderDetail->variation != null) 
                                                    ({{$orderDetail->variation}})
                                                @endif
                                            
                                            </a>
                                        @else
                                            <strong>{{ __('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        <?php echo $orderDetail->description; ?> 
                                    </td>
                                    <td>
                                        <span class="badge badge-default">{{__('Qty')}} {{ $orderDetail->quantity }}</span><br>
                                        <span class="badge badge-default">{{__('Price')}} {{ single_price($orderDetail->price) }}</span><br>
                                        <span class="badge badge-success">= {{ single_price($orderDetail->total_cost) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-default">{{single_price($orderDetail->commission)}}</span>
                                        <br>
                                        <span class="badge badge-default">{{single_price($orderDetail->extra_commission)}} Extra</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mt-4">
                <div class="card-header py-2 px-3 heading-6 strong-600">{{__('Total')}}</div>
                <div class="card-body pb-0">
                    <table class="table details-table">
                        <tbody>
                            <tr>
                                <th>{{__('Subtotal')}}</th>
                                <td class="text-right">
                                    <span class="strong-600">+{{ single_price($order->required_to_pay ) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('Extra Commission')}}</th>
                                <td class="text-right">
                                    <span class="strong-600">+{{ single_price($order->extra_commission ) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('Deposit')}}</th>
                                <td class="text-right">
                                    <span class="strong-600">-{{ single_price($order->deposit_amount) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{__('Shipping')}}<br><small>({{__('From System')}})</small></th>
                                <td class="text-right">
                                    <span class="text-italic">+{{ single_price($order->shipping_country_cost) }}</span>
                                </td>
                            </tr>
                            <tr style="background: #34828285">
                                <th><span class="strong-600">{{__('Total')}}</span></th>
                                <td class="text-right">
                                    <strong><span>={{ single_price($order->required_to_pay + $order->extra_commission + $order->shipping_country_cost - $order->deposit_amount) }}</span></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> 
        </div>
    </div>
</div> 
