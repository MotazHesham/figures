@extends('layouts.app')
@section('styles')
    <style>
        td{
            font-size: 15px
        }

    </style>
@endsection

@section('content')


<div class="row">
    <div class="col-lg-12 pull-right">
        <a href="{{ route('receipt.price_view.add')}}" class="btn btn-lg btn-rounded btn-info pull-right">{{__('Add Price View Receipt')}}</a>
    </div>
</div>

<br>

<div class="panel">
    <div class="panel-body">

        <h2 class="text-center">
            {{__('Price View Receipt')}}
        </h2>

        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_receipt_price_view" action="" method="GET" >
                    <h5 class="text-center">{{__('Search In Price View Receipts')}}</h5>
                    <div class="row">
                        {{-- sort by (staff  - order_num ) --}}
                        <div class="col-md-4">
                            <span>&nbsp;</span>
                            <div class="@isset($staff_id) isset @endisset" style="min-width: 200px;margin-bottom: 10px">
                                <select class="form-control demo-select2" name="staff_id" id="staff_id" onchange="sort_receipt_price_view()">
                                    <option value="">{{__('Choose Staff')}}</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{$staff->id}}"
                                                @isset($staff_id) @if($staff_id == $staff->id) selected @endif @endisset>
                                                {{$staff->email}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- order_num  --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div style=" margin-bottom: 10px">
                                        <input  type="text"
                                                class="form-control @isset($place) isset @endisset"
                                                id="place"
                                                name="place"@isset($place) value="{{ $place }}"
                                                @endisset placeholder="مكان التسليم">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div style=" margin-bottom: 10px">
                                        <input  type="text"
                                                class="form-control @isset($order_num) isset @endisset"
                                                id="order_num"
                                                name="order_num"@isset($order_num) value="{{ $order_num }}"
                                                @endisset placeholder="{{__('Order Num')}}">
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- sort by (phone - client_name) --}}
                        <div class="col-md-4">

                            {{-- phone --}}
                            <div style="min-width: 200px;margin-bottom: 10px">
                                <span>&nbsp;</span>
                                <input  type="text"
                                        class="form-control @isset($phone) isset @endisset"
                                        id="phone"
                                        name="phone"
                                        @isset($phone) value="{{ $phone }}" @endisset
                                        placeholder="{{__('Phone Number')}}">
                            </div>

                            {{-- client_name --}}
                            <div style="min-width: 200px;margin-bottom: 10px">
                                <span>&nbsp;</span>
                                <input  type="text"
                                        class="form-control @isset($client_name) isset @endisset"
                                        id="client_name"
                                        name="client_name"
                                        @isset($client_name) value="{{ $client_name }}" @endisset
                                        placeholder="{{__('Client Name')}}">
                            </div>

                        </div>


                        {{-- sort by (date)--}}
                        <div class="col-md-4">

                            {{-- start date --}}
                            <div class="col-md-12 text-center" style="margin-bottom:10px">
                                <span class="badge badge-default">من تاريخ الأضافة</span>
                                <input type="text" @isset($from) value="{{format_date($from)}}" @endisset  disabled id="from_date_text" class="form-control @isset($from) isset @endisset" style="position: relative;">
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="from" id="from_date" >
                            </div>

                            {{-- end date --}}
                            <div class="col-md-12 text-center" style="margin-bottom:10px">
                                <span class="badge badge-default">الي تاريخ الأضافة</span>
                                <input type="text" @isset($to) value="{{format_date($to)}}" @endisset  disabled id="to_date_text" class="form-control @isset($to) isset @endisset" style="position: relative;">
                                <input type="date" onkeydown="return false" class="form-control my_custom_date_input"  name="to" id="to_date" >
                            </div>

                            <div style="margin-top: 10px" class="text-center">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="submit" value="{{__('Search')}}" name="search" class="btn btn-success btn-rounded btn-block" >
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <input type="submit" value="{{__('Download')}}" name="download" class="btn btn-info btn-rounded btn-block" >
                                    </div> --}}
                                    <div class="col-md-4">
                                        <a class="btn btn-warning btn-rounded btn-block" href="{{route('receipt.price_view')}}">{{__('Reset')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            {{-- statistics --}}
            <div class="col-md-3">
                <div class="filteration-box">
                    <h5 class="text-center">
                        {{__('Statistics Price View Receipts')}}
                        <div>
                            @if($phone == null &&
                                $client_name == null &&
                                $order_num == null &&
                                $place == null &&
                                $staff_id == null &&
                                $from == null && $to == null)

                                <span class="text-center badge badge-danger">All</span>

                            @else
                            <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                <span class="text-center badge badge-grey">{{$phone}}</span>
                                <span class="text-center badge badge-grey">{{$client_name}}</span>
                                <span class="text-center badge badge-grey">{{$order_num}}</span>
                                <span class="text-center badge badge-grey">{{$place}}</span>
                                <span class="text-center badge badge-grey">@php
                                    if($staff_id){
                                        $staff = \App\Models\User::find($staff_id);
                                        if($staff){
                                            echo $staff->email;
                                        }
                                    }
                                @endphp</span>
                                <span class="text-center badge badge-grey">{{$from ? 'من تاريخ الأضافة ' . format_Date($from) : ''}}</span>
                                <span class="text-center badge badge-grey">{{$to ? 'الي تاريخ الأضافة ' . format_Date($to) : ''}}</span>
                            </div>
                            @endif
                        </div>
                    </h5>
                    <div class="row ">
                        <div class="col-md-4">
                            <span class="badge badge-mint">عدد الفواتير {{$receipts->total()}}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="badge badge-{{$generalsetting->total_cost}}">= {{single_price($statistics['total_total'])}}   {{__('Total')}} </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="panel">
    <div class="panel-body">

        <div class="clearfix">
            <div class="pull-right">
                {{ $receipts->appends(request()->input())->links() }}
            </div>
        </div>
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th >{{__('Order Num')}} </th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Client')}}</th>
                    <th style="width: 200px">{{__('Products')}}</th>
                    <th>مدة الأرتباط</th>
                    <th>مدة التوريد</th>
                    <th>الدفع</th>
                    <th>مكان التسليم</th>
                    <th>القيمة (14%)</th>
                    <th></th>
                    <th>{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $key => $receipt)
                    <tr>
                        <td>
                            <br>
                            {{ ($key+1) + ($receipts->currentPage() - 1)*$receipts->perPage() }}
                        </td>
                        <td>
                            <br>
                            {{ $receipt->order_num }}
                        </td>
                        <td>
                            <br>
                            <span class="badge badge-{{$generalsetting->date_created}}" style="margin: 2px;">
                                <span>{{__('Date Created')}}</span>
                                <br>
                                {{format_Date(strtotime($receipt->created_at))}}
                            </span>
                        </td>
                        <td>
                            <br>
                            {{ $receipt->client_name }}
                            <br>
                            {{ $receipt->phone }}
                        </td>

                        <td>
                            <br>
                            @php
                                foreach($receipt->receipt_price_view_products as $product){
                                    echo '<span class="badge badge-default">' . $product->description . '</span>';
                                    echo '<span class="badge badge-pink">' . $product->quantity . '</span>';
                                    echo '<span class="badge badge-mint">' . single_price($product->cost) . '</span><br>';
                                }
                                if($receipt->added_value == 1){
                                    $extra_value = ($receipt->total * 14) / 100;
                                }else{
                                    $extra_value = 0;
                                }
                            @endphp
                            <br>
                            @if($receipt->added_value == 1)
                                <span class="badge badge-info">+ {{ single_price($receipt->total) }} الأجمالي  </span> <br>
                                <span class="badge badge-mint">+ {{ single_price($extra_value) }} (قيمة %14)  </span> <br>
                            @endif
                            <span class="badge badge-success">{{ $receipt->total + $extra_value }} المجموع</span>

                        </td>
                        <td>
                            {{ $receipt->relate_duration }}
                        </td>
                        <td>
                            {{ $receipt->supply_duration }}
                        </td>
                        <td>
                            {{ $receipt->payment }}
                        </td>
                        <td>
                            {{ $receipt->place }}
                        </td>
                        <td>
                            {{ $receipt->added_value == 1 ? 'شامل' : 'غير شامل' }}
                        </td>
                        <td>
                            <br>
                            <span class="badge badge-{{$generalsetting->added_by}}">
                                {{__('Added By')}}
                                <br>
                                {{ $receipt->Staff ? $receipt->Staff->email : ''}}
                            </span>
                        </td>
                        <td>
                            <br>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('receipt.price_view.edit', $receipt->id)}}"><i class="fa fa-edit" style="color: #2E86C1"></i> {{__('Edit')}}</a></li>
                                    <li><a target="_blanc" href="{{route('receipt.price_view.print', $receipt->id)}}"><i class="fa fa-print" style="color: #2ECC71"></i> {{__('Print')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('receipt.price_view.destroy', $receipt->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i> {{__('Delete')}}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $receipts->appends(request()->input())->links() }}
            </div>
        </div>

    </div>
</div>
@endsection


@section('script')
    <script type="text/javascript">

        function sort_receipt_price_view(el){
            $('#sort_receipt_price_view').submit();
        }



    </script>
@endsection
