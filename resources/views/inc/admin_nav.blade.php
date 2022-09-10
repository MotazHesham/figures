<header id="navbar">
    <div id="navbar-container" class="boxed">

        @php
            $generalsetting = \App\Models\GeneralSetting::first();
        @endphp


        <div class="navbar-header">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                @if ($generalsetting->logo != null)
                    <img loading="lazy" src="{{ asset($generalsetting->admin_logo) }}" class="brand-icon"
                        alt="{{ $generalsetting->site_name }}">
                @else
                    <img loading="lazy" src="{{ asset('img/logo_shop.png') }}" class="brand-icon"
                        alt="{{ $generalsetting->site_name }}">
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


                <li id="dropdown-user" class="dropdown">
                    <a href="https://waslaeg.com" data-toggle="modal" class="dropdown-toggle text-right">
                        <i class="fa fa-magic"></i>
                        <b class="non-content"> الدخول لوصلة</b>
                    </a>
                </li>



                <!--Theme dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li id="dropdown-user0" class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                        <i class="fa fa-bookmark"></i>
                        <b class="non-content">{{ __('Theme') }}</b>
                    </a>

                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                        <ul class="head-list">
                            <li>
                                <a href="{{ route('change.theme', 'dark') }}"><i class=" icon-lg icon-fw"> </i>
                                    {{ __('Dark Mood') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('change.theme', 'light') }}"><i class=" icon-lg icon-fw"></i>
                                    {{ __('Light Mood') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End Theme dropdown-->

                @php

                    $calender = \App\Models\Calender::all();
                    $now = date('Y-m-d');
                    global $count, $z, $y;
                    $count = 0;
                    $z = [];
                    $y = [];
                    for ($i = 0; $i <= 10; $i++) {
                        $z[] = date('Y-m-d', strtotime($now . ' + ' . $i . 'days'));
                    }

                    $myEvents = $calender->transform(function ($value, $key) {
                        $GLOBALS['y'][] = date('Y-m-d', $value['date']);
                    });

                    $ww = array_count_values($y);
                    $oo = 0;
                    if (Auth::user()->user_type == 'admin') {
                        foreach ($z as $raw) {
                            foreach ($ww as $key => $raw2) {
                                if ($raw == $key) {
                                    $oo = 1;
                                }
                            }
                        };
                    }

                @endphp

                <li class="dropdown" id="lang-change">
                    @php
                        if (Session::has('locale')) {
                            $locale = Session::get('locale', Config::get('app.locale'));
                        } else {
                            $locale = 'eg';
                        }
                    @endphp
                    @if (\App\Models\Language::where('code', $locale)->first() != null)
                        <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                            <img loading="lazy" src="{{ asset('frontend/images/icons/flags/' . $locale . '.png') }}"
                                class="flag" style="margin-right:6px;"><span
                                class="language"><b class="non-content"> {{ \App\Models\Language::where('code', $locale)->first()->name }} </b></span>
                        </a>
                    @endif
                    <ul class="dropdown-menu">
                        @foreach (\App\Models\Language::all() as $key => $language)
                            <li class="dropdown-item @if ($locale == $language) active @endif">
                                <a href="#" data-flag="{{ $language->code }}"><img loading="lazy"
                                        src="{{ asset('frontend/images/icons/flags/' . $language->code . '.png') }}"
                                        class="flag" style="margin-right:6px;"><span
                                        class="language">{{ $language->name }}</span></a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                @php
                    $user_alerts = \App\Models\UserAlert::orderBy('created_at', 'desc')->where(function ($query) {
                        $query->where('type', 'private')->where('user_id', Auth::user()->id);
                    });
                    $count_user_alerts = \App\Models\UserAlert::orderBy('created_at', 'desc')->where(function ($query) {
                        $query->where('type', 'private')->where('user_id', Auth::user()->id);
                    });

                    if (Auth::user()->user_type == 'staff' && Auth::user()->notification_show == 1) {
                        $user_alerts = $user_alerts->orWhereIn('type', ['playlist', 'orders', 'register', 'designs', 'commission']);
                        $count_user_alerts = $count_user_alerts->orWhereIn('type', ['playlist', 'orders', 'register', 'designs', 'commission']);
                    } elseif (Auth::user()->user_type == 'admin') {
                        $user_alerts = $user_alerts->orWhereIn('type', ['playlist', 'orders', 'register', 'designs', 'commission']);
                        $count_user_alerts = $count_user_alerts->orWhereIn('type', ['playlist', 'orders', 'register', 'designs', 'commission']);
                    }

                    $conversations = \App\Models\Conversation::with(['receiver', 'sender'])
                        ->where('sender_id', auth()->user()->id)
                        ->orderBy('sender_viewed')
                        ->orderBy('updated_at', 'desc');
                    $count_conversations = \App\Models\Conversation::with(['receiver', 'sender'])
                        ->where('sender_id', auth()->user()->id)
                        ->where('sender_viewed', 0)
                        ->count();
                @endphp

                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true">
                        <i class="fab fa-facebook-messenger"></i>
                        @if ($count_conversations != 0)
                            <span class="badge badge-header badge-danger">{{ $count_conversations }}</span>
                        @endif
                    </a>

                    <!--Notification dropdown menu-->
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="opacity: 1;">
                        <div class="nano scrollable has-scrollbar" style="height: 265px; ">
                            <div class="nano-content" tabindex="0" style="right: -17px;">
                                <ul class="head-list">

                                    @foreach ($conversations->paginate(15) as $raw)
                                        @php
                                            $user = $raw->receiver;
                                            $message = $raw
                                                ->messages()
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp

                                        @if ($user)
                                            <li>
                                                <a class="media"
                                                    href="{{ route('admin.conversation.show', encrypt($raw->id)) }}"
                                                    style="position:relative">
                                                    @if ($raw->sender_viewed == 0)
                                                        <span class="badge badge-header badge-success"
                                                            style="right:auto;left:0;"></span>
                                                    @endif
                                                    <div class="media-body">
                                                        @if ($user)
                                                            @if ($user->avatar_original != null)
                                                                <img height="30" width="30" style="border-radius:50%;"
                                                                    src="{{ asset($user->avatar_original) }}">
                                                            @else
                                                                <img height="30" width="30" style=""
                                                                    src="{{ asset('frontend/images/user.png') }}">
                                                            @endif
                                                        @else
                                                            <img height="30" width="30" style=""
                                                                src="{{ asset('frontend/images/user.png') }}">
                                                        @endif
                                                        <small
                                                            class="mar-no text-nowrap text-main text-semibold">{{ $user->email ?? '' }}</small>
                                                        <br>
                                                        <small class="text-dark">{{ $message->message }}</small>
                                                    </div>
                                                    <small
                                                        style="float:right;@if ($raw->sender_viewed == 0) color:#16691c;font-weight:bolder @endif">{{ calculate_diff_date($message->created_at) }}</small>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach

                                    <li>
                                        <a class="media" href="{{ route('admin.conversation.index') }}"
                                            style="position:relative">
                                            <div class="media-body text-center">
                                                <p class="mar-no text-nowrap text-main text-semibold">أظهار الكل</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="nano-pane" style="">
                                <div class="nano-slider" style="height: 170px; transform: translate(0px, 0px);">
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true">
                        <i class="demo-pli-bell"></i>
                        @if ($count_user_alerts->where('seen', 0)->count() != 0)
                            <span
                                class="badge badge-header badge-danger">{{ $count_user_alerts->where('seen', 0)->count() }}</span>
                        @endif
                    </a>

                    <!--Notification dropdown menu-->
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="opacity: 1;">
                        <div class="nano scrollable has-scrollbar" style="height: 265px; ">
                            <div class="nano-content" tabindex="0" style="right: -17px;">
                                <ul class="head-list">

                                    <li>
                                        <a class="media" href="{{ route('alerts.all') }}"
                                            style="position:relative">
                                            <div class="media-body text-center">
                                                <p class="mar-no text-nowrap text-main text-semibold">أظهار الكل</p>
                                            </div>
                                        </a>
                                    </li>

                                    @if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')
                                        @foreach ($z as $raw)
                                            @foreach ($ww as $key => $raw2)
                                                @if ($raw == $key)
                                                    <li>
                                                        <a class="media"
                                                            href="{{ route('calender.admin.by.date', $raw) }}"
                                                            style="position:relative">
                                                            <span class="badge badge-header badge-info"
                                                                style="right:auto;left:0;"></span>
                                                            <div class="media-body">
                                                                <p class="mar-no text-nowrap text-main text-semibold">
                                                                    ({{ $raw2 }}) New events in
                                                                    {{ format_Date(strtotime($raw)) }} </p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif

                                    @foreach ($user_alerts->paginate(15) as $alert)
                                        <li>
                                            <a class="media" href="{{ $alert->alert_link }}"
                                                style="position:relative"
                                                onclick="notification_seen({{ $alert->id }})">
                                                @if ($alert->seen == 0)
                                                    <span class="badge badge-header badge-success"
                                                        style="right:auto;left:0;"></span>
                                                @endif
                                                <div class="media-body">
                                                    <p class="mar-no text-nowrap text-main text-semibold">
                                                        {{ $alert->alert_text }}</p>
                                                </div>
                                                <small
                                                    style="float:right;@if ($alert->seen == 0) color:#16691c;font-weight:bolder @endif">{{ calculate_diff_date($alert->created_at) }}</small>
                                            </a>
                                        </li>
                                    @endforeach

                                    @if ($user_alerts->count() > 15)
                                        <li>
                                            <a class="media" href="{{ route('alerts.all') }}"
                                                style="position:relative">
                                                <div class="media-body text-center">
                                                    <p class="mar-no text-nowrap text-main text-semibold">أظهار الكل</p>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="nano-pane" style="">
                                <div class="nano-slider" style="height: 170px; transform: translate(0px, 0px);">
                                </div>
                            </div>
                        </div>
                    </div>
                </li>



                <!--User dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li id="dropdown-user" class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                        <span class="ic-user pull-right">
                            @if (auth()->user()->avatar_original)
                                <img height="50" width="50" src="{{ asset(auth()->user()->avatar_original) }} "
                                    style="border-radius: 50px">
                            @else

                                <img height="40" width="40" src="{{ asset('frontend/images/user.png') }}"
                                    style="border-radius: 50px">
                            @endif
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                        <ul class="head-list">
                            <li>
                                <a href="{{route('user.orders.index')}}">
                                    {{ __('Orders') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('profile.index') }}"><i class="demo-pli-male icon-lg icon-fw"></i>
                                    {{ __('Profile') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"><i class="demo-pli-unlock icon-lg icon-fw"></i>
                                    {{ __('Logout') }}</a>
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
