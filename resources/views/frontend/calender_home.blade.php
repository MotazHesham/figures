

<!DOCTYPE html>
<html lang="en">
<head>

@php
    $seosetting = \App\Models\SeoSetting::first();
@endphp

<meta charset="utf-8">
<meta name="robots" content="index, follow">
<title>@yield('meta_title', config('app.name', 'Laravel'))</title>
<meta name="description" content="@yield('meta_description', $seosetting->description)" />
<meta name="keywords" content="@yield('meta_keywords', $seosetting->keyword)">
<meta name="author" content="{{ $seosetting->author }}">
<meta name="sitemap_link" content="{{ $seosetting->sitemap_link }}">

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
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">


<style>
    
</style>

<!-- jQuery -->
<script src="{{ asset('frontend/js/vendor/jquery.min.js') }}"></script>

<style> 

    @font-face {
        font-family: 'Air Strip Arabic';
        src: url("{{ asset('css/AirStripArabic.ttf') }}") format("truetype");
        font-weight: normal;
        font-style: normal;
    }
    body{ 
        font-family: 'Air Strip Arabic';
    }  
</style>
</head>
<body>

    <!-- Header -->   

    <div style="background: url('{{asset('img/background0.jpg')}}');background-size: cover;height:720px">
        <nav class="navbar navbar-dark navbar-expand-lg navbar-light bg-light" style=" background-color: transparent !important;">
            <a class="navbar-brand" href="#" style="  margin-left: 3%;  " data-aos-duration="1000" data-aos="fade-right">
                @php
                    $generalsetting = \App\Models\GeneralSetting::first();
                @endphp
                @if($generalsetting->logo != null)
                    <img src="{{ asset($generalsetting->logo) }}" alt="{{ env('APP_NAME') }}" width="100" height="100">
                @else
                    <img src="{{ asset('frontend/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }}" width="100" height="100" >
                @endif 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav" style="  margin-left: 85%;"> 
                    <li class="nav-item " data-aos-duration="1100" data-aos="fade-left">
                        <a style="font-size: 20px;font-family: 'Air Strip Arabic';color:white" class="nav-link" href="{{ route('orders.track') }}">تتبع الطلبات</a>
                    </li>  
                </ul>
            </div>
        </nav>
        <div class="container">
            <div class="row justify-content-md-center"> 
                <div class="col-md-5"  class="text-center"> 
                    <div style="text-align:end">
                        <img style="margin-top: 15%" src="{{asset('img/00.png')}}" class=" img-fluid" data-aos-duration="1000" data-aos="fade-up">
                        <p style=" font-size:23px;line-height: 31px;text-align: center;color:white" data-aos-duration="1500" data-aos="fade-up">
                            <span>...أفكار مبتنتهيش</span>
                            <br>
                            <span>قبل أي مناسبة هانكلمك نفكرك </span>
                        </p>    
                    </div> 
                </div>
            </div>
        </div>
    </div>  


    <div style="background-image: linear-gradient(to bottom,rgb(255, 168, 93,.4) ,rgb(255, 168, 93,.04)  , rgb(255, 168, 93,0)  )">    

        <div style=" text-align:center"> 
            <video width="650" height="400" controls class=" img-fluid" style="margin-top: -12%;" data-aos-duration="2000" data-aos="fade-up">
                <source src="{{asset('img/calender.mp4')}}" type="video/mp4"> 
            </video>
            <div class="mt-4"> 
            
                <a data-aos-duration="1000" data-aos="fade-left" style=" font-size: 37px; margin: 16px; border-radius: 16px;font-family: 'Air Strip Arabic';" class="btn btn-warning" href="{{route('user.login.form')}}">تسجبل الدخول</a>
                <a data-aos-duration="1000" data-aos="fade-right" style=" font-size: 37px; margin: 16px; border-radius: 16px;font-family: 'Air Strip Arabic';" class="btn btn-warning" href="{{route('user.register.form')}}">ابدأ الان</a>
                
            </div> 
        </div>
        
        <div class="row mt-5 mb-5">
            <div class="col-md-5 offset-md-1">
                <img src="{{asset('img/about_company.gif')}}" data-aos="zoom-out-right" data-aos-duration="1000" class=" img-fluid">
            </div>
            <div class="col-md-6" style="text-align: end">
                <h1 style="margin-right:1%;font-family: 'Air Strip Arabic';" data-aos="fade-right" data-aos-duration="1000"> <i class="fa fa-building" style="color:#F5B041"></i> عن الشركة </h1>
                <p style="margin-right:1%;font-size: 23px;line-height:50px" data-aos="fade-up"
                data-aos-anchor-placement="center-bottom" data-aos-duration="1000" > 
                        <span>شركة ابتكار لكل ماهو مبتكر فى عالم الدعايه والإعلان </span>
                    <br>
                        <span>شعارنا / أداء - جودة - سرعه - أسعار منافسه</span> 
                    <br>
                        <span>من الأخر </span>
                    <br>
                        <span>أفكار مبتنتهيش</span> 
                    <br>
                        <span><a href="{{route('home')}}" >ebtekarstore.net <i class="fa fa-external-link"></i></a> </span> <span>ابدأ بالتسوق في منتجاتنا الأن</span> 
                </p>
            </div>
        </div>
        
    </div>
    

        <div class="container">
            <div id="calendar" data-aos-duration="1000" data-aos="zoom-in"></div>
        </div>
        

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
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    $(document).ready(function() { 
        
        AOS.init();
        $('#calendar').evoCalendar({
            theme: 'Royal Navy',
            format: 'dd/MM/yyyy',
        })

        $('#calendar').evoCalendar('toggleEventList', false);
        
    })
</script>

</body>
</html>

