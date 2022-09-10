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
                                        
                                    </h2>
                                </div> 
                            </div>
                        </div> 

                        <div class="row mt-5"> 
                            @foreach ($mockups as $key => $mockup)  
                                <div class="col-md-4"> 
                                    <div class="text-center" style="background:white"> 
                                        <a href="{{route('collections.start',$mockup->id)}}">
                                            <div style="background:{{json_decode($mockup->colors)[0] ?? ''}}">
                                                @if($mockup->preview_1 != null)
                                                    @php
                                                        $prev_1 = json_decode($mockup->preview_1);
                                                        $image_1 = $prev_1 ? $prev_1->image  : ''; 
                                                    @endphp
                                                    <img src="{{asset($image_1)}}" class="img-fluid"  alt="" >
                                                @endif
                                            </div> 
                                            <div class="mt-3" style="padding: 15px;">
                                                {{$mockup->name}}
                                                <br>
                                                {{single_price($mockup->purchase_price)}} 
                                            </div>
                                        </a>
                                    </div> 
                                </div>
                            @endforeach
                        </div> 
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $mockups->links() }}
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
