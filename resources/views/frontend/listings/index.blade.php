@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">

                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @elseif(Auth::user()->user_type == 'designer')
                            @include('frontend.inc.designer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">

                        <!-- Page title -->
                        <div class="page-title mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Listings')}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-5">
                            <div class="col-md-4 ">
                                <div class="dashboard-widget text-center red-widget text-white mt-4 c-pointer">
                                    <i class="fa fa-dollar"></i>
                                    <span class="d-block title heading-3 strong-400">{{ $num_of_sale }}</span>
                                    <span class="d-block sub-title">عدد المبيعات</span>

                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="dashboard-widget text-center blue-widget text-white mt-4 c-pointer">
                                    <i class="fa fa-dollar"></i>
                                    <span class="d-block title heading-3 strong-400">{{single_price($total_profit_where_not_done)}}</span>
                                    <span class="d-block sub-title">الأرباح قيدة التنفيذ</span>

                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer">
                                    <i class="fa fa-dollar"></i>
                                    <span class="d-block title heading-3 strong-400">{{single_price($total_profit_where_done)}}</span>
                                    <span class="d-block sub-title">الأرباح المتاحة للتوريد</span>

                                </div>
                            </div>  
                        </div>


                        <!-- My Store items -->

                        <div class="products-box-bar p-3 bg-white">
                            <div class="row sm-no-gutters gutters-5">
                            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th> 
                                        <th>{{__('Design Name')}}</th> 
                                        <th>{{__('Profit')}}</th> 
                                        <th>الأرباح قيدة التنفيذ</th> 
                                        <th>الأرباح المتاحة للتوريد</th> 
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
                                                <img class="img-fluid lazyload" height="70" width="70" src="{{ asset($listing_image->image ?? '') }}" alt="{{ __($listing['design_name']) }}">
                                            </td>
                                            <td>
                                                {{$listing['design_name']}}
                                            </td>
                                            <td>
                                                {{single_price($listing['profit'])}}
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
                                            <td>
                                                @if($listing['status'] == 'pending')
                                                    <i class="fa fa-pause-circle" style="font-size: 30px; color: rgb(71, 121, 179);"></i> {{__('Pending')}}
                                                @elseif($listing['status'] == 'accepted')
                                                    <i class="fa fa-check-circle" style="font-size: 30px; color: rgb(55, 189, 55);"></i>  {{__('Accepted')}}
                                                @elseif($listing['status'] == 'refused')
                                                    <i class="fa fa-times-circle" style="font-size: 30px; color: rgb(180, 82, 82);"></i> {{__('Refused')}}
                                                @endif
                                            </td>
                                            <td>  
                                                @if($listing['status'] == 'pending')
                                                    <a href="{{route('listings.edit', encrypt($listing['id']))}}"><i class="fa fa-edit"></i></a>
                                                    <a onclick="confirm_modal('{{route('listings.destroy', $listing['id'])}}');"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                            </div>
                        </div> 

                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $listings->links() }}
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section> 

@endsection

@section('script')
    <script type="text/javascript"> 
    </script>
@endsection
