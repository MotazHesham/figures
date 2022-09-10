@php
    if(auth()->user()->user_type == 'delivery_man'){
        $extend = 'delivery_man.app';
    }else{
        $extend = 'layouts.app';
    }
@endphp

@extends($extend)

@section('styles')
    <style>
        td{
            font-size: 15px
        }
        .order-card{
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
            overflow: hidden;
            max-width: 700px;
            padding:15px;
            border-radius:14px;
            box-shadow: 2px 1px 19px #48484863;
        }
        .order-card-left-side{
            background-image:linear-gradient(#348282,#3580b3);
            border-radius:14px;
            padding:15px;
        }
        .order-card:hover {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
        .order-card-actions{
            position:absolute;
            right: 0;
            top: -30px;
            visibility: hidden;
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
        }
        .order-card-actions a{
            background-color: rgb(113, 95, 95);
            color: #fff;
            border-radius: 25px;
            padding:10px;
            margin: 3px;
        }
        .order-card-actions i{
            color:rgb(62, 170, 62)
        }
        .badge-default {
            background-color: #e9eeef;
            color: #7a878e;
        }
        .address-scrollable::-webkit-scrollbar {
            width: 5px;
        }

        .address-scrollable::-webkit-scrollbar-track {
            background:rgba(184, 34, 34, 0);
            border-radius: 10px;
        }

        .address-scrollable::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background: rgba(159, 53, 53, 0.8);
        }
        .address-scrollable::-webkit-scrollbar-thumb:hover {
            background: black;
        }
    </style>
@endsection

@section('content')

@php

    $title = '';
    $title_send = '';
    $back_type = '';
    $next_type = '';
    if($type == 'design'){
        $title = 'الديزانر';
        $title_send = 'أرسال لقائمة التصنيع';
        $next_type = 'manufacturing';
        $title_back = 'أرجاع للشركة';
        $back_type = 'pending';
    }elseif($type == 'manufacturing'){
        $title = 'التصنيع';
        $title_send = 'أرسال لقائمة التجهيز';
        $next_type = 'prepare';
        $title_back = 'أرجاع لقائمة الديزانر';
        $back_type = 'design';
    }elseif($type == 'prepare'){
        $title = 'التجهيز';
        $title_send = 'أرسال للشحن';
        $next_type = 'finish';
        $title_back = 'أرجاع لقائمة التصنيع';
        $back_type = 'manufacturing';
    }

@endphp
<div class="panel">
    <div class="panel-body">

        <h2 class="text-center">
            <a class="btn btn-info btn-rounded" href="{{route('alerts.history')}}">السجل</a> قائمة {{$title}}
        </h2>


        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_delivery_orders" action="" method="GET" >
                    <h5 class="text-center">البحث في قائمة {{$title}}</h5>
                    <div class="row">
                        {{-- sort by (delivery_man ) --}}
                        <div class="col-md-4">
                            <span>&nbsp;</span>
                            <div class="@isset($user_id) isset @endisset" style="min-width: 200px;margin-bottom: 10px">
                                <select class="form-control demo-select2" name="user_id" id="user_id" onchange="sort_delivery_orders()">
                                    <option value="">{{__('Choose User')}}</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"
                                                @isset($user_id) @if($user_id == $user->id) selected @endif @endisset>
                                                {{$user->email}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        {{-- sort by (order_num) --}}
                        <div class="col-md-3">
                            <span>&nbsp;</span>
                            <div style=" margin-bottom: 10px">
                                <input  type="text"
                                        class="form-control @isset($order_num) isset @endisset"
                                        id="order_num"
                                        name="order_num"@isset($order_num) value="{{ $order_num }}"
                                        @endisset placeholder="{{__('Order Num')}}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <span>&nbsp;</span>
                            <div style=" margin-bottom: 10px">
                                <input  type="text"
                                        class="form-control @isset($description) isset @endisset"
                                        id="description"
                                        name="description"@isset($description) value="{{ $description }}"
                                        @endisset placeholder="{{__('Description')}}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div style=" margin-top: 18px">
                                <input type="submit" class="form-control btn btn-success" value="بحث" name="" id="">
                            </div>
                        </div>

                    </div>
                </form>
            </div>


            {{-- statistics --}}
            <div class="col-md-3">
                <div class="filteration-box">
                    <h5 class="text-center">
                        <div>
                            @if(
                                $order_num == null &&
                                $user_id == null )

                                <span class="text-center badge badge-danger">All</span>

                            @else
                            <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                <span class="text-center badge badge-grey">{{$order_num}}</span>
                                <span class="text-center badge badge-grey">@php
                                    if($user_id){
                                        $user = \App\Models\User::find($user_id);
                                        if($user){
                                            echo $user->email;
                                        }
                                    }
                                @endphp</span>
                            </div>
                            @endif
                        </div>
                    </h5>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="panel">
    <div class="panel-body">

        <div class="clearfix">
            <div class="pull-right">
                {{ $items->appends(request()->input())->links() }}
            </div>
        </div>

        <div class="row">
            @foreach ($items as $key => $item)
                @php
                    if($type == 'design'){
                        $authenticated = $item['designer_id'];
                    }elseif($type == 'manufacturing'){
                        $authenticated = $item['manifacturer_id'];
                    }elseif($type == 'prepare'){
                        $authenticated = $item['preparer_id'];
                    }
                @endphp
                {{-- order card --}}
                <div class="col-md-4">
                    <div class="card order-card" data-id="{{$key}}" id="order-card-{{$key}}" style="margin-bottom:30px">
                        {{-- code --}}
                        <div class=" order-card-left-side text-center mb-3" style="color: white;margin-bottom:20px;@if($item['quickly'] == 1) background-image: linear-gradient(#9f1b2e,#1a1313) @endif">
                            <div class="row">
                                <div class="col-xs-4 col-md-4">
                                    {{$item['order_num']}}
                                </div>
                                <div class="col-xs-8 col-md-8">
                                    <span class="badge badge-default">{{__('Added By')}}</span>
                                    <br>
                                    <span class="badge badge-{{$generalsetting->delivery_man}}" style="margin: 2px;">
                                        {{ $item['added_by']}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        {{-- order info --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div style="text-align: end">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div>
                                                <span class="badge badge-default">{{__('Date Created')}}</span><br>
                                                {{ format_Date(strtotime($item['created_at'])) }}
                                                <br>
                                                <span class="badge badge-default">تاريخ المرحلة</span><br>
                                                {{ format_Date_Time(strtotime($item['send_to_playlist_date'])) }}
                                            </div>
                                            <br>
                                            <span class="badge badge-default">{{__('Note')}}</span>
                                            <br>
                                            <div style="color: rgb(110, 110, 206);
                                                        height: 60px;
                                                        overflow-y: scroll;
                                                        box-shadow: 0px 4px 5px #bd808063;
                                                        border-radius: 7px;" class="address-scrollable"><?php echo $item['note']; ?></div>
                                        </div>
                                        <div class="col-md-8">
                                            <br>
                                            <span class="badge badge-default">{{__('Description')}}</span>
                                            <br>
                                            <div style="color: rgb(110, 110, 206);
                                                        height: 100px;
                                                        overflow-y: scroll;
                                                        box-shadow: 0px 4px 5px #bd808063;
                                                        border-radius: 7px;" class="address-scrollable"><?php echo $item['description']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- action Buttons --}}
                        <div class="order-card-actions" id="order-card-actions-{{$key}}">

                            <a style="cursor: pointer;background:#45B39D"  onclick="show_details('{{$item['order_num']}}')" title="{{__('Order Details')}}">أظهار الصور</a>
                            @if($type == 'design')
                                <form action="{{ route('playlist.print') }}" method="post" style="display: inline">
                                    @csrf
                                    <input type="hidden" name="order_num" value="{{$item['order_num']}}" id="">
                                    <button type="submit" class="btn btn-success btn-rounded">  {{__('Print')}}</button>
                                </form>
                            @endif
                            @if(auth()->user()->user_type == 'admin' || $authenticated == auth()->user()->id)
                                <a style="cursor: pointer;background:#E59866"
                                    onclick="change_status('{{$item['order_num']}}','{{$next_type}}','send')"
                                    title="">
                                    {{$title_send}}
                                </a>
                                <a style="cursor: pointer;background:#EC7063"
                                    onclick="change_status('{{$item['order_num']}}','{{$back_type}}','back')"
                                    title="">
                                    {{$title_back}}
                                </a>
                            @endif

                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="clearfix">
            <div class="pull-right">
                {{ $items->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="playlist_modal" tabindex="-1" role="dialog" aria-labelledby="playlist_modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>


@endsection
@section('script')
    <script type="text/javascript">

        $(document).ready(function () {
            $('.order-card').hover(function(){
                var id = $(this).data('id');
                $('#order-card-actions-'+id).css('top','10px');
                $('#order-card-actions-'+id).css('visibility','visible');
            });
            $('.order-card').mouseleave(function(){
                var id = $(this).data('id');
                $('#order-card-actions-'+id).css('top','-30px');
                $('#order-card-actions-'+id).css('visibility','hidden');
            });
        });

        function sort_delivery_orders(el){
            $('#sort_delivery_orders').submit();
        }

        function change_status(order_num,type,condition){
            $.post('{{ route('playlist.update_playlist_status') }}', {_token:'{{ csrf_token() }}', order_num:order_num, status:type, condition:condition}, function(data){
                showAlert('success', 'تم الأرسال');
                location.reload();
            });
        }

        function show_details(order_num){
            $.post('{{ route('playlist.show') }}', {_token:'{{ csrf_token() }}', order_num:order_num}, function(data){
                $('#playlist_modal').modal('show');
                $('#playlist_modal #modal-content').html(data);
            });
        }

    </script>
@endsection
