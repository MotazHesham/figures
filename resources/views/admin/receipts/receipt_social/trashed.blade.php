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
                <h5 class="modal-title" id="exampleModalLabel">{{__('Add '.ucfirst($receipt_type).' Receipt')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('receipt.social.add',$receipt_type)}}" method="GET">
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

<!-- Modal -->
<div class="modal fade" id="designer" tabindex="-1" aria-labelledby="designerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="designerLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('receipt.social.update_playlist_status2')}}" method="POST">
                    @csrf
                    <input type="hidden" name="order_num" id="order_num">
                    <input type="hidden" name="status" id="status" value="design">
                    <div id="select_users">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<br>

<div class="panel">
    <div class="panel-body">

        <h2 class="text-center">
            {{__(ucfirst($receipt_type).' Receipt')}}
        </h2>

    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <table class="table table-striped demo-dt-basic mar-no" cellspacing="0" width="100%">
            <thead>
                <tr class="table-tr-color">
                    <th>#</th>
                    <th >{{__('Order Num')}} </th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Client')}}</th>
                    <th>{{__('Address')}}</th>
                    <th>{{__('Products')}}</th>
                    <th>{{__('Total')}}</th>
                    <th>{{__('Note')}}</th>
                    <th>{{__('Deliverd Status')}}</th>
                    <th></th>
                    <th></th>
                    <th>{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $general_settings = \App\Models\GeneralSetting::first();
                @endphp
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
                        <button class="btn btn-dark" onclick="show_logs('App\\Models\\Receipt_Social','{{ $receipt->id }}','admin.receipts.receipt_social')">{{ $receipt->order_num }}</button>

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
                        <br>
                        {{ $receipt->phone2 }}
                        <br>
                        <span class="badge badge-{{$generalsetting->receipt_type}}">
                            {{__('Receipt Type')}}
                            <br>
                            {{ $receipt->type }}
                        </span>
                    </td>
                    <td>
                        <br>
                        <span class="badge badge-default">{{$receipt->shipping_country_name}}</span> ,
                        <br>
                        <?php echo $receipt->address;?>
                    </td>
                    <td>
                        <br>
                        <a class="btn btn-warning btn-rounded" onclick="view_products({{$receipt->id}})">عرض الكل</a>
                        <br>
                        @php
                            foreach($receipt->receipt_social_products as $product){
                                    echo '<span class="badge badge-default">' . $product->title . '</span>';
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
                        <span class="badge badge-default">{{ ($receipt->extra_commission) }}  {{__('Extra Commission')}}</span> <br>
                        <span class="badge badge-default">{{ ($receipt->shipping_country_cost) }}  {{__('Shipping Cost')}}</span> <br>
                        <span class="badge badge-default">{{ ($receipt->total) }} {{__('Total')}}</span> <br>
                        <span class="badge badge-success">= {{ ($receipt->total + $receipt->extra_commission + $receipt->shipping_country_cost - $receipt->deposit) }}</span>
                    </td>
                    <td>
                        <br>
                        <?php echo $receipt->note;?>
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
                        @if($general_settings->delivery_system == 'wasla' && $receipt->sent_to_wasla)
                            <span class="badge badge-success">  تم الارسال لوصلة</span>
                            <hr>
                        @endif
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

                        <br>
                        @foreach($receipt->socials as $social)
                            <span class="badge badge-dark">{{$social->name}}</span>
                        @endforeach

                        <hr>

                        <span class="badge badge-default">تأكيد</span>
                        <br>
                        <label class="switch">
                            <input onchange="update_confirm(this)" value="{{ $receipt->id }}" type="checkbox" <?php if($receipt->confirm == 1) echo "checked";?> >
                            <span class="slider round"></span></label>

                    </td>
                    <td>
                        <br>
                        <div class="btn-group dropdown">
                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                {{__('Actions')}} <i class="dropdown-caret"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a onclick="return confirm('are you sure to restore ?')" href="{{route('receipt.social.destroy', $receipt->id)}}">  {{__('Restore')}}</a></li>
                            </ul>
                        </div>
                        <hr>
                            @if($receipt->playlist_status == 'design')
                                <span class="badge badge-info" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','design')">
                                    مرحلة الديزاين
                                </span>
                            @elseif($receipt->playlist_status == 'manufacturing')
                                <span class="badge badge-warning" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','manufacturing')">
                                    مرحلة التصنيع
                                </span>
                            @elseif($receipt->playlist_status == 'prepare')
                                <span class="badge badge-danger" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','prepare')">
                                    مرحلة التجهيز
                                </span>
                            @elseif($receipt->playlist_status == 'finish')
                                <span class="badge badge-success" style="cursor: pointer" onclick="change_status('{{$receipt->order_num}}','finish')">
                                    جاهز للتوصيل
                                </span>
                            @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="show-products" tabindex="-1" aria-labelledby="show-productsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content ">
            <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div id="show-products-modal-body">

            </div>
        </div>
    </div>
</div>


@endsection


@section('script')
    <script type="text/javascript">

        function sort_receipt_social(el){
            $('#sort_receipt_social').submit();
        }

        function update_quickly(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.social.quickly') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Confirm Receipt updated successfully');
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }

        function update_confirm(el){
                if(el.checked){
                    var status = 1;
                }
                else{
                    var status = 0;
                }
                $.post('{{ route('receipt.social.confirm') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                    if(data == 1){
                        showAlert('success', 'Quickly Receipt updated successfully');
                        location.reload();
                    }
                    else{
                        showAlert('danger', 'Something went wrong');
                    }
                });
            }

        function change_status(order_num,type){
            $.post('{{ route('receipt.social.playlist_users') }}', {_token:'{{ csrf_token() }}',order_num:order_num}, function(data){
                $('#designer').modal('show');
                $('#designer .modal-body #select_users').html(data);
                $('#designer #order_num').val(order_num);
                $('#designer #status').val(type);
                $('#designer .modal-title').html(order_num);
            });
        }

        function view_products(id){
            $.post('{{ route('receipt.social.view_products') }}', {_token:'{{ csrf_token() }}',id:id}, function(data){
                $('#show-products').modal('show');
                $('#show-products #show-products-modal-body').html(data);
            });
        }

        function update_done(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('receipt.social.done') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
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
