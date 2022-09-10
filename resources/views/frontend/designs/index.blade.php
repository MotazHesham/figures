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
                                        {{__('Upload Designs')}}
                                    </h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4" style="background: white;width:fit-content;padding:20px">
                            <form action="{{route('design.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf 
                                <input type="file" name="design" id="" required class="form-control">
                                <button type="submit" class="btn btn-success">Upload</button>
                            </form>
                        </div>
                        

                        <div class="row shop-default-wrapper shop-cards-wrapper shop-tech-wrapper mt-4">
                            @foreach ($designs as $key => $design) 
                                <div class="col-xl-4 col-6" >
                                    <div class="card card-product mb-3 product-card-2">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card-image"> 
                                                        <img src="{{ asset($design->design) }}" alt="">
                                                    </div>
                                                </div> 
                                            </div>
                                            
                                        </div>
                                        <div class="card-footer p-3">
                                            <div class="product-buttons">
                                                <div class="row align-items-center">
                                                    <div class="col-2">
                                                        <a href="{{route('design.destroy',$design->id)}}" class="link link--style-3" data-toggle="tooltip" data-placement="top" title="Remove from Designs" >
                                                            <i class="la la-close"></i>
                                                        </a>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            @endforeach
                        </div>

                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $designs->links() }}
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section> 

@endsection 