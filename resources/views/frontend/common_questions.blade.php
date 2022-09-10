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
                @endif
            </div>

            <div class="col-lg-9">
                <div class="main-content">
                    <!-- Page title -->
                    <div class="page-title mb-5">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-12">
                                <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                    {{__('Common Questions')}}
                                </h2>
                            </div>
                        </div>
                    </div>  
                                
 
                        
                        @foreach($common_questions as $row)
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading{{$row->id}}">
                                        <h4 class="panel-title">
                                            <a class="collapsed text-center" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$row->id}}" aria-expanded="true" aria-controls="collapse{{$row->id}}">
                                                {{$row->question}}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse{{$row->id}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{{$row->id}}">
                                        <div class="panel-body">
                                            <p> <?php echo $row->answer; ?> </p>
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        @endforeach  

                </div>
            </div>
        </div>
    </div>
</section>

@endsection