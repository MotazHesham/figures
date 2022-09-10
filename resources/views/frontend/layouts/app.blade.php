<!DOCTYPE html>
<html lang="en">
<head>

@php
    $seosetting = \App\Models\SeoSetting::first();
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow">
<title>@yield('meta_title', config('app.name', 'Laravel'))</title>
<meta name="description" content="@yield('meta_description', $seosetting->description)" />
<meta name="keywords" content="@yield('meta_keywords', $seosetting->keyword)">
<meta name="author" content="{{ $seosetting->author }}">
<meta name="sitemap_link" content="{{ $seosetting->sitemap_link }}">
<meta name="facebook-domain-verification" content="zhiftfbjj8tk57ds1l8l2fwh9ukmrn" />

@yield('meta')

@if(!isset($detailedProduct))
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ config('app.name', 'Laravel') }}">
    <meta itemprop="description" content="{{ $seosetting->description }}">
    <meta itemprop="image" content="{{ asset(\App\Models\GeneralSetting::first()->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ config('app.name', 'Laravel') }}">
    <meta name="twitter:description" content="{{ $seosetting->description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ asset(\App\Models\GeneralSetting::first()->logo) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }}" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:image" content="{{ asset(\App\Models\GeneralSetting::first()->logo) }}" />
    <meta property="og:description" content="{{ $seosetting->description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endif

<!-- Favicon -->
<link type="image/x-icon" href="{{ asset(\App\Models\GeneralSetting::first()->favicon) }}" rel="shortcut icon" />

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet" media="none" onload="if(media!='all')media='all'">

<!-- Bootstrap -->
<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" type="text/css" media="all">

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="{{ asset('frontend/css/line-awesome.min.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">


<link rel="stylesheet" href="{{ asset('evo-calendar/css/evo-calendar.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">
<link rel="stylesheet" href="{{ asset('evo-calendar/css/evo-calendar.royal-navy.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">


<link type="text/css" href="{{ asset('frontend/css/bootstrap-tagsinput.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link type="text/css" href="{{ asset('frontend/css/jodit.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link type="text/css" href="{{ asset('frontend/css/sweetalert2.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link type="text/css" href="{{ asset('frontend/css/slick.css') }}" rel="stylesheet" media="all">
<link type="text/css" href="{{ asset('frontend/css/xzoom.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link type="text/css" href="{{ asset('frontend/css/jssocials.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link type="text/css" href="{{ asset('frontend/css/jssocials-theme-flat.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link type="text/css" href="{{ asset('frontend/css/intlTelInput.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">
<link type="text/css" href="{{ asset('css/spectrum.css')}}" rel="stylesheet" media="none" onload="if(media!='all')media='all'">

<!-- Global style (main) -->
<link type="text/css" href="{{ asset('frontend/css/active-shop.css') }}" rel="stylesheet" media="all">


<link type="text/css" href="{{ asset('frontend/css/main.css') }}" rel="stylesheet" media="all">


<!-- color theme -->
<link href="{{ asset('frontend/css/colors/'.\App\Models\GeneralSetting::first()->frontend_color.'.css')}}" rel="stylesheet" media="all">

<!-- Custom style -->
<link type="text/css" href="{{ asset('frontend/css/custom-style.css') }}" rel="stylesheet" media="all">

<style>

</style>

<!-- jQuery -->
<script src="{{ asset('frontend/js/vendor/jquery.min.js') }}"></script>





<style>

#accordion{
    padding-left: 80px;
    overflow: hidden;
    position: relative;
    z-index: 1;
}
#accordion:before{
    content: "";
    width: 5px;
    height: 100%;
    background: #004e89;
    position: absolute;
    top: 0;
    left: 22px;
    z-index: -1;
}
#accordion .panel{
    border: none;
    border-radius: 0;
    box-shadow: none;
    margin-bottom: 15px;
}
#accordion .panel-heading{
    padding: 0;
    border: none;
    border-radius: 0;
}
#accordion .panel-title a{
    display: block;
    padding: 10px 30px 15px 0;
    background: #fff;
    font-size: 18px;
    font-weight: bold;
    color: #004e89;
    position: relative;
    transition: all 0.5s ease 0s;
}
#accordion .panel-title a:before{
    content: "-";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    width: 50px;
    height: 50px;
    line-height: 40px;
    border-radius: 50%;
    background: #cad5c2;
    text-align: center;
    font-size: 17px;
    color: #004e89;
    border: 6px solid #004e89;
    position: absolute;
    top: 0;
    left: -80px;
}
#accordion .panel-title a.collapsed:before{
    content: "+";
    background: #fff;
}
#accordion .panel-body{
    padding: 10px 15px;
    background: #eee;
    border: none;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) inset, 0 1px 2px rgba(255, 255, 255, 0.9);
    font-size: 14px;
    color: #487677;
    line-height: 25px;
}




.category-name{
    font-size: 16px !important;
}


    .new_home .card-body{
        position: absolute;
        top: 19%;
        left: 22%;
    }
    .new_home .card{
        background: #878840;
        overflow: hidden;
    }
    .new_home h5{
        font-size: 25px;
        font-weight: 700;
        color: white;
    }
    .new_home img{
        object-fit: cover;
        opacity: 0.4;
        height: 150px;
    }
    .new_home .card:hover{
        opacity: 0.7;
        cursor: pointer;
    }

    #caleneder_badge:hover  .text_calender{
        transform: scale(0);
    }
    #caleneder_badge:hover {
        opacity: 1;
    }
    #caleneder_badge:hover  #login_calender{
        transform: scale(1);
    }
    #caleneder_badge:hover  #register_calender{
        transform: scale(1);
    }
    #login_calender , #register_calender{

        transform: scale(0);
    }
    .my_custom_date_input{
        position: absolute;
        top: 19px;
        right: 10px;
        width: 70px;
    }
</style>





@yield('styles')
</head>
<body>


<!-- MAIN WRAPPER -->
<div class="body-wrap shop-default shop-cards shop-tech gry-bg">

    <!-- Header -->
    @include('frontend.inc.nav')

    @auth
        @if(auth()->user()->user_type == 'seller' && auth()->user()->seller->verification_status != 1)
            @php
                $generalsetting = \App\Models\GeneralSetting::first();
            @endphp
            <div class="container mt-5 mb-5">
                <h1>{{__('You Are Not Verfied Yet')}} <span style="color:#348282">{{__('Contact Us Via:')}}</span></h1>
                <div style="padding:30px;border: 1px #bdb8b8 solid;">
                    <div class="row">
                        <div class="col-md-2">
                            <span style="color:#348282;padding:15px">{{__('Email')}}: </span>
                        </div>
                        <div class="col-md-2">
                            <span>{{$generalsetting->email}}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <span style="color:#348282;padding:15px">{{__('Phone')}}: </span>
                        </div>
                        <div class="col-md-2">
                            <span >{{$generalsetting->phone}}</span>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <span style="color:#348282;padding:15px">{{__('Address')}}: </span>
                        </div>
                        <div class="col-md-2">
                            <span>{{$generalsetting->address}}</span>
                        </div>
                    </div>

                </div>
            </div>
        @else
            @yield('content')
        @endif
    @else
        @yield('content')
    @endauth

    @include('frontend.inc.footer')

    @include('frontend.partials.modal')



    <div class="modal fade" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductInCart" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="editProductInCart-modal-body">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="GuestCheckout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{__('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="input-group input-group--style-1">
                                    <input type="email" name="email" class="form-control" placeholder="{{__('Email')}}">
                                    <span class="input-group-addon">
                                        <i class="text-md la la-user"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group--style-1">
                                    <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
                                    <span class="input-group-addon">
                                        <i class="text-md la la-lock"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <a href="{{ route('password.request') }}" class="link link-xs link--style-3">{{__('Forgot password?')}}</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1 px-4">{{__('Sign in')}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="text-center pt-3">
                        <p class="text-md">
                            {{__('Need an account?')}} <a href="{{ route('user.register.form') }}" class="strong-600">{{__('Register Now')}}</a>
                        </p>
                    </div>
                    <div class="or or--1 my-3 text-center">
                        <span>or</span>
                    </div>
                    <div class="p-3 pb-0">
                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 mb-3">
                            <i class="icon fa fa-facebook"></i> {{__('Login with Facebook')}}
                        </a>
                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 mb-3">
                            <i class="icon fa fa-google"></i> {{__('Login with Google')}}
                        </a>
                        {{-- <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4 mb-3">
                            <i class="icon fa fa-twitter"></i> {{__('Login with Twitter')}}
                        </a>  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- END: body-wrap -->

<!-- SCRIPTS -->
<!-- <a href="#" class="back-to-top btn-back-to-top"></a> -->

<!-- Core -->
<script src="{{ asset('frontend/js/vendor/popper.min.js') }}"></script>
<script src="{{ asset('frontend/js/vendor/bootstrap.min.js') }}"></script>

<!-- Plugins: Sorted A-Z -->
<script src="{{ asset('frontend/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('frontend/js/select2.min.js') }}"></script>
<script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
<script src="{{ asset('frontend/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('frontend/js/slick.min.js') }}"></script>
<script src="{{ asset('frontend/js/jssocials.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('frontend/js/jodit.min.js') }}"></script>
<script src="{{ asset('frontend/js/xzoom.min.js') }}"></script>
<script src="{{ asset('frontend/js/fb-script.js') }}"></script>
<script src="{{ asset('frontend/js/lazysizes.min.js') }}"></script>
<script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>

<!-- App JS -->
<script src="{{ asset('frontend/js/active-shop.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>

<script src="{{ asset('evo-calendar/js/evo-calendar.js') }}"></script>

{{-- loading plugin --}}
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

<script>


    function showFrontendAlert(type, message){
        if(type == 'danger'){
            type = 'error';
        }
        swal({
            position: 'top-end',
            type: type,
            title: message,
            showConfirmButton: false,
            timer: 3000
        });
    }
</script>

@foreach (session('flash_notification', collect())->toArray() as $message)
    <script>
        showFrontendAlert('{{ $message['level'] }}', '{{ $message['message'] }}');
    </script>
@endforeach
<script>

    $(document).ready(function() {
        //perevent submittig multiple times
        $("body").on("submit", "form", function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });

        $('.category-nav-element').each(function(i, el) {
            $(el).on('mouseover', function(){
                if(!$(el).find('.sub-cat-menu').hasClass('loaded')){
                    $.post('{{ route('category.elements') }}', {_token: '{{ csrf_token()}}', id:$(el).data('id')}, function(data){
                        $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                    });
                }
            });
        });
        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-item a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){
                        location.reload();
                    });

                });
            });
        }

        if ($('#currency-change').length > 0) {
            $('#currency-change .dropdown-item a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var currency_code = $this.data('currency');
                    $.post('{{ route('currency.change') }}',{_token:'{{ csrf_token() }}', currency_code:currency_code}, function(data){
                        location.reload();
                    });

                });
            });
        }

        $('#from_date').on('change', function() {
            var val = $(this).val();
            $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                $('#from_date_text').val(data);
            });

        });

        $('#to_date').on('change', function() {
            var val = $(this).val();
            $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                $('#to_date_text').val(data);
            });

        });

        $('#date_of_receiving_order').on('change', function() {
            var val = $(this).val();
            $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                $('#date_of_receiving_order_text').val(data);
            });

        });


        $('#excepected_deliverd_date').on('change', function() {
            var val = $(this).val();
            $.post('{{ route('get_date') }}', {_token:'{{ csrf_token() }}', date:val}, function(data){
                $('#excepected_deliverd_date_text').val(data);
            });

        });


    });

    function search(){
        var search = $('#search').val();
        if(search.length > 0){
            $('body').addClass("typed-search-box-shown");

            $('.typed-search-box').removeClass('d-none');
            $('.search-preloader').removeClass('d-none');
            $.post('{{ route('search.ajax') }}', { _token: '{{ @csrf_token() }}', search:search}, function(data){
                if(data == '0'){
                    // $('.typed-search-box').addClass('d-none');
                    $('#search-content').html(null);
                    $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"'+search+'"</strong>');
                    $('.search-preloader').addClass('d-none');

                }
                else{
                    $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                    $('#search-content').html(data);
                    $('.search-preloader').addClass('d-none');
                }
            });
        }
        else {
            $('.typed-search-box').addClass('d-none');
            $('body').removeClass("typed-search-box-shown");
        }
    }

    function updateNavCart(){
        $.post('{{ route('cart.nav_cart') }}', {_token:'{{ csrf_token() }}'}, function(data){
            $('#cart_items').html(data);
        });
    }

    function removeFromCart(id){
        $.post('{{ route('cart.removeFromCart') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
            updateNavCart();
            showFrontendAlert('success', 'Item has been removed from cart');
            $('#cart-summary').html(data);
        });
    }

    function removeFromCartView(e, id){
        e.preventDefault();
        removeFromCart(id);
    }

    function updateQuantity(id, element){
        $.post('{{ route('cart.updateQuantity') }}', { _token:'{{ csrf_token() }}', id:id, quantity: element.value}, function(data){
            updateNavCart();
            $('#cart-summary').html(data);
        });
    }

    function showCheckoutModal(){
        $('#GuestCheckout').modal();
    }

    function addToWishList(id){
        @if (Auth::check() && (Auth::user()->user_type == 'customer' || Auth::user()->user_type == 'seller'))
            $.post('{{ route('wishlists.store') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
                if(data != 0){
                    $('#wishlist').html(data);
                    showFrontendAlert('success', 'Item has been added to wishlist');
                }
                else{
                    showFrontendAlert('warning', 'Please login first');
                }
            });
        @else
            showFrontendAlert('warning', 'Please login first');
        @endif
    }

    function showAddToCartModal(id){

        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }
        $('#addToCart-modal-body').html(null);
        $('#addToCart').modal();
        $('.c-preloader').show();
        $.post('{{ route('cart.showCartModal') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
            $('.c-preloader').hide();
            $('#addToCart-modal-body').html(data);
            $('.xzoom, .xzoom-gallery').xzoom({
                Xoffset: 20,
                bg: true,
                tint: '#000',
                defaultScale: -1
            });
            getVariantPrice();
        });
    }

    function editProductInCartModal(id){
        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }
        $('#editProductInCart-modal-body').html(null);
        $('#editProductInCart').modal();
        $('.c-preloader').show();
        $.post('{{ route('cart.edit') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
            $('.c-preloader').hide();
            $('#editProductInCart-modal-body').html(data);
            $('.xzoom, .xzoom-gallery').xzoom({
                Xoffset: 20,
                bg: true,
                tint: '#000',
                defaultScale: -1
            });
        });
    }

    $('input[name="free_shipping"]').on('change', function() {
        if($('input[name="free_shipping"]').is(':checked')){
            $('#free_Shipping_reason_row').css('display', 'inline');
            $('#shipping_cost').prop('disabled', true);
        }
        else{
            $('#free_Shipping_reason_row').css('display', 'none');
            $('#shipping_cost').prop('disabled', false);
        }
    });

    $('#option-choice-form input').on('change', function(){
        $('.chosen_quntity_input').val($('.input-number').val());
        getVariantPrice();
    });


    function getVariantPrice(){
        if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
            $.ajax({
                type:"POST",
                url: '{{ route('products.variant_price') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data){
                    $('#option-choice-form #chosen_price_div').removeClass('d-none');
                    $('#option-choice-form #chosen_price_div #chosen_price').html(data.total_price);
                    $('#option-choice-form #chosen_price_div #chosen_price_commission').html(data.total_commission);
                    $('#product-price-for-variant').html(data.price);
                    $('.chosen_price_input').val(data.price_input);
                    $('.chosen_variant').val(data.variant);
                    $('#product-price-old-for-variant').html(data.before_discount);
                    $('#available-quantity').html(data.quantity);
                    $('.input-number').prop('max', data.quantity);
                    //console.log(data.quantity);
                    if(parseInt(data.quantity) < 1 && data.digital  != 1){
                        $('.buy-now').hide();
                        $('.add-to-cart').hide();
                    }
                    else{
                        $('.buy-now').show();
                        $('.add-to-cart').show();
                    }
                }
            });
        }
    }

    function checkAddToCartValidity(){
        var names = {};
        $('#option-choice-form input:radio').each(function() { // find unique names
              names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function() { // then count them
              count++;
        });

        if($('#option-choice-form input:radio:checked').length == count){
            return true;
        }

        return false;
    }



    function show_details(id){
        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }
        $('#addToCart-modal-body').html(null);
        $('#addToCart').modal();
        $('.c-preloader').show();
        $.post('{{ route('user.orders.products.product_details_of_order') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
            $('.c-preloader').hide();
            $('#addToCart-modal-body').html(data);
            $('.xzoom, .xzoom-gallery').xzoom({
                Xoffset: 20,
                bg: true,
                tint: '#000',
                defaultScale: -1
            });
        });
    }

    function addToCart(){
        if(checkAddToCartValidity()) {
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.ajax({
                type:"POST",
                url: '{{ route('cart.addToCart') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data){
                    $('#addToCart-modal-body').html(null);
                    $('.c-preloader').hide();
                    $('#modal-size').removeClass('modal-lg');
                    $('#addToCart-modal-body').html(data);
                    updateNavCart();
                    $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
                }
            });
        }
        else{
            showFrontendAlert('warning', 'Please choose all the options');
        }
    }

    var photo_id = 2;
    function add_more_slider_image(){
        var photoAdd =  '<div class="row">';
        photoAdd +=  '<div class="col-2">';
        photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>';
        photoAdd +=  '</div>';
        photoAdd +=  '<div class="col-6">';
        photoAdd +=  '<input type="file" name="photos[]" id="photos-'+photo_id+'" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
        photoAdd +=  '<label for="photos-'+photo_id+'" class="mw-100 mb-3">';
        photoAdd +=  '<span></span>';
        photoAdd +=  '<strong>';
        photoAdd +=  '<i class="fa fa-upload"></i>';
        photoAdd +=  "{{__('Choose image')}}";
        photoAdd +=  '</strong>';
        photoAdd +=  '</label>';
        photoAdd +=  '</div>';
        photoAdd +=  '<div class="col-md-4">';
        photoAdd +=  '<input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">';
        photoAdd +=  '</div>';
        photoAdd +=  '</div>';
        $('#product-images').append(photoAdd);

        photo_id++;
        imageInputInitialize();
    }
    var photo_id2 = 2;
    function add_more_slider_image2(){
        var photoAdd =  '<div class="row">';
        photoAdd +=  '<div class="col-2">';
        photoAdd +=  '<button type="button" onclick="delete_this_row(this)" class="btn btn-link btn-icon text-danger"><i class="fa fa-trash-o"></i></button>';
        photoAdd +=  '</div>';
        photoAdd +=  '<div class="col-6">';
        photoAdd +=  '<input type="file" name="photos[]" id="photos2-'+photo_id2+'" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
        photoAdd +=  '<label for="photos2-'+photo_id2+'" class="mw-100 mb-3">';
        photoAdd +=  '<span></span>';
        photoAdd +=  '<strong>';
        photoAdd +=  '<i class="fa fa-upload"></i>';
        photoAdd +=  "{{__('Choose image')}}";
        photoAdd +=  '</strong>';
        photoAdd +=  '</label>';
        photoAdd +=  '</div>';
        photoAdd +=  '<div class="col-md-4">';
        photoAdd +=  '<input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">';
        photoAdd +=  '</div>';
        photoAdd +=  '</div>';
        $('#product-images2').append(photoAdd);

        photo_id2++;
        imageInputInitialize();
    }
    function delete_this_row(em){
        $(em).closest('.row').remove();
    }

    function buyNow(){
        if(checkAddToCartValidity()) {
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.ajax({
                type:"POST",
                url: '{{ route('cart.addToCart') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data){
                   //$('#addToCart-modal-body').html(null);
                   //$('.c-preloader').hide();
                   //$('#modal-size').removeClass('modal-lg');
                   //$('#addToCart-modal-body').html(data);
                    updateNavCart();
                    $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
                    window.location.replace("{{ route('cart') }}");
                }
            });
        }
        else{
            showFrontendAlert('warning', 'Please choose all the options');
        }
    }


    function cartQuantityInitialize(){
        $('.btn-number').click(function(e) {
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function() {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function() {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                alert('Sorry, the minimum value was reached');
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                alert('Sorry, the maximum value was reached');
                $(this).val($(this).data('oldValue'));
            }


        });
        $(".input-number").keydown(function(e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function imageInputInitialize(){
        $('.custom-input-file').each(function() {
            var $input = $(this),
                $label = $input.next('label'),
                labelVal = $label.html();

            $input.on('change', function(e) {
                var fileName = '';

                if (this.files && this.files.length > 1)
                    fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                else if (e.target.value)
                    fileName = e.target.value.split('\\').pop();

                if (fileName)
                    $label.find('span').html(fileName);
                else
                    $label.html(labelVal);
            });

            // Firefox bug fix
            $input
                .on('focus', function() {
                    $input.addClass('has-focus');
                })
                .on('blur', function() {
                    $input.removeClass('has-focus');
                });
        });
    }


</script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;

        var pusher = new Pusher('5fe57ffd2f40d8e4fbb0', {
            cluster: 'eu'
        });

        // var channel = pusher.subscribe('my-channel');
        // channel.bind('my-event', function(data) {
        //     alert(JSON.stringify(data));
        // });
    </script>
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script>
        @if(!Cookie::has('device_token'))
            $(document).ready(function(){
                initFirebaseMessagingRegistration();
            });
        @endif

        var firebaseConfig = {
            apiKey: "AIzaSyDQPcBmACU5DEKYk5tYsrjXZvSXpquU8K0",
            authDomain: "ertgal-82937.firebaseapp.com",
            projectId: "ertgal-82937",
            storageBucket: "ertgal-82937.appspot.com",
            messagingSenderId: "472298756042",
            appId: "1:472298756042:web:8482b91ef945698b23a133",
            measurementId: "G-K4J3VX5JDH"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        function initFirebaseMessagingRegistration() {
                messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);
                    $.ajax({
                        url: '{{ route("save-token") }}',
                        type: 'POST',
                        data: {
                            token: token,
                            _token:'{{ @csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            console.log('Token saved successfully.');
                        },
                        error: function (err) {
                            console.log('User Chat Token Error'+ err);
                        },
                    });
                }).catch(function (err) {
                    console.log('User Chat Token Error'+ err);
                });
        }
        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });
    </script>
@yield('script')

</body>
</html>
