@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
@endsection

@section('content')
    <!-- SHOP GRID WRAPPER -->
    <section class="product-details-area gry-bg">
        <div class="container">

            <div class="bg-white">

                <!-- Product gallery and Description -->
                <div class="row no-gutters cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-6 product-gal">
                        <div class="product-gal sticky-top d-flex flex-row-reverse">
                            @if(is_array(json_decode($detailedProduct->photos)) && count(json_decode($detailedProduct->photos)) > 0)
                                <div class="product-gal-img">
                                    <img src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom img-fluid lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(json_decode($detailedProduct->photos)[0]) }}" xoriginal="{{ asset(json_decode($detailedProduct->photos)[0]) }}" />
                                </div>
                                <div class="product-gal-thumb">
                                    <div class="xzoom-thumbs">
                                        @foreach (json_decode($detailedProduct->photos) as $key => $photo)
                                            <a href="{{ asset($photo) }}">
                                                <img onclick="selected_photo('{{$photo}}')" src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom-gallery lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" width="80" data-src="{{ asset($photo) }}"  @if($key == 0) xpreview="{{ asset($photo) }}" @endif>
                                            </a>
                                        @endforeach

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Product description -->
                        <div class="product-description-wrapper">
                            <!-- Product title -->
                            <h1 class="product-title mb-2">
                                {{ __($detailedProduct->name) }}
                            </h1>

                            <div class="row align-items-center my-1">
                                <div class="col-6">
                                    <!-- Rating stars -->
                                    <div class="rating">
                                        @php
                                            $total = 0;
                                            $total += $detailedProduct->reviews->count();
                                        @endphp
                                        <span class="star-rating">
                                            {{ renderStarRating($detailedProduct->rating) }}
                                        </span>
                                        <span class="rating-count ml-1">({{ $total }} {{__('reviews')}})</span>
                                    </div>
                                </div>
                                <div class="col-6 text-right">
                                    <ul class="inline-links inline-links--style-1">
                                        @php
                                            $qty = 0;
                                            if($detailedProduct->variant_product){
                                                foreach ($detailedProduct->stocks as $key => $stock) {
                                                    $qty += $stock->qty;
                                                }
                                            }
                                            else{
                                                $qty = $detailedProduct->current_stock;
                                            }
                                        @endphp
                                        @if ($qty > 0)
                                            <li>
                                                <span class="badge badge-md badge-pill bg-green">{{__('In stock')}}</span>
                                            </li>
                                        @else
                                            <li>
                                                <span class="badge badge-md badge-pill bg-red">{{__('Out of stock')}}</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>


                            <hr>

                            @if(home_price($detailedProduct->id) != home_discounted_price($detailedProduct->id))

                                <div class="row no-gutters mt-4">
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Price')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price-old">
                                            <del >
                                                <span id="product-price-old-for-variant">{{ home_price($detailedProduct->id) }}</span>
                                                <span>/{{ $detailedProduct->unit }}</span>
                                            </del>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters mt-3">
                                    <div class="col-2">
                                        <div class="product-description-label mt-1">{{__('Discount Price')}}:</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="product-price">
                                            <strong id="product-price-for-variant">
                                                {{ home_discounted_price($detailedProduct->id) }}
                                            </strong>
                                            <span class="piece">/{{ $detailedProduct->unit }}</span>
                                        </div>
                                    </div>
                                    @if(Auth::check() && Auth::user()->user_type == 'seller')
                                        <div class="col-2">
                                            <div class="product-description-label">{{__('Commission')}}:</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="product-price">
                                                <strong>
                                                    {{ format_price(convert_price($detailedProduct->unit_price - $detailedProduct->purchase_price))  }}
                                                </strong>
                                                <span class="piece">/{{ $detailedProduct->unit }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                            @else

                                <div class="row no-gutters mt-3">
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Price')}}:</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="product-price">
                                            <strong>
                                                {{ home_discounted_price($detailedProduct->id) }}
                                            </strong>
                                            <span class="piece">/{{ $detailedProduct->unit }}</span>
                                        </div>
                                    </div>

                                @if(Auth::check() && Auth::user()->user_type == 'seller')
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Commission')}}:</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="product-price">
                                            <strong>
                                                {{ single_price($detailedProduct->unit_price - $detailedProduct->purchase_price)  }}
                                            </strong>
                                            <span class="piece">/{{ $detailedProduct->unit }}</span>
                                        </div>
                                    </div>
                                @endif
                                </div>
                            @endif


                            <hr>

                            {{-- =========================================== --}}
                            {{--  attributes And colors --}}

                            @php
                                $has_unit_attribute = false;
                            @endphp

                            <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

                                @if ($detailedProduct->choice_options != null)
                                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)

                                    @php
                                        if(\App\Models\Attribute::find($choice->attribute_id)->name == 'unit'){
                                            $has_unit_attribute = true;
                                        }
                                    @endphp

                                    <div class="row no-gutters">
                                        <div class="col-2">
                                            <div class="product-description-label mt-2 ">{{ \App\Models\Attribute::find($choice->attribute_id)->name }}:</div>
                                        </div>
                                        <div class="col-10">
                                            <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2">
                                                @foreach ($choice->values as $key => $value)
                                                    <li>
                                                        <input type="radio" id="{{ $choice->attribute_id }}-{{ $value }}" name="attribute_id_{{ $choice->attribute_id }}" value="{{ $value }}" @if($key == 0) checked @endif>
                                                        <label for="{{ $choice->attribute_id }}-{{ $value }}">{{ $value }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    @endforeach
                                @endif

                                @if (count(json_decode($detailedProduct->colors)) > 0)
                                    <div class="row no-gutters">
                                        <div class="col-2">
                                            <div class="product-description-label mt-2">{{__('Color')}}:</div>
                                        </div>
                                        <div class="col-10">
                                            <ul class="list-inline checkbox-color mb-1">
                                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                                    <li>
                                                        <input type="radio" id="{{ $detailedProduct->id }}-color-{{ $key }}" name="color" value="{{ $color }}" @if($key == 0) checked @endif>
                                                        <label style="background: {{ $color }};" for="{{ $detailedProduct->id }}-color-{{ $key }}" data-toggle="tooltip"></label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <hr>
                                @endif

                            {{--  attributes And colors --}}
                            {{-- =========================================== --}}

                                @if ($has_unit_attribute)
                                    <input type="hidden" name="quantity" class="form-control input-number text-center" placeholder="1" value="1" min="1" max="10">
                                @else
                                    <!-- Quantity + Add to cart -->
                                    <div class="row no-gutters">
                                        <div class="col-2">
                                            <div class="product-description-label mt-2">{{__('Quantity')}}:</div>
                                        </div>
                                        <div class="col-10">
                                            <div class="product-quantity d-flex align-items-center">
                                                <div class="input-group input-group--style-2 pr-3" style="width: 160px;">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-number" type="button" data-type="minus" data-field="quantity" disabled="disabled">
                                                            <i class="la la-minus"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" name="quantity" class="form-control input-number text-center" placeholder="1" value="1" min="1" max="10">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-number" type="button" data-type="plus" data-field="quantity">
                                                            <i class="la la-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="avialable-amount">(<span id="available-quantity">{{ $qty }}</span> {{__('available')}})</div>
                                            </div>
                                        </div>
                                    </div>

                                @endif

                                <hr>

                                <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Total Price')}}:</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="product-price">
                                            <strong id="chosen_price">

                                            </strong>
                                        </div>
                                    </div>
                                    @if(Auth::check() && Auth::user()->user_type == 'seller')
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Total Commission')}}:</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="product-price">
                                            <strong id="chosen_price_commission">

                                            </strong>
                                        </div>
                                    </div>
                                    @endif
                                </div>


                            </form>

                            <div>
                                @include('admin.partials.error_message')
                            </div>
                            <form id="option-choice-form" enctype="multipart/form-data" action="{{route('cart.addToCart')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
                                <input class="chosen_price_input" type="hidden" name="price">
                                <input class="chosen_variant" type="hidden" name="variant">
                                <input class="chosen_quntity_input" type="hidden" name="quantity" value="1">
                                <input class="chosen_photo" type="hidden" name="chosen_photo" id="chosen_photo" value="{{json_decode($detailedProduct->photos)[0]}}">
                                @if($detailedProduct->special)
                                    @if(auth()->check())
                                        @if($detailedProduct->added_by != 'designer')
                                            <div class="form-box bg-white mt-4">
                                                <div class="form-box-title px-3 py-2">
                                                    {{__('Images To Print in Product')}}
                                                </div>
                                                <div class="form-box-content p-3">
                                                    <div id="product-images">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label>{{__('Main Images')}}</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="file" name="photos[]" id="photos-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                                                <label for="photos-1" class="mw-100 mb-3">
                                                                    <span></span>
                                                                    <strong>
                                                                        <i class="fa fa-upload"></i>
                                                                        {{__('Choose image')}}
                                                                    </strong>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{ __('Add More') }}</button>
                                                    </div>

                                                </div>
                                            </div>

                                            @if(auth()->check() && auth()->user()->user_type == 'seller')
                                                <div class="form-box bg-white mt-4">
                                                    <div class="form-box-title px-3 py-2">
                                                        {{__('Specification')}}
                                                    </div>
                                                    <div class="form-box-content p-3">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label>{{__('PDF')}}</label>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="file" name="pdf" id="file-6" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
                                                                <label for="file-6" class="mw-100 mb-3">
                                                                    <span></span>
                                                                    <strong>
                                                                        <i class="fa fa-upload"></i>
                                                                        {{__('Choose PDF')}}
                                                                    </strong>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label>{{__('Link')}}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control mb-3" id="link" name="link" value="{{old('link')}}">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label>{{__('File Sent To Email')}}</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="switch" style="margin-top:5px;">
                                                                    <input type="checkbox" name="file_sent">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-box bg-white mt-4">
                                                <div class="form-box-title px-3 py-2">
                                                    {{__('Description')}}
                                                </div>
                                                <div class="form-box-content p-3">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>{{__('Description')}}</label>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div class="mb-3">
                                                                <textarea rows="8" cols="50"  name="description">{{old('description')}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                <div class="accordion" id="accordionExample">
                                    <div class="d-table width-100 mt-3">

                                        <div class="d-table-cell">
                                            <!-- Buy Now button -->
                                            @php
                                                if(auth()->check()){
                                                    $loved = \App\Models\Wishlist::where('product_id',$detailedProduct->id)->where('user_id',auth()->user()->id)->first();
                                                }else{
                                                    $loved = false;
                                                }
                                            @endphp
                                            @if ($qty > 0)
                                                @auth
                                                    <button type="submit" class="btn btn-styled btn-alt-base-1 c-white btn-icon-left strong-700 hov-bounce hov-shaddow ml-2 add-to-cart" >
                                                        <i class="la la-shopping-cart"></i>
                                                        {{__('Add to cart')}}
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-styled btn-alt-base-1 c-white btn-icon-left strong-700 hov-bounce hov-shaddow ml-2 add-to-cart"
                                                            onclick="showCheckoutModal()">
                                                        <i class="la la-shopping-cart"></i>
                                                        {{__('Add to cart')}}
                                                    </button>
                                                @endauth
                                                @if( auth()->check() && in_array(auth()->user()->user_type,['seller','staff','admin']) )
                                                    <button class="btn btn-success btn-styled  btn-icon-left strong-700 hov-bounce hov-shaddow buy-now" type="button" data-toggle="collapse" data-target="#add_to_existing_order" aria-expanded="true" aria-controls="add_to_existing_order">
                                                        <i class="fa fa-angle-down"></i>
                                                        {{__('Add To Existing Order')}}
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow buy-now" onclick="addToWishList({{ $detailedProduct->id }})" @if($loved) disabled @endif>
                                                    @if($loved)
                                                        <i class="fa fa-heart" style="color: crimson"></i> {{__('in your wishlist')}}
                                                    @else
                                                        <i class="la la-heart-o"></i> {{__('Add to wishlist')}}
                                                    @endif
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-styled btn-base-3 btn-icon-left strong-700" disabled>
                                                    <i class="la la-cart-arrow-down"></i> {{__('Out of Stock')}}
                                                </button>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                            </form>
                            @auth
                                @include('frontend.orders.add_to_existing_order')
                            @endauth



                            <div class="row no-gutters mt-4">
                                <div class="col-2">
                                    <div class="product-description-label mt-2">{{__('Share')}}:</div>
                                </div>
                                <div class="col-10">
                                    <div id="share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="gry-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 d-none d-xl-block">
                    <div class="seller-info-box mb-3">
                        <div class="sold-by position-relative">
                            @if ($detailedProduct->added_by == 'seller' && \App\Models\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1 && $detailedProduct->user->seller->verification_status == 1)
                                <div class="position-absolute medal-badge">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" viewBox="0 0 287.5 442.2">
                                        <polygon style="fill:#F8B517;" points="223.4,442.2 143.8,376.7 64.1,442.2 64.1,215.3 223.4,215.3 "/>
                                        <circle style="fill:#FBD303;" cx="143.8" cy="143.8" r="143.8"/>
                                        <circle style="fill:#F8B517;" cx="143.8" cy="143.8" r="93.6"/>
                                        <polygon style="fill:#FCFCFD;" points="143.8,55.9 163.4,116.6 227.5,116.6 175.6,154.3 195.6,215.3 143.8,177.7 91.9,215.3 111.9,154.3
                                        60,116.6 124.1,116.6 "/>
                                    </svg>
                                </div>
                            @endif
                            <div class="title">{{__('Sold By')}}</div>
                                {{ env('APP_NAME') }}
                            @php
                                $total = 0;
                                $rating = 0;
                                $reviews = \App\Models\Review::where('product_id',$detailedProduct->id)->get();
                                foreach ($reviews as $key => $review) {
                                    $total += $review->count();
                                    $rating += $review->sum('rating');
                                }
                            @endphp

                            <div class="rating text-center d-block">
                                <span class="star-rating star-rating-sm d-block">
                                    @if ($total > 0)
                                        {{ renderStarRating($rating/$total) }}
                                    @else
                                        {{ renderStarRating(0) }}
                                    @endif
                                </span>
                                <span class="rating-count d-block ml-0">({{ $total }} {{__('customer reviews')}})</span>
                            </div>
                        </div>
                    </div>
                    <div class="seller-top-products-box bg-white sidebar-box mb-3">
                        <div class="box-title">
                            {{__('Top Selling Products From This Seller')}}
                        </div>
                        <div class="box-content">
                            @foreach (filter_products(\App\Models\Product::where('user_id', $detailedProduct->user_id)->orderBy('num_of_sale', 'desc'))->limit(6)->get() as $key => $top_product)
                            <div class="mb-3 product-box-3">
                                <div class="clearfix">
                                    <div class="product-image float-left">
                                        <a href="{{ route('product', $top_product->slug) }}">
                                            <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($top_product->thumbnail_img) }}" alt="{{ __($top_product->name) }}">
                                        </a>
                                    </div>
                                    <div class="product-details float-left">
                                        <h4 class="title text-truncate">
                                            <a href="{{ route('product', $top_product->slug) }}" class="d-block">{{ $top_product->name }}</a>
                                        </h4>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($top_product->rating) }}
                                        </div>
                                        <div class="price-box">
                                            @if(home_base_price($top_product->id) != home_discounted_base_price($top_product->id))
                                                <del class="old-product-price strong-400">{{ home_base_price($top_product->id) }}</del>
                                            @endif
                                            <span class="product-price strong-600">{{ home_discounted_base_price($top_product->id) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="product-desc-tab bg-white">
                        <div class="tabs tabs--style-2">
                            <ul class="nav nav-tabs justify-content-center sticky-top bg-white">
                                <li class="nav-item">
                                    <a href="#tab_default_1" data-toggle="tab" class="nav-link text-uppercase strong-600 active show">{{__('Description')}}</a>
                                </li>
                                @if($detailedProduct->video_link != null)
                                    <li class="nav-item">
                                        <a href="#tab_default_2" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Video')}}</a>
                                    </li>
                                @endif
                                @if($detailedProduct->pdf != null)
                                    <li class="nav-item">
                                        <a href="#tab_default_3" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Downloads')}}</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="#tab_default_4" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Reviews')}}</a>
                                </li>
                            </ul>

                            <div class="tab-content pt-0">
                                <div class="tab-pane active show" id="tab_default_1">
                                    <div class="py-2 px-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mw-100 overflow--hidden" style="float: right;">
                                                    <?php echo $detailedProduct->description; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_default_2">
                                    <div class="fluid-paragraph py-2">
                                        <!-- 16:9 aspect ratio -->
                                        <div class="embed-responsive embed-responsive-16by9 mb-5">
                                            @if ($detailedProduct->video_provider == 'youtube' && $detailedProduct->video_link != null)
                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $detailedProduct->video_link)[1] ?? '' }}"></iframe>
                                            @elseif ($detailedProduct->video_provider == 'dailymotion' && $detailedProduct->video_link != null)
                                                <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] ?? '' }}"></iframe>
                                            @elseif ($detailedProduct->video_provider == 'vimeo' && $detailedProduct->video_link != null)
                                                <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] ?? '' }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <div class="py-2 px-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ asset($detailedProduct->pdf) }}">{{ __('Download') }}</a>
                                            </div>
                                        </div>
                                        <span class="space-md-md"></span>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_4">
                                    <div class="fluid-paragraph py-4">
                                        @foreach ($detailedProduct->reviews as $key => $review)
                                            <div class="block block-comment">
                                                <div class="block-image">
                                                    <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($review->user->avatar_original) }}" class="rounded-circle lazyload">
                                                </div>
                                                <div class="block-body">
                                                    <div class="block-body-inner">
                                                        <div class="row no-gutters">
                                                            <div class="col">
                                                                <h3 class="heading heading-6">
                                                                    <a href="javascript:;">{{ $review->user->name }}</a>
                                                                </h3>
                                                                <span class="comment-date">
                                                                    {{ date('d-m-Y', strtotime($review->created_at)) }}
                                                                </span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="rating text-right clearfix d-block">
                                                                    <span class="star-rating star-rating-sm float-right">
                                                                        @for ($i=0; $i < $review->rating; $i++)
                                                                            <i class="fa fa-star active"></i>
                                                                        @endfor
                                                                        @for ($i=0; $i < 5-$review->rating; $i++)
                                                                            <i class="fa fa-star"></i>
                                                                        @endfor
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="comment-text">
                                                            {{ $review->comment }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if(count($detailedProduct->reviews) <= 0)
                                            <div class="text-center">
                                                {{ __('There have been no reviews for this product yet.') }}
                                            </div>
                                        @endif

                                        @if(Auth::check())
                                            @php
                                                $commentable = false;
                                            @endphp
                                            @foreach ($detailedProduct->orderDetails as $key => $orderDetail)
                                                @if($orderDetail->order != null && $orderDetail->order->user_id == Auth::user()->id && $orderDetail->delivery_status == 'delivered' && \App\Models\Review::where('user_id', Auth::user()->id)->where('product_id', $detailedProduct->id)->first() == null)
                                                    @php
                                                        $commentable = true;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if ($commentable)
                                                <div class="leave-review">
                                                    <div class="section-title section-title--style-1">
                                                        <h3 class="section-title-inner heading-6 strong-600 text-uppercase">
                                                            {{__('Write a review')}}
                                                        </h3>
                                                    </div>
                                                    <form class="form-default" role="form" action="{{ route('reviews.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="" class="text-uppercase c-gray-light">{{__('Your name')}}</label>
                                                                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" disabled required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="" class="text-uppercase c-gray-light">{{__('Email')}}</label>
                                                                    <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control" required disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="c-rating mt-1 mb-1 clearfix d-inline-block">
                                                                    <input type="radio" id="star5" name="rating" value="5" required/>
                                                                    <label class="star" for="star5" title="Awesome" aria-hidden="true"></label>
                                                                    <input type="radio" id="star4" name="rating" value="4" required/>
                                                                    <label class="star" for="star4" title="Great" aria-hidden="true"></label>
                                                                    <input type="radio" id="star3" name="rating" value="3" required/>
                                                                    <label class="star" for="star3" title="Very good" aria-hidden="true"></label>
                                                                    <input type="radio" id="star2" name="rating" value="2" required/>
                                                                    <label class="star" for="star2" title="Good" aria-hidden="true"></label>
                                                                    <input type="radio" id="star1" name="rating" value="1" required/>
                                                                    <label class="star" for="star1" title="Bad" aria-hidden="true"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-sm-12">
                                                                <textarea class="form-control" rows="4" name="comment" placeholder="{{__('Your review')}}" required></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="text-right">
                                                            <button type="submit" class="btn btn-styled btn-base-1 btn-circle mt-4">
                                                                {{__('Send review')}}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="my-4 bg-white p-3">
                        <div class="section-title-1">
                            <h3 class="heading-5 strong-700 mb-0">
                                <span class="mr-4">{{__('Related products')}}</span>
                            </h3>
                        </div>
                        <div class="caorusel-box arrow-round gutters-5">
                            <div class="slick-carousel" data-slick-items="3" data-slick-xl-items="2" data-slick-lg-items="3"  data-slick-md-items="2" data-slick-sm-items="1" data-slick-xs-items="1"  data-slick-rows="2">
                                @foreach (filter_products(\App\Models\Product::where('subcategory_id', $detailedProduct->subcategory_id)->where('id', '!=', $detailedProduct->id))->limit(10)->get() as $key => $related_product)
                                <div class="caorusel-card my-1">
                                    <div class="row no-gutters product-box-2 align-items-center">
                                        <div class="col-5">
                                            <div class="position-relative overflow-hidden h-100">
                                                <a href="{{ route('product', $related_product->slug) }}" class="d-block product-image h-100 text-center">
                                                    <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($related_product->thumbnail_img) }}" alt="{{ __($related_product->name) }}">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-7 border-left">
                                            <div class="p-3">
                                                <h2 class="product-title mb-0 p-0 text-truncate">
                                                    <a href="{{ route('product', $related_product->slug) }}">{{ __($related_product->name) }}</a>
                                                </h2>
                                                <div class="star-rating star-rating-sm mb-2">
                                                    {{ renderStarRating($related_product->rating) }}
                                                </div>
                                                <div class="clearfix">
                                                    <div class="price-box float-left">
                                                        @if(home_base_price($related_product->id) != home_discounted_base_price($related_product->id))
                                                            <del class="old-product-price strong-400">{{ home_base_price($related_product->id) }}</del>
                                                        @endif
                                                        <span class="product-price strong-600">{{ home_discounted_base_price($related_product->id) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Any query about this product')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title" value="{{ $detailedProduct->name }}" placeholder="Product Name" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required placeholder="Your Question">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{__('Cancel')}}</button>
                        <button type="submit" class="btn btn-base-1 btn-styled">{{__('Send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
    		$('#share').jsSocials({
    			showLabel: false,
                showCount: false,
                shares: [ "whatsapp"]
    		});

    	});

        function selected_photo(photo){
            $('#chosen_photo').val(photo);
            console.log(photo);
        }


        function CopyToClipboard(containerid) {
            if (document.selection) {
                var range = document.body.createTextRange();
                range.moveToElementText(document.getElementById(containerid));
                range.select().createTextRange();
                document.execCommand("Copy");

            } else if (window.getSelection) {
                var range = document.createRange();
                document.getElementById(containerid).style.display = "block";
                range.selectNode(document.getElementById(containerid));
                window.getSelection().addRange(range);
                document.execCommand("Copy");
                document.getElementById(containerid).style.display = "none";

            }
            showFrontendAlert('success', 'Copied');
        }

        function show_chat_modal(){
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }




    </script>
@endsection
