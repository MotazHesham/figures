@extends('layouts.app')

@section('content')


<div class="panel"> 
    <div class="panel-body">

        <h2 class="text-center">
            التصاميم  
        </h2>

        <div class="row">

            <div class="col-md-9">
                <form class="filteration-box" id="sort_listings" action="" method="GET" >  

                <h5 class="text-center">البحث في التصاميم</h5>  
                    <div class="row">
                        
                        <div class="col-md-4"> 
                            <span>&nbsp;</span>
                            <div class="@isset($designer_id) isset @endisset" style="min-width: 160px;margin-bottom: 10px">
                                <select class="form-control demo-select2" name="designer_id" id="designer_id" onchange="sort_listings()">
                                    <option value="">{{__('Choose Designer')}}</option> 
                                    @foreach($designers as $designer)
                                        <option value="{{$designer->id}}"
                                                @isset($designer_id) @if($designer_id == $designer->id) selected @endif @endisset>
                                                {{$designer->email}}
                                        </option>
                                    @endforeach 
                                </select>
                            </div>  
                        </div>  
                        <div class="col-md-4"> 
                            <span>&nbsp;</span>
                            <div class="@isset($status) isset @endisset" style="min-width: 160px;margin-bottom: 10px">
                                <select class="form-control demo-select2" name="status" id="status" onchange="sort_listings()">
                                    <option value="">{{__('Choose Status')}}</option>  
                                        <option value="pending" @isset($status) @if($status == 'pending') selected @endif @endisset>قيد الأنتظار</option> 
                                        <option value="accepted" @isset($status) @if($status == 'accepted') selected @endif @endisset>مقبول</option> 
                                        <option value="refused" @isset($status) @if($status == 'refused') selected @endif @endisset>مرفوض</option> 
                                </select>
                            </div>  
                        </div>  
                        <div class="col-md-4">   
                            <div style=" margin-bottom: 10px">
                                <span>&nbsp;</span>
                                <input  type="text" 
                                        class="form-control @isset($design_name) isset @endisset" 
                                        id="design_name" 
                                        name="design_name"
                                        @isset($design_name) value="{{ $design_name }}" @endisset 
                                        placeholder="اسم التصميم">
                            </div>  
                        </div>
                    </div>

                </form> 
            </div>


            {{-- statistics --}}
            <div class="col-md-3">
                <div class="filteration-box"> 
                    <h5 class="text-center">
                        {{__('Statistics listings')}}
                        <div> 
                            @if( $designer_id == null && $status == null && $design_name == null)

                                <span class="text-center badge badge-danger">All</span>

                            @else
                                <div style="border:black 1px solid;border-radius:15px;padding:5px">
                                    <span class="text-center badge badge-grey">{{$status}}</span>
                                    <span class="text-center badge badge-grey">{{$design_name}}</span> 
                                    <span class="text-center badge badge-grey">@php
                                        if($designer_id){
                                            $designer = \App\Models\User::find($designer_id);
                                            if($designer){ 
                                                echo $designer->email;
                                            }
                                        }
                                    @endphp</span>
                                </div>
                            @endif
                        </div>
                    </h5> 
                    <div class="row "> 
                        <div class="col-md-4">
                            <span class="badge badge-mint">عدد التصاميم {{$listings->total()}}</span> 
                        </div>
                        <div class="col-md-6"> 
                            <span>الأرباح قيدة التنفيذ</span>
                            <br>
                            <span class="badge badge-info">x{{ $num_of_sale_where_not_done }} </span> 
                            <span class="badge badge-success"> {{single_price($total_profit_where_not_done)}} </span> 
                            <hr>
                            <span>الأرباح المتاحة للتوريد</span>
                            <br>
                            <span class="badge badge-info">x{{ $num_of_sale_where_done }} </span>
                            <span class="badge badge-success"> {{single_price($total_profit_where_done)}} </span>
                        </div>
                    </div> 
                </div>
            </div>

        </div>

    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel"> 
    <div class="panel-body"> 
        <div class="clearfix">
            <div class="pull-right">
                {{ $listings->appends(request()->input())->links() }}
            </div>
        </div>
        <table class="table table-striped table-bordered " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Design Name')}}</th>
                    <th>{{__('Mockup')}}</th>
                    <th>{{__('Designer')}}</th>
                    <th>{{__('Image')}}</th>
                    <th>الأرباح قيدة التنفيذ</th> 
                    <th>الأرباح المتاحة للتوريد</th> 
                    <th>{{__('Profit')}}</th>
                    <th>{{__('Status')}}</th> 
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listings as $key => $listing)
                    @php
                        $listing_image = $listing['listing_images'][0] ?? '';
                    @endphp
                    <tr>
                        <td>
                            {{ ($key+1) + ($listings->currentPage() - 1)*$listings->perPage() }}
                        </td>
                        <td>{{$listing['design_name']}}</td>
                        <td>{{$listing['mockup'] }}</td>
                        <td>{{$listing['user'] }}</td>
                        <td>
                            <img class="img-fluid lazyload" height="70" width="70" src="{{ asset($listing_image->image ?? '') }}" alt="{{ __($listing['design_name']) }}">
                            <button onclick="preview_designs({{$listing['id']}})" class="btn btn-info btn-rounded">{{__('View All')}}</a>
                        </td>
                        <td>
                            @if($listing['status'] == 'accepted')
                                <span class="badge badge-info">x{{$listing['count_where_not_done']}}</span> <br>
                                <span class="badge badge-success">= {{ single_price($listing['profit_where_not_done']) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($listing['status'] == 'accepted')
                                <span class="badge badge-info">x{{$listing['count_where_done']}}</span> <br>
                                <span class="badge badge-success">= {{ single_price($listing['profit_where_done']) }}</span>
                            @endif
                        </td>
                        <td>{{single_price($listing['profit'])}}</td>
                        <td>
                            @if($listing['status'] == 'pending')
                                <i class="fa fa-pause-circle" style="font-size: 30px; color: rgb(71, 121, 179);"></i> {{__('Pending')}}
                            @elseif($listing['status'] == 'accepted')
                                <i class="fa fa-check-circle" style="font-size: 30px; color: rgb(55, 189, 55);"></i>  {{__('Accepted')}}
                            @elseif($listing['status'] == 'refused')
                                <i class="fa fa-times-circle" style="font-size: 30px; color: rgb(180, 82, 82);"></i> {{__('Refused')}}
                                <br>
                                <a href="{{route('admin.listings.trash',$listing['id'])}}" class="btn btn-warning" > أخفاء من القايمة</a>
                            @endif
                        </td>
                        <td> 
                            @if($listing['status'] == 'pending')
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{route('admin.listings.accept',$listing['id'])}}">{{__('Accept')}}</a></li>
                                        <li><a href="{{route('admin.listings.refuse',$listing['id'])}}">{{__('Refuse')}}</a></li>
                                    </ul>
                                </div> 
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $listings->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="preview_designs">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content position-relative">
            <div class="c-preloader">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
            <div id="preview_designs-modal">

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
	<script type="text/javascript">
        function sort_listings(el){
            $('#sort_listings').submit();
        } 
        function preview_designs(id){
            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }
            $('#preview_designs-modal').html(null);
            $('#preview_designs').modal();
            $('.c-preloader').show();
            $.post('{{ route('admin.listings.preview_designs') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
                $('.c-preloader').hide();
                $('#preview_designs-modal').html(data);
            });
        }
    </script>
@endsection
