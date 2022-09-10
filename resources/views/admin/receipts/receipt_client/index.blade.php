@extends('layouts.app')
@section('styles')
    <style>
        td{
            font-size: 15px
        }

    </style>
@endsection

@section('content')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Add Client Receipt')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('receipt.client.add')}}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="phone" required placeholder="Phone Number" onkeyup="searchByPhone(this)">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <a href="{{route('receipt.product','client')}}" class="btn btn-lg btn-rounded btn-success">{{__('Products Of Clients Receipt')}}</a>
        </div>
        <div class="col-lg-6">
            <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-rounded btn-lg btn-info pull-right">{{__('Add Client Receipt')}}</a>
        </div>
    </div>
</div>

<br>

<div class="panel">
    <div class="panel-body">

        <h2 class="text-center">
            {{__('Clients Receipt')}}
        </h2>

        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_receipt_client" action="" method="GET" >
                    <h5 class="text-center">{{__('Search In Client Receipts')}}</h5>
                    <div class="row">
                        {{-- sort by (staff  - order_num - done) --}}
                        <div class="col-md-4">
                            <span>&nbsp;</span>
                            <div class="@isset($staff_id) isset @endisset" style="min-width: 200px;margin-bottom: 10px">
                                <select class="form-control demo-select2" name="staff_id" id="staff_id" onchange="sort_receipt_client()">
                                    <option value="">{{__('Choose Staff')}}</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{$staff->id}}"
                                                @isset($staff_id) @if($staff_id == $staff->id) selected @endif @endisset>
                                                {{$staff->email}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- order_num - done --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <span>&nbsp;</span>
                                    <div class=" @isset($done) isset @endisset" style=" margin-bottom: 10px">
                                        <select class="form-control demo-select2" name="done" id="done" onchange="sort_receipt_client()">
                                            <option value="">{{__('Delivery Status')}}</option>
                                            <option value="0" @isset($done) @if($done == '0') selected @endif @endisset>لم يتم التوصيل</option>
                                            <option value="1" @isset($done) @if($done == '1') selected @endif @endisset>تم التوصيل</option>
                                        </select>
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


                            <div class=" @isset($quickly) isset @endisset" style=" margin-bottom: 10px">
                                <select class="form-control demo-select2" name="quickly" id="quickly" onchange="sort_receipt_client()">
                                    <option value="">{{__('Select')}}</option>
                                    <option value="0" @isset($quickly) @if($quickly == '0') selected @endif @endisset >{{__('Not Quickly')}}</option>
                                    <option value="1" @isset($quickly) @if($quickly == '1') selected @endif @endisset>{{__('Quickly')}}</option>
                                </select>
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
                                    <div class="col-md-4">
                                        <input type="submit" value="{{__('Download')}}" name="download" class="btn btn-info btn-rounded btn-block" >
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-warning btn-rounded btn-block" href="{{route('receipt.client')}}">{{__('Reset')}}</a>
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
                        {{__('Statistics Client Receipts')}}
                        <div>
                            @if($phone == null &&
                                $client_name == null &&
                                $order_num == null &&
                                $staff_id == null &&
                                $done == null &&
                                $quickly == null &&
                                $from == null && $to == null)

                                <span class="text-center badge badge-danger">All</span>

                            @else
                            <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                <span class="text-center badge badge-grey">{{$phone}}</span>
                                <span class="text-center badge badge-grey">{{$client_name}}</span>
                                <span class="text-center badge badge-grey">{{$order_num}}</span>
                                <span class="text-center badge badge-grey">@isset($quickly)
                                        {{$quickly ?
                                            'عاجل'
                                            :
                                            'غير عاجل'}}
                                    @endisset</span>
                                <span class="text-center badge badge-grey">@isset($done)
                                        {{$done ?
                                            'تم التوصيل'
                                            :
                                            'لم يتم التوصيل'}}
                                    @endisset</span>
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
                            <span class="badge badge-{{$generalsetting->deposit}}">% {{($statistics['total_discount'])}} {{__('Discount')}}</span>
                            <span class="badge badge-{{$generalsetting->deposit}}">- {{($statistics['total_deposit'])}} {{__('Deposit')}}</span>
                            <span class="badge badge-{{$generalsetting->total_cost}}">= {{($statistics['total_total'])}}   {{__('Total')}} </span>
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
                    <th>{{__('Products')}}</th>
                    <th>{{__('Total')}}</th>
                    <th>{{__('Note')}}</th>
                    <th></th>
                    <th></th>
                    <th>{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $key => $receipt)
                <tr @if($receipt->quickly) style="background-image:linear-gradient(to {{$generalsetting->quickly_3}},{{$generalsetting->quickly_1}},{{$generalsetting->quickly_2}})" @endif>
                    <td>
                        <br>{{ ($key+1) + ($receipts->currentPage() - 1)*$receipts->perPage() }}
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
                            {{format_Date(strtotime($receipt->created_at))}}
                        </span>
                        <br>
                        <span class="badge badge-{{$generalsetting->date_of_receiving_order}}" style="margin: 2px;">
                            <span>{{__('Date of Receiving Order')}}</span>
                            <br>
                            {{format_Date($receipt->date_of_receiving_order)}}
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
                            foreach($receipt->receipt_client_products as $product){
                                    echo '<span class="badge badge-default">' . $product->description . '</span>';
                                    echo '<span class="badge badge-pink">' . $product->quantity . '</span>';
                                    echo '<span class="badge badge-mint">' . ($product->cost) . '</span><br>';
                            }
                        @endphp
                    </td>
                    <td>
                        <br>
                        @php
                            $discount = round( ( ($receipt->total/100) * $receipt->discount ) , 2);
                        @endphp
                        <span class="badge badge-default">{{ ($receipt->deposit) }}{{__('Deposit')}}</span><br>
                        <span class="badge badge-default">{{ $receipt->discount }}% {{__('Discount')}}</span> <br>
                        <span class="badge badge-default">{{ ($receipt->total) }} {{__('Total')}}</span> <br>
                        <span class="badge badge-success">= {{ ($receipt->total - $discount - $receipt->deposit) }}</span>
                    </td>
                    <td>
                        <br>
                        <?php echo $receipt->note;?>
                    </td>
                    <td>
                        <br>
                        <span class="badge badge-default">{{__('Deliverd Done')}}</span>
                        <br>
                        <label class="switch">
                        <input onchange="update_done(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->done == 1) echo "checked";?> >
                        <span class="slider round"></span></label>

                        <hr>

                        <span class="badge badge-default">{{__('Quickly')}}</span>
                        <br>
                        <label class="switch">
                            <input onchange="update_quickly(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->quickly == 1) echo "checked";?> >
                            <span class="slider round"></span></label>
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
                                <li><a href="{{route('receipt.client.edit', $receipt->id)}}"><i class="fa fa-edit" style="color: #2E86C1"></i> {{__('Add Product')}}</a></li>
                                <li><a target="_blanc" href="{{route('receipt.client.print_receive_money', $receipt->id)}}"><i class="fa fa-print" style="color: #3c5e4a"></i> استلام نقدية</a></li>
                                <li><a target="_blanc" href="{{route('receipt.client.print', $receipt->id)}}"><i class="fa fa-print" style="color: #2ECC71"></i> {{__('Print')}}</a></li>
                                <li><a href="{{route('receipt.client.duplicate', $receipt->id)}}"><i class="fa fa-clone" style="color: hsl(295, 41%, 45%)"></i> {{__('Duplicate')}}</a></li>
                                <li><a onclick="confirm_modal('{{route('receipt.client.destroy', $receipt->id)}}');"><i class="fa fa-trash" style="color: #E74C3C"></i> {{__('Delete')}}</a></li>
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

<div class="modal fade" id="addToCart">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content position-relative">
            <div class="c-preloader">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
            <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div id="addToCart-modal-body">

            </div>
        </div>
    </div>
</div>


@endsection


@section('script')
    <script type="text/javascript">

        function sort_receipt_client(el){
            $('#sort_receipt_client').submit();
        }

        function update_quickly(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.client.quickly') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Quickly Receipt updated successfully');
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }


        function update_done(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('receipt.client.done') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'success');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

    </script>
@endsection
