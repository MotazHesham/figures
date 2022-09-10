<header id="navbar">
    <div id="navbar-container" class="boxed">

        @php
            $generalsetting = \App\Models\GeneralSetting::first();
        @endphp


        <div class="navbar-header">
            <a href="{{route('deliveryman.orders.index','on_delivery')}}" class="navbar-brand">
                @if($generalsetting->logo != null)
                    <img loading="lazy"  src="{{ asset($generalsetting->admin_logo) }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                @else
                    <img loading="lazy"  src="{{ asset('img/logo_shop.png') }}" class="brand-icon" alt="{{ $generalsetting->site_name }}">
                @endif
                <div class="brand-title">
                    <span class="brand-text">{{ $generalsetting->site_name }}</span>
                </div>
            </a>
        </div>

        <div class="navbar-content">

            <ul class="nav navbar-top-links">

                <li class="tgl-menu-btn">
                    <a class="mainnav-toggle" href="#">
                        <i class="demo-pli-list-view"></i>
                    </a>
                </li>



            </ul>
            <ul class="nav navbar-top-links"> 

                <li class="dropdown" id="lang-change">
                    @php
                        if(Session::has('locale')){
                            $locale = Session::get('locale', Config::get('app.locale'));
                        }
                        else{
                            $locale = 'eg';
                        }
                    @endphp
                    @if(\App\Models\Language::where('code', $locale)->first() != null)
                        <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                            <img loading="lazy"  src="{{ asset('frontend/images/icons/flags/'.$locale.'.png') }}" class="flag" style="margin-right:6px;"><span class="language">{{ \App\Models\Language::where('code', $locale)->first()->name }}</span>
                        </a>
                    @endif
                    <ul class="dropdown-menu">
                        @foreach (\App\Models\Language::all() as $key => $language)
                            <li class="dropdown-item @if($locale == $language) active @endif">
                                <a href="#" data-flag="{{ $language->code }}"><img loading="lazy"  src="{{ asset('frontend/images/icons/flags/'.$language->code.'.png') }}" class="flag" style="margin-right:6px;"><span class="language">{{ $language->name }}</span></a>
                            </li>
                        @endforeach
                    </ul>
                </li>


                <!--User dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li id="dropdown-user" class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                        <span class="ic-user pull-right">
                            @if(auth()->user()->avatar_original)
                                <img height="50" width="50" src="{{ asset(auth()->user()->avatar_original) }} " style="border-radius: 50px">
                            @else 
                            
                                <img height="40" width="40" src="{{asset('frontend/images/user.png')}}" style="border-radius: 50px">
                            @endif
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                        <ul class="head-list">
                            <li>
                                <a href="{{ route('profile.index') }}"><i class="demo-pli-male icon-lg icon-fw"></i> {{__('Profile')}}</a>
                            </li>
                            <li>
                                <a href="{{ route('logout')}}"><i class="demo-pli-unlock icon-lg icon-fw"></i> {{__('Logout')}}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End user dropdown-->
            </ul>
        </div>
        <!--================================-->
        <!--End Navbar Dropdown-->

    </div>
</header>
