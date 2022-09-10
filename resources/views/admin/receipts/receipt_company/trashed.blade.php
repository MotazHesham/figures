@extends('layouts.app')

@section('styles')
    <style>
        td{
            font-size: 15px
        }

    </style>
@endsection

@section('content')

<div class="panel">
    <div class="panel-body">

        <h2 class="text-center">
            {{__('Trashed Company Receipts')}}
        </h2>
    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <table class="table table-striped demo-dt-basic mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th>{{__('Order Num')}} </th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Client')}}</th>
                    <th style="width: 200px">{{__('Address')}}</th>
                    <th>{{__('Total Cost')}}</th>
                    <th style="width: 400px">{{__('Description')}}</th>
                    <th>{{__('Deliverd Status')}}</th>
                    <th></th>
                    <th>{{__('')}}</th>
                    <th>{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $key => $receipt)
                    <tr @if($receipt->done)
                            style="background-image:linear-gradient(to {{$generalsetting->done_3}},{{$generalsetting->done_1}},{{$generalsetting->done_2}})"
                        @elseif($receipt->quickly)
                            style="background-image:linear-gradient(to {{$generalsetting->quickly_3}},{{$generalsetting->quickly_1}},{{$generalsetting->quickly_2}})"
                        @endif>
                        <td>
                            <br>{{ ($key+1) }}
                        </td>
                        <td>
                            <br>
                            {{ $receipt->order_num }}
                            @if($receipt->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif
                        </td>
                        <td>
                            <br>
                            <span class="badge badge-{{$generalsetting->date_created}}" style="margin: 2px;">
                                <span>{{__('Date Created')}}</span>
                                <br>
                                {{format_Date_Time(strtotime($receipt->created_at))}}
                            </span>
                            <span class="badge badge-{{$generalsetting->date_of_receiving_order}}" style="margin: 2px;">
                                <span>{{__('Date of Receiving Order')}}</span>
                                <br>
                                {{format_Date($receipt->date_of_receiving_order)}}
                            </span>
                            <span class="badge badge-{{$generalsetting->delivery_date}}" style="margin: 2px;">
                                <span>{{__('Delivery Date')}}</span>
                                <br>
                                {{format_Date($receipt->deliver_date)}}
                            </span>
                        </td>
                        <td>
                            <br>
                            {{ $receipt->client_name }}
                            <br>
                            {{ $receipt->phone }}
                            <br>
                            {{$receipt->phone2}}
                            <br>
                            <span class="badge badge-{{$generalsetting->receipt_type}}">
                                {{__('Receipt Type')}}
                                <br>
                                {{ $receipt->type }}
                            </span>
                        </td>
                        <td>
                            <br>
                            <?php echo nl2br($receipt->address);?>
                        </td>
                        <td>
                            <br>
                            <span class="badge badge-{{$generalsetting->order_cost}}">+ {{ ($receipt->order_cost) }} <span>{{__('Order Cost')}}</span></span>
                            <span class="badge badge-{{$generalsetting->shipping_country_cost}}">+ {{ ($receipt->shipping_country_cost) }} <span>{{__('Shipping Cost')}}</span></span>
                            <span class="badge badge-{{$generalsetting->deposit}}">- {{ ($receipt->deposit) }} <span>{{__('Deposit')}}</span></span>
                            <span class="badge badge-{{$generalsetting->total_cost}}">= {{ ($receipt->need_to_pay) }}</span>
                        </td>

                        <td>
                            <br>
                            <?php echo $receipt->description;?>
                        </td>
                        <td>
                            <br>
                            <span class="badge badge-{{delivery_status_function($receipt->delivery_status)}}">{{ __(ucfirst(str_replace('_', ' ', $receipt->delivery_status))) }}</span>
                            <span class="badge badge-grey">{{__(ucfirst($receipt->payment_status))}}</span>
                            <hr>
                            <span class="badge badge-default">{{__('Done')}}</span>
                            <label class="switch">
                                <input onchange="update_done(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->done == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                        </td>

                        <td>
                            <br>
                            <span class="badge badge-default">{{__('Calling')}}</span>
                            <label class="switch">
                                <input onchange="update_calling(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->calling == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            <br>
                            <hr>
                            <span class="badge badge-default">{{__('Quickly')}}</span>
                            <label class="switch">
                                <input onchange="update_quickly(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->quickly == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                        </td>

                        <td class=" text-center">
                            <br>
                            <span class="badge badge-{{$generalsetting->added_by}}" style="margin: 2px;">
                                <span>{{__('Added By')}}</span>
                                <br>
                                {{ $receipt->Staff ? $receipt->Staff->email : ''}}
                            </span>

                            @if($receipt->DeliveryMan)
                            <span class="badge badge-{{$generalsetting->delivery_man}}" style="margin: 2px;">
                                <span>{{__('Delivery Man')}}</span>
                                <br>
                                {{ $receipt->DeliveryMan->email}}
                            </span>
                            @endif
                            <hr>
                            <span class="badge badge-default">{{__('Note')}}</span>
                            <br>
                            <?php echo $receipt->note;?>
                        </td>

                        <td>
                            <br>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href=" {{route('receipt.company.destroy', $receipt->id)}} " onclick="return confirm('Are you sure?')">  {{__('Restore')}}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



@endsection
