@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading" style="padding:10px">
        <h3 class="text-center">{{__('Commission Requests')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Date Requested')}}</th>
                    <th>{{__('Seller')}}</th>
                    <th>{{__('Payment Method')}}</th>
                    <th>{{__('Transfer Number')}}</th>
                    <th>{{__('Commission Orders')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Added By')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commission_requests as $key => $raw)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>
                            {{format_Date_Time(strtotime($raw->created_at))}}
                        </td>
                        <td>{{$raw->user ? $raw->user->email : ''}}</td>
                        <td>{{$raw->payment_method}}</td>
                        <td>{{$raw->transfer_number}}</td>
                        <td>
                            @if($raw->commission_request_orders)
                                @foreach($raw->commission_request_orders as $raw2)
                                    @if($raw2->order) 
                                        <span class="badge badge-default">{{$raw2->order->code}}</span>
                                        <span class="badge badge-warning">{{single_price($raw2->commission)}}</span>
                                    @endif
                                    <br>
                                @endforeach
                            @endif
                            <span class="badge badge-success">{{__('Total')}} {{$raw->total_commission}}</span>
                        </td>
                        <td>
                            @if($raw->status == 'requested')
                                <span class="badge badge-info">مطلوب للتوريد</span>
                            @elseif($raw->status == 'delivered')
                                <span class="badge badge-success">تم التوريد</span>
                                <br>
                                <br>
                                <span class="badge badge-info">
                                    {{__('Added By')}}
                                    <br>
                                    {{$raw->done_by_user ? $raw->done_by_user->email : ''}}
                                </span>
                                <br>
                                <br>
                                <span class="badge badge-mint">
                                    تم التسليم في 
                                    <br>
                                    {{format_Date_Time($raw->done_time)}}
                                </span>
                            @endif
                        </td>
                        <td>{{$raw->by_user ? $raw->by_user->email : ''}}</td>
                        <td> 
                            @if($raw->status == 'requested')
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('orders.request_commission.edit', encrypt($raw->id))}}"><i class="fa fa-edit" style="color: #2E86C1"></i> {{__('Edit')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('orders.request_commission.destroy', $raw->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i> {{__('Delete')}}</a></li>
                                        <li><a onclick="return confirm('{{__('Are You Sure ?')}}');" href="{{route('orders.request_commission.pay',encrypt($raw->id))}}"><i class="fa fa-dollar" style="color: #2ECC71"></i> {{__('Pay')}}</a></li>
                                    </ul>
                                </div>  
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
