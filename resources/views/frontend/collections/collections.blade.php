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
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Collections')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('collections.index') }}">{{__('Collections')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div class="row mt-5"> 
                            @foreach ($categories as $key => $category)  
                                <div class="col-md-4"> 
                                    <div class="text-center" style="background:white"> 
                                        <a href="{{route('collections.mockups',$category->id)}}">
                                            <div style="height: 170px">
                                                <img src="{{asset($category->banner)}}" class="rounded" style="width: 100%;height:100%" alt="" >
                                            </div> 
                                            <div class="mt-3" style="padding: 15px;">
                                                {{$category->name}}
                                            </div>
                                        </a>
                                    </div> 
                                </div>
                            @endforeach
                        </div> 
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $categories->links() }}
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    

@endsection

@section('script')

@endsection
