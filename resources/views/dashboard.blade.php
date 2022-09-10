@extends('layouts.app')

@section('content')   

@if( Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)) )

<div class="row">

    <div id="shipping_cost_bar" class="{{ $chart2->options['column_class'] }} panel" style=" padding:15px 0 0 15px;border-radius:15px;padding:30px">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="card-title mb-0">{{ __('Sellers Orders') }}</h4>
                <div class="small text-muted">{{date("F", mktime(0, 0, 0, $month_bar, 10))}} - {{$year_bar}}</div>
            </div>
            <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                <form action="" method="GET" id="form-line">
                    <label class="btn btn-outline-secondary">
                        <select class="form-control" name="year_bar" id="year_bar">
                            @for($i = 2021 ; $i <= 2051 ; $i++)
                                <option value="{{$i}}" @if($year_bar == "{{$i}}") selected @endif>{{$i}}</option>
                            @endfor
                        </select>
                    </label>
                    <label class="btn btn-outline-secondary">
                        <input type="submit" class="btn btn-info btn-rounded" value="fetch">
                    </label>
                    <label class="btn btn-outline-secondary">
                        <select class="form-control" name="month_bar" id="month_bar">
                            <option value="1" @if($month_bar == "1") selected @endif>{{ __('january')}}</option>
                            <option value="2" @if($month_bar == "2") selected @endif>{{ __('february')}}</option>
                            <option value="3" @if($month_bar == "3") selected @endif>{{ __('march')}}</option>
                            <option value="4" @if($month_bar == "4") selected @endif>{{ __('april')}}</option>
                            <option value="5" @if($month_bar == "5") selected @endif>{{ __('may')}}</option>
                            <option value="6" @if($month_bar == "6") selected @endif>{{ __('june')}}</option>
                            <option value="7" @if($month_bar == "7") selected @endif>{{ __('july')}}</option>
                            <option value="8" @if($month_bar == "8") selected @endif>{{ __('august')}}</option>
                            <option value="9" @if($month_bar == "9") selected @endif>{{ __('september')}}</option>
                            <option value="10" @if($month_bar == "10") selected @endif>{{ __('october')}}</option>
                            <option value="11" @if($month_bar == "11") selected @endif>{{ __('november')}}</option>
                            <option value="12" @if($month_bar == "12") selected @endif>{{ __('december')}}</option>
                        </select>
                    </label> 
                </form>
            </div>
        </div>
        {!! $chart2->renderHtml() !!}
    </div>

    @php
        $calculate_commission = calculate_commission(\App\Models\Order::get());
    @endphp
    <div class="col-md-4">
        <div class="panel" style="padding:15px 0 0 15px;border-radius:15px;padding:30px"> 
            <h3 class="text-center">{{__('Sellers Money')}}</h3>
            <div class="dashboard-widget text-center " style="border-radius: 35px;background-color:#c05f72;height:fit-content;color:white;padding:15px;margin-bottom:30px"  class="text-center">
                <div>
                    <div>
                        <i class="fa fa-dollar"></i>
                    </div> 
                </div>
                <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['pending'])}}</span>
                <span class="d-block sub-title">{{ __('pending_commission') }}</span>
            </div> 
            <div class="dashboard-widget text-center" style="border-radius: 35px;background-color:#32879e;height:fit-content;color:white;padding:15px;margin-bottom:30px" class="text-center">
                <div>
                    <i class="fa fa-dollar"></i>
                </div>
                <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['available'])}}</span>
                <span class="d-block sub-title">{{ __('available_commission') }}</span>
            </div> 
            <div class="dashboard-widget text-center" style="border-radius: 35px;background-color:#b9a846;height:fit-content;color:white;padding:15px;margin-bottom:30px" class="text-center">
                <div>
                    <i class="fa fa-dollar"></i>
                </div>
                <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['requested'])}}</span>
                <span class="d-block sub-title">{{ __('requested_commission') }}</span>
            </div> 
            <div class="dashboard-widget text-center" style="border-radius: 35px;background-color:#379e81;height:fit-content;color:white;padding:15px;margin-bottom:30px" class="text-center">
                <div>
                    <i class="fa fa-dollar"></i>
                </div>
                <span class="d-block title heading-3 strong-400">{{single_price($calculate_commission['delivered'])}}</span>
                <span class="d-block sub-title">{{ __('delivered_commission') }}</span>
            </div> 
        </div> 
    </div> 

</div>


    @php
    $top_3_products = \App\Models\Product::orderBy('num_of_sale','desc')->get()->take(6);
    @endphp
    <div class="row" style="margin-top:30px">
        <div class="col-md-6">
            <div class="panel" style=" border-radius:15px;box-shadow:1px 2px 14px #d8d0d0;padding:30px">
                <h3 class="text-center" >{{__('Top 6 Selling Products')}}</h3>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        @foreach($top_3_products as $key => $product)
                            @if($key <= 2)
                                <div class="" style=" margin-bottom:20px">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="{{asset($product->thumbnail_img)}}" alt="" style="border-radius: 50px" height="80" width="80">
                                        </div>
                                        <div class="col-md-8">
                                            <h4 class="text-center">
                                                {{$product->name}} 
                                                <br>
                                                <span class="badge badge-default"> 
                                                    {{__('Num of Sale')}} {{$product->num_of_sale}}
                                                </span>
                                            </h4> 
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endforeach
                    </div>
                    <div class="col-md-6">
                        @foreach($top_3_products as $key => $product)
                            @if($key > 2)
                                <div class="" style=" margin-bottom:20px">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="{{asset($product->thumbnail_img)}}" alt="" style="border-radius: 50px" height="80" width="80">
                                        </div>
                                        <div class="col-md-8">
                                            <h4 class="text-center">
                                                {{$product->name}} 
                                                <br>
                                                <span class="badge badge-default"> 
                                                    {{__('Num of Sale')}} {{$product->num_of_sale}}
                                                </span>
                                            </h4> 
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel" style=" border-radius:15px;box-shadow:1px 2px 14px #d8d0d0; padding:30px">
                <h3 class="text-center">&nbsp;</h3>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel">
                            <div class="pad-top text-center dash-widget" style="border-radius: 20px;box-shadow: 1px 2px 14px #80808061">
                                <p class="text-normal text-main badge badge-default">{{__('Total published products')}}</p>
                                <p class="text-semibold text-3x text-main">{{ \App\Models\Product::where('published', 1)->get()->count() }}</p>
                                <a href="{{ route('products.index') }}" class="btn btn-info btn-rounded mar-top btn-block top-border-radius-no">{{ __('Manage Products') }}</a> 
                            </div>
                        </div>
                        <div class="panel">
                            <div class="pad-top text-center dash-widget" style="border-radius: 20px;box-shadow: 1px 2px 14px #80808061">
                                <p class="text-normal text-main badge badge-default">{{__('Total product sub sub category')}}</p>
                                <p class="text-semibold text-3x text-main">{{ \App\Models\SubSubCategory::all()->count() }}</p>
                                <a href="{{ route('subsubcategories.create') }}" class="btn btn-info btn-rounded mar-top btn-block top-border-radius-no">{{__('Create Sub Sub Category')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel">
                            <div class="pad-top text-center dash-widget" style="border-radius: 20px;box-shadow: 1px 2px 14px #80808061">
                                <p class="text-normal text-main badge badge-default">{{__('Total product sub category')}}</p>
                                <p class="text-semibold text-3x text-main">{{ \App\Models\SubCategory::all()->count() }}</p>
                                <a href="{{ route('subcategories.create') }}" class="btn btn-info btn-rounded mar-top btn-block top-border-radius-no">{{__('Create Sub Category')}}</a>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="pad-top text-center dash-widget" style="border-radius: 20px;box-shadow: 1px 2px 14px #80808061">
                                <p class="text-normal text-main badge badge-default">{{__('Total product category')}}</p>
                                <p class="text-semibold text-3x text-main">{{ \App\Models\Category::all()->count() }}</p>
                                <a href="{{ route('categories.create') }}" class="btn btn-info btn-rounded mar-top btn-block top-border-radius-no">{{__('Create Category')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div> 

    <div class="row" style="margin-top: 30px">
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body text-center dash-widget dash-widget-left">
                    <div class="dash-widget-vertical">
                        <div class="rorate">{{__('SELLERS')}}</div>
                    </div>
                    <br>
                    <p class="text-normal text-main  badge badge-default">{{__('Total sellers')}}</p>
                    <p class="text-semibold text-3x text-main">{{ \App\Models\User::where('user_type','seller')->get()->count() }}</p>
                    <br>
                    <a href="{{ route('sellers.index') }}" class="btn-link">{{__('Manage Sellers')}} <i class="fa fa-long-arrow-right"></i></a>
                    <br>
                    <br>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body text-center dash-widget">
                    <br>
                    <p class="text-normal text-main badge badge-default">{{__('Total approved sellers')}}</p>
                    <p class="text-semibold text-3x text-main">{{ \App\Models\User::where('user_type','seller')->whereHas('seller', function ($query) {
                                                                                                            return $query->where('verification_status', 1);
                                                                                                        })->count() }}</p>
                    <br>
                    <a href="{{ route('sellers.index') }}" class="btn-link">{{__('Manage Sellers')}} <i class="fa fa-long-arrow-right"></i></a>
                    <br>
                    <br>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel">
                <div class="panel-body text-center dash-widget">
                    <br>
                    <p class="text-normal text-main badge badge-default">{{__('Total pending sellers')}}</p>
                    <p class="text-semibold text-3x text-main">{{ \App\Models\User::where('user_type','seller')->whereHas('seller', function ($query) {
                                                                                                            return $query->where('verification_status', 0);
                                                                                                        })->count() }}</p>
                    <br>
                    <a href="{{ route('sellers.index') }}" class="btn-link">{{__('Manage Sellers')}} <i class="fa fa-long-arrow-right"></i></a>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>   
    
    <div class="row"> 
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel">
                        <div class="pad-top text-center dash-widget">
                            <p class="text-semibold text-lg text-main mar-ver">
                                {{__('Home page Settings')}} <br>
                            </p>
                            <br>
                            <a href="{{ route('home_settings.index') }}" class="btn btn-info mar-top btn-block top-border-radius-no">{{__('Click Here')}}</a>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="pad-top text-center dash-widget">
                            <p class="text-semibold text-lg text-main mar-ver">
                                {{__('Policy page Settings')}} <br>
                            </p>
                            <br>
                            <a href="{{route('privacypolicy.index', 'privacy_policy')}}" class="btn btn-info mar-top btn-block top-border-radius-no">{{__('Click Here')}}</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel">
                        <div class="pad-top text-center dash-widget">
                            <p class="text-semibold text-lg text-main mar-ver">
                                {{__('General Settings')}} <br>
                            </p>
                            <br>
                            <a href="{{route('generalsettings.index')}}" class="btn btn-info mar-top btn-block top-border-radius-no">{{__('Click Here')}}</a>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="pad-top text-center dash-widget">
                            <p class="text-semibold text-lg text-main mar-ver">
                                {{__('Shipping Countries Settings')}} <br>
                            </p> 
                            <br>
                            <a href="{{route('countries.index')}}" class="btn btn-info mar-top btn-block top-border-radius-no">{{__('Click Here')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
@section('script')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script> {!! $chart2->renderJs() !!}

@endsection
