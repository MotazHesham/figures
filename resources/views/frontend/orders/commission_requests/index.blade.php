
@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Commission Requests')}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        
                        
                    <table class="table table-striped table-responsive-md" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Date Requested')}}</th>
                                <th>{{__('Payment Method')}}</th>
                                <th>{{__('Transfer Number')}}</th>
                                <th>{{__('Commission Orders')}}</th>
                                <th>{{__('Status')}}</th>
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
                                            <span class="badge badge-mint">
                                                تم التسليم في 
                                                <br>
                                                {{format_Date_Time($raw->done_time)}}
                                            </span>
                                        @endif
                                    </td>
                                    <td> 
                                        <a href="{{route('orders.request_commission.seller_edit', encrypt($raw->id))}}"><i class="fa fa-edit" style="color: #2E86C1">{{__('Edit')}}</i> </a>
                                        <br>
                                        <a onclick="confirm_modal('{{route('orders.request_commission.destroy', $raw->id)}}');"><i class="fa fa-trash" style="color: #E74C3C">{{__('Delete')}}</i> </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    </div>
                </div>
            </div>
        </div>
    </section> 

@endsection 
