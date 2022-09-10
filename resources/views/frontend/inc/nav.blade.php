<div class="header bg-white">
    <!-- Top Bar -->
    <div class="top-navbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col">
                    <ul class="inline-links d-lg-inline-block d-flex justify-content-between" style="padding-top: 12px;">
                        <li class="dropdown" id="lang-change">
                            @php
                                if(Session::has('locale')){
                                    $locale = Session::get('locale', Config::get('app.locale'));
                                }
                                else{
                                    $locale = 'eg';
                                }
                            @endphp
                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                <img src="{{ asset('frontend/images/placeholder.jpg') }}" height="11" data-src="{{ asset('frontend/images/icons/flags/'.$locale.'.png') }}" class="flag lazyload" alt="{{ \App\Models\Language::where('code', $locale)->first()->name }}" height="11"><span class="language">{{ \App\Models\Language::where('code', $locale)->first()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach (\App\Models\Language::all() as $key => $language)
                                    <li class="dropdown-item @if($locale == $language) active @endif">
                                        <a href="#" data-flag="{{ $language->code }}"><img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset('frontend/images/icons/flags/'.$language->code.'.png') }}" class="flag lazyload" alt="{{ $language->name }}" height="11"><span class="language">{{ $language->name }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="dropdown" id="currency-change">
                            @php
                                if(Session::has('currency_code')){
                                    $currency_code = Session::get('currency_code');
                                }
                                else{
                                    $currency_code = \App\Models\Currency::findOrFail(\App\Models\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
                                }
                            @endphp
                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                {{ \App\Models\Currency::where('code', $currency_code)->first()->name }} {{ (\App\Models\Currency::where('code', $currency_code)->first()->symbol) }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach (\App\Models\Currency::where('status', 1)->get() as $key => $currency)
                                    <li class="dropdown-item @if($currency_code == $currency->code) active @endif">
                                        <a href="" data-currency="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->symbol }})</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="col-5 text-right d-none d-lg-block">
                    <ul class="inline-links">
                        @auth
                            <li class="dropdown dropleft" style="padding: 5px;">
                                <a href="" class="dropdown-toggle my-custom-toggle"  data-toggle="dropdown">
                                    @if(auth()->user()->avatar_original)
                                        <span style="  font-size: 14px;  font-weight: bolder;">{{auth()->user()->name}}</span>
                                        <img height="40" width="40" src="{{ asset(auth()->user()->avatar_original) }} " style="border-radius: 50px">
                                    @else
                                        <span style="  font-size: 14px;  font-weight: bolder;">{{auth()->user()->name}}</span>
                                        <img height="40" width="40" src="{{asset('frontend/images/user.png')}}" style="border-radius: 50px">
                                    @endif
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item">
                                        <a href="{{ route('orders.track') }}" class="top-bar-item">{{__('Track Order')}}</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="{{ route('home') }}" class="top-bar-item">{{__('Home')}}</a>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    @php
                                        $conversation_recieved = \App\Models\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', 0)->get();
                                    @endphp
                                    <li class="dropdown-item">
                                        <a href="{{ route('conversations.index') }}" class="top-bar-item">
                                            {{__('Messages')}}
                                            @if (count($conversation_recieved) > 0)
                                                <span class="ml-2" style="color:green"><strong>({{ count($conversation_recieved) }})</strong></span>
                                            @endif
                                        </a>
                                    </li>
                                    @if(Auth::user()->user_type == 'designer')
                                        <li class="dropdown-item">
                                            <a href="{{ route('my_store.index',Auth::user()->store_name) }}" class="top-bar-item">{{__('My Store')}} </a>
                                        </li>

                                        <li class="dropdown-item">
                                            <a href="{{ route('listings.index') }}" class="top-bar-item">{{__('Listings')}}</a>
                                        </li>

                                        <li class="dropdown-item">
                                            <a href="{{ route('collections.index') }}" class="top-bar-item">{{__('Start Design')}}</a>
                                        </li>
                                    @endif
                                    <li class="dropdown-item">
                                        <a href="{{ route('user.orders.index') }}" class="top-bar-item">{{__('Orders')}}</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="{{ route('wishlists.index') }}" class="top-bar-item">{{__('Wishlist')}}</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="{{ route('calender') }}" class="top-bar-item">{{__('Calender')}}</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="{{ route('profile') }}" class="top-bar-item">{{__('Profile')}}</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a href="{{ route('logout') }}" class="top-bar-item">{{__('Logout')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('orders.track') }}" class="top-bar-item">{{__('Track Order')}}</a>
                            </li>
                            <li>
                                <a href="{{ route('user.login.form') }}" class="top-bar-item">{{__('Login')}}</a>
                            </li>
                            <li>
                                <a href="{{ route('user.register.form') }}" class="top-bar-item">{{__('Registration')}}</a>
                            </li>
                        @endauth


                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Top Bar -->

    <!-- mobile menu -->
    <div class="mobile-side-menu d-lg-none">
        <div class="side-menu-overlay opacity-0" onclick="sideMenuClose()"></div>
        <div class="side-menu-wrap opacity-0">
            <div class="side-menu closed">
                <div class="side-menu-header ">
                    <div class="side-menu-close" onclick="sideMenuClose()">
                        <i class="la la-close"></i>
                    </div>

                    @auth
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                            @if (Auth::user()->avatar_original != null)
                                <div class="image " style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
                            @else
                                <div class="image " style="background-image:url('{{ asset('frontend/images/user.png') }}')"></div>
                            @endif

                            <div class="name">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="side-login px-3 pb-3">
                            <a href="{{ route('logout') }}">{{__('Sign Out')}}</a>
                        </div>
                    @else
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                                <div class="image " style="background-image:url('{{ asset('frontend/images/icons/user-placeholder.jpg') }}')"></div>
                        </div>
                        <div class="side-login px-3 pb-3">

                                <a href="{{ route('user.login.form') }}">
                                    <span style="opacity: 0">(</span>
                                    {{__('Sign In')}}
                                    <span style="opacity: 0">)</span>
                                </a>
                            <a href="{{ route('user.register.form') }}">{{__('Registration')}}</a>
                        </div>
                    @endauth
                </div>
                <div class="side-menu-list px-3">
                    <ul class="side-user-menu">
                        <li>
                            <a href="{{ route('home') }}" class="{{ areActiveRoutesHome(['home'])}}">
                                <i class="la la-dashboard"></i>
                                <span class="category-name">
                                    {{__('Continue to Shipping')}}
                                </span>
                            </a>
                        </li>


                        @if (Auth::check())

                            @if (\App\Models\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                                @php
                                    $conversation_recieved = \App\Models\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', 0)->get();
                                @endphp
                                <li>
                                    <a href="{{ route('conversations.index') }}" class="{{ areActiveRoutesHome(['conversations.index', 'conversations.show'])}}">
                                        <i class="la la-comment" style="font-size: 18px !important"></i>
                                        <span class="category-name">
                                            {{__('Messages')}}
                                            @if (count($conversation_recieved) > 0)
                                                <span class="ml-2" style="color:green"><strong>({{ count($conversation_recieved) }})</strong></span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->user_type == 'customer')
                                <li>
                                    <a href="{{ route('user.orders.index') }}" class="{{ areActiveRoutesHome(['user.orders.index','dashboard'])}}">
                                        <i class="la la-file-text" style="font-size: 18px !important"></i>
                                        <span class="category-name">
                                            {{__('Orders')}}
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->user_type == 'designer')
                                <li>
                                    <a href="{{ route('my_store.index',Auth::user()->store_name) }}" class="{{ areActiveRoutesHome(['my_store.index'])}}">
                                        <i class="fa fa-bookmark" style="font-size: 18px !important"></i>
                                        <span class="category-name">
                                            {{__('My Store')}}
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('listings.index') }}" class="{{ areActiveRoutesHome(['listings.index'])}}">
                                        <i class="fa fa-list" style="font-size: 18px !important"></i>
                                        <span class="category-name">
                                            {{__('Listings')}}
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('collections.index') }}" class="{{ areActiveRoutesHome(['collections.index'])}}">
                                        <i class="fa fa-paint-brush" style="font-size: 18px !important"></i>
                                        <span class="category-name">
                                            {{__('Start Design')}}
                                        </span>
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('profile') }}" class="{{ areActiveRoutesHome(['profile'])}}">
                                    <i class="la la-user"></i>
                                    <span class="category-name">
                                        {{__('Manage Profile')}}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                                    <i class="la la-heart-o" style="font-size: 18px !important"></i>
                                    <span class="category-name">
                                        {{__('Wishlist')}}
                                        @if(Auth::check())
                                            <span class="badge" id="cart_items_sidenav">{{ count(Auth::user()->wishlists)}}</span>
                                        @else
                                            <span class="badge" id="cart_items_sidenav">0</span>
                                        @endif
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('calender') }}" class="{{ areActiveRoutesHome(['calender'])}}">
                                    <i class="fa fa-calendar" style="font-size: 18px !important"></i>
                                    <span class="category-name">
                                        {{__('Calender')}}
                                    </span>
                                </a>
                            </li>


                            @if(Auth::user()->user_type == 'seller')
                                <li>
                                    <a href="{{ route('user.orders.index') }}" class="{{ areActiveRoutesHome(['user.orders.index'])}}">
                                        <i class="la la-file-text"></i>
                                        <span class="category-name">
                                            {{__('Orders')}}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('orders.request_commission.seller') }}" >
                                        <i class="la la-dashboard" style="font-size: 18px !important"></i>
                                        <span class="category-name">
                                            {{__('Commission Requests')}}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('seller.common_questions') }}" class="{{ areActiveRoutesHome(['seller.common_questions'])}}">
                                        <i class="la la-user" style="font-size: 18px !important"></i>
                                        <span class="category-name">
                                            {{__('Common Questions')}}
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @php
                                $quality_responsible = \App\Models\QualityResponsible::first();
                            @endphp
                            <div class="text-center" style="padding: 15px">
                                <div style="background-color: #cec9c95c;border-radius: 14px; padding: 15px;">
                                    <p>{{__('Quality Responsible')}}</p>
                                    <img src="{{ asset($quality_responsible->photo) }}" height="75" width="75" style="border-radius: 50px" alt="">
                                    <h5>{{$quality_responsible->name}}</h5>
                                    <div class="mt-3">
                                        <button onclick="window.open('tel:{{$quality_responsible->phone}}');" class="btn btn-outline-success" style="border-radius: 50px">{{$quality_responsible->phone}} <i class="text-md la la-phone"></i></button>
                                    </div>
                                    <div class="mt-3">
                                        <form method="get" action="https://wa.me/{{$quality_responsible->country_code}}{{substr($quality_responsible->wts_phone,2)}}">
                                            <button type="submit" class="btn btn-outline-success" style="border-radius: 50px">{{$quality_responsible->wts_phone}} <i class="fa fa-whatsapp"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <!-- end mobile menu -->

    <div class="position-relative logo-bar-area">
        <div class="">
            <div class="container">
                <div class="row no-gutters align-items-center">
                    <div class="col-lg-3 col-8">
                        <div class="d-flex">
                            <div class="d-block d-lg-none mobile-menu-icon-box">
                                <!-- Navbar toggler  -->
                                <a href="" onclick="sideMenuOpen(this)">
                                    <div class="hamburger-icon">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                            </div>

                            <!-- Brand/Logo -->
                            <a class="navbar-brand w-100" href="{{ route('home') }}">
                                @php
                                    $generalsetting = \App\Models\GeneralSetting::first();
                                @endphp
                                @if($generalsetting->logo != null)
                                    <img src="{{ asset($generalsetting->logo) }}" style=" max-height: 80px;" alt="{{ env('APP_NAME') }}">
                                @else
                                    <img src="{{ asset('frontend/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}">
                                @endif
                            </a>

                            @if(Route::currentRouteName() != 'home' && Route::currentRouteName() != 'categories.all')
                                <div class="d-none d-xl-block category-menu-icon-box">
                                    <div class="dropdown-toggle navbar-light category-menu-icon" id="category-menu-icon">
                                        <span class="navbar-toggler-icon"></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-4 position-static">
                        <div class="d-flex w-100">
                            <div class="search-box flex-grow-1 px-4">
                                <form action="{{ route('search') }}" method="GET">
                                    <div class="d-flex position-relative">
                                        <div class="d-lg-none search-box-back">
                                            <button class="" type="button"><i class="la la-long-arrow-left"></i></button>
                                        </div>
                                        <div class="w-100">
                                            <input type="text" aria-label="Search" id="search" name="q" class="w-100" placeholder="{{__('I am shopping for...')}}" autocomplete="off">
                                        </div>
                                        <div class="form-group category-select d-none d-xl-block">
                                            <select class="form-control selectpicker" name="category">
                                                <option value="">{{__('All Categories')}}</option>
                                                @foreach (\App\Models\Category::all() as $key => $category)
                                                <option value="{{ $category->slug }}"
                                                    @isset($category_id)
                                                        @if ($category_id == $category->id)
                                                            selected
                                                        @endif
                                                    @endisset
                                                    >{{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="d-none d-lg-block" type="submit">
                                            <i class="la la-search la-flip-horizontal"></i>
                                        </button>
                                        <div class="typed-search-box d-none">
                                            <div class="search-preloader">
                                                <div class="loader"><div></div><div></div><div></div></div>
                                            </div>
                                            <div class="search-nothing d-none">

                                            </div>
                                            <div id="search-content">

                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="logo-bar-icons d-inline-block ml-auto">
                                <div class="d-inline-block d-lg-none">
                                    <div class="nav-search-box">
                                        <a href="#" class="nav-box-link">
                                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    <div class="nav-wishlist-box" id="wishlist">
                                        <a href="{{ route('wishlists.index') }}" class="nav-box-link">
                                            <i class="la la-heart-o d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block">{{__('Wishlist')}}</span>
                                            @if(Auth::check())
                                                <span class="nav-box-number">{{ count(Auth::user()->wishlists)}}</span>
                                            @else
                                                <span class="nav-box-number">0</span>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div class="d-inline-block" data-hover="dropdown">
                                    <div class="nav-cart-box dropdown" id="cart_items">
                                        <a href="" class="nav-box-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-shopping-cart d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block">{{__('Cart')}}</span>
                                            @if(auth()->check() && auth()->user()->carts)
                                                <span class="nav-box-number">{{ count(auth()->user()->carts) }}</span>
                                            @else
                                                <span class="nav-box-number">0</span>
                                            @endif
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right px-0">
                                            <li>
                                                <div class="dropdown-cart px-0">
                                                    @if(auth()->check() && auth()->user()->carts)
                                                        @if(count(auth()->user()->carts) > 0)
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700">{{__('Cart Items')}}</h3>
                                                            </div>
                                                            <div class="dropdown-cart-items c-scrollbar">
                                                                @php
                                                                    $total = 0;
                                                                @endphp
                                                                @foreach(auth()->user()->carts as $key => $cartItem)
                                                                    @php
                                                                        $product = \App\Models\Product::find($cartItem['product_id']);
                                                                        $total = $total + $cartItem['total_cost'];
                                                                    @endphp
                                                                    <div class="dc-item">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="dc-image">
                                                                                <a href="{{ route('product', $product->slug) }}">
                                                                                    <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($cartItem->chosen_photo ?? $product->thumbnail_img) }}" class="img-fluid lazyload" alt="{{ __($product->name) }}">
                                                                                </a>
                                                                            </div>
                                                                            <div class="dc-content">
                                                                                <span class="d-block dc-product-name text-capitalize strong-600 mb-1">
                                                                                    <a href="{{ route('product', $product->slug) }}">
                                                                                        {{ __($product->name) }} ({{$cartItem['variation']}})
                                                                                    </a>
                                                                                </span>

                                                                                <span class="dc-quantity">x{{ $cartItem['quantity'] }}</span>
                                                                                <span class="dc-price">{{ single_price($cartItem['total_cost']) }}</span>
                                                                            </div>
                                                                            <div class="dc-actions">
                                                                                <button onclick="removeFromCart({{ $cartItem['id'] }})">
                                                                                    <i class="la la-close"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="dc-item py-3">
                                                                <span class="subtotal-text">{{__('Subtotal')}}</span>
                                                                <span class="subtotal-amount">{{ single_price($total) }}</span>
                                                            </div>
                                                            <div class="py-2 text-center dc-btn">
                                                                <ul class="inline-links inline-links--style-3">
                                                                    <li class="px-1">
                                                                        <a href="{{ route('cart') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1">
                                                                            <i class="la la-shopping-cart"></i> {{__('View cart')}}
                                                                        </a>
                                                                    </li>
                                                                    @if (Auth::check())
                                                                    <li class="px-1">
                                                                        <a href="{{ route('checkout.shipping_info') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1 light-text">
                                                                            <i class="la la-mail-forward"></i> {{__('Checkout')}}
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="dc-header">
                                                            <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hover-category-menu" id="hover-category-menu">
            <div class="container">
                <div class="row no-gutters position-relative">
                    <div class="col-lg-3 position-static">
                        <div class="category-sidebar" id="category-sidebar">
                            <div class="all-category">
                                <span>{{__('CATEGORIES')}}</span>
                                <a href="{{ route('categories.all') }}" class="d-inline-block">See All ></a>
                            </div>
                            <ul class="categories">
                                @foreach (\App\Models\Category::all()->take(11) as $key => $category)
                                    @php
                                        $brands = array();
                                    @endphp
                                    <li class="category-nav-element" data-id="{{ $category->id }}">
                                        <a href="{{ route('products.category', $category->slug) }}">
                                            <img class="cat-image lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->icon) }}" width="30" alt="{{ __($category->name) }}">
                                            <span class="cat-name">{{ __($category->name) }}</span>
                                        </a>
                                        @if(count($category->subcategories)>0)
                                            <div class="sub-cat-menu c-scrollbar">
                                                <div class="c-preloader">
                                                    <i class="fa fa-spin fa-spinner"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Navbar -->
</div>
