

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
<link rel="stylesheet" href="{{ asset('evo-calendar/css/evo-calendar.midnight-blue.css') }}" type="text/css" media="none" onload="if(media!='all')media='all'">


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
<body >



    <!-- Header -->   


    <div style="background-image: linear-gradient(to bottom,rgb(255, 168, 93,.4) ,rgb(255, 168, 93,.04)  , rgb(255, 168, 93,0)  )">

        <div >
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style=" background-color: transparent !important;">
                <a class="navbar-brand" href="#" style="  margin-left: 3%;  ">
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
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav" style="  margin-left: 59%;">
                        <li class="nav-item ">
                            <a style="font-size: 20px;font-family: 'Air Strip Arabic';" class="nav-link" href="{{$generalsetting->video_instructions}}" target="_blanc">شرح التسجيل بالموقع</a>
                        </li>
                        <li class="nav-item ">
                            <a style="font-size: 20px;font-family: 'Air Strip Arabic';" class="nav-link" href="{{ route('orders.track') }}">تتبع الطلبات</a>
                        </li>
                        <li class="nav-item">
                            <a style="font-size: 20px;font-family: 'Air Strip Arabic';" class="nav-link" href="{{ route('user.login.form') }}">تسجيل الدخول </a>
                        </li> 
                    </ul>
                </div>
            </nav>
            <div class="container">
                <div class="row">
                    <div class="col-md-6" >
                        <img  src="{{asset('img/1.png')}}" class=" img-fluid" height="600" width="880" alt="">
                    </div>
                    <div class="col-md-6" style="text-align: end">
                        <img style="margin-top: 15%" src="{{asset('img/00.png')}}" class=" img-fluid">
                        <p class="mt-5" style=" font-size:23px;line-height: 31px;">
                            احنا اول متجر الكتروني للمسوقين في مصر 
                            <br>
                            بمنتجات اسبشيل باختيار 
                            العميل وهدايا مناسبات ومنتجات  
                            <br>
                            دعائيه للشركات  
                        </p> 
                        <p>
                            <button type="button" data-toggle="modal" data-target="#exampleModal"  class="btn btn-warning text-center" style="margin-right: 30%; font-size: 32px; padding: 5px 33px;font-family: 'Air Strip Arabic';
                            ">سجل الأن</button>
                        </p>
                        
                    </div>
                </div>
            </div>
        </div> 
        <!-- seller register Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body" id="user-register-partial">
                        @include('frontend.partials.register_partial')
                    </div> 
                </div>
            </div>
        </div>

        <h1 class="text-center " style="margin-bottom: 5%;font-family: 'Air Strip Arabic';font-size: 65px; " data-aos="flip-left" data-aos-duration="1000" >مزايا العمل معنا</h1>

        <div class="row ">
            <div class="col-md-6 text-center" style="font-size: 53px" data-aos="fade-down-right" data-aos-duration="1000">
                <div style=" margin-left: 34%; " >
                    هتستلم
                </div>
                <div style=" margin-top: -4%; margin-left: 5%;" > 
                    ارباحك 
                </div>
                <p style="font-size: 30px"  >بمجرد استلام العميل الاوردر</p>
                <img src="{{asset('img/22.png')}}"  class=" img-fluid">
            </div>
            <div class="col-md-6 text-center" style="font-size: 53px" data-aos="fade-down-left" data-aos-duration="1000"> 
                <div  > 
                    منتجاتك عندنا 
                </div>
                <p style="font-size: 27px; line-height: 40px;" >
                    المصنع الرئيسي لكل منتج هيخرج لعميلك
                    <br>
                    المستورد الرئيسي لكل منتج معروض عندنا 
                </p>
                <img src="{{asset('img/33.png')}}"  class=" img-fluid">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6 text-center" style="font-size: 53px" data-aos="fade-up-right" data-aos-duration="1000">
                <div > 
                    العموله
                </div>
                <p style="font-size: 27px; line-height: 40px;" >
                    نسبة عمولة بربح عالي جدا
                    <br> 
                </p>
                <img src="{{asset('img/44.png')}}" class=" img-fluid">
            </div>
            <div class="col-md-6 text-center" style="font-size: 53px" data-aos="fade-up-left" data-aos-duration="1000"> 
                <div  > 
                    الشحن
                </div>
                <p style="font-size: 27px; line-height: 40px;" >
                    وشركه الشحن الي هتشحنلك اوردراتك
                    <br>
                    يعني استلام العميل مسؤليتنا احنا واي مشكله
                    <br>
                    تحصل مع العميل هتتواصل معانا  مباشرة
                </p>
                <img src="{{asset('img/55.png')}}"  class=" img-fluid">
            </div>
        </div>




        <div class="row">
            <div class="col-md-6">
                <img src="{{asset('img/6.png')}}" data-aos="zoom-out-right" data-aos-duration="1000" class=" img-fluid">
            </div>
            <div class="col-md-6" style="text-align: end">
                <h1 style="margin-top: 21%;margin-right:1%;font-family: 'Air Strip Arabic';" data-aos="fade-right" data-aos-duration="1000"> <i class="fa fa-flag" style="color:#F5B041"></i>	أسعار منافسه في السوق لأننا الأيد الاولي </h1>
                <p style="margin-right:1%;font-size: 23px" data-aos="fade-up"
                data-aos-anchor-placement="center-bottom" data-aos-duration="1000" >
                    منتجات بأفكار متجدده دايما لكل موسم ومختلفه عن المتواجده في السوق 
                    <br>
                    هتلاقي شغل جديد ديما كل موسم ومناسبه ودا بيساعدك ان العملاء هيفضلوا
                    <br>
                    معاك ديما ف كل المناسبات وكمان هيكبر من مبياعاتك 
                </p>
            </div>
        </div>
 

        <div style=" text-align:center"> 
            <video width="650" height="400" controls data-aos="flip-up" data-aos-duration="1000" class=" img-fluid">
                <source src="{{asset('img/ebtikar_seller.mp4')}}" type="video/mp4"> 
            </video>
            <div class="mt-4">
                <a data-aos-duration="1000" data-aos="fade-left" style=" font-size: 37px; margin: 16px; border-radius: 16px;font-family: 'Air Strip Arabic';" href="{{ route('user.login.form') }}" class="btn btn-warning">تسجيل الدخول</a>
                <button data-aos-duration="1000" data-aos="fade-right" style=" font-size: 37px; margin: 16px; border-radius: 16px;font-family: 'Air Strip Arabic';" 
                type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-warning">ابدأ الأن</button>
            </div>
        </div>
        
        
        <h2 class="text-center mb-4 mt-5" style="font-family: 'Air Strip Arabic';font-size:50px">أراء عملائنا</h2>
        @php
            $generalsetting = \App\Models\GeneralSetting::first();
            $photos = json_decode($generalsetting->photos);
            $count = count($photos);
        @endphp
        
        <div style="background-color: #dcdcdc3b"> 
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active"> 
                        @if(is_array($photos) && $count > 0)
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img class="d-block w-100 img-fluid" style="height:300px" src="{{asset($photos[0])}}" alt="First slide">
                                    </div>
                                    <div class="col-md-6"> 
                                        <img class="d-block w-100 img-fluid" style="height:300px" src="{{asset($photos[1])}}" alt="Second slide">
                                    </div> 
                                </div>
                            </div> 
                        @endif
                    </div>

                    @if(is_array($photos) && $count > 2) 
                        @for ($i = 1 ; $i < ($count - 2) ; $i++) 
                            <div class="carousel-item">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img class="d-block w-100 img-fluid" style="height:300px" src="{{asset($photos[++$i])}}" alt="First slide">
                                        </div>
                                        
                                        <div class="col-md-6"> 
                                            <img class="d-block w-100 img-fluid" style="height:300px" src="{{asset($photos[$i + 1])}}" alt="Second slide">
                                        </div> 
                                    </div>
                                </div> 
                            </div>  
                        @endfor
                    @endif
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: grey; border-radius: 25px;  padding: 23px;"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: grey; border-radius: 25px;  padding: 23px;"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div> 
        </div>
        
        




    @include('frontend.inc.footer') 


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
        

    })
</script>

@if(count($errors) > 0)
    <script>
        $('#exampleModal').modal('toggle');
    </script>
@endif

</body>
</html>

