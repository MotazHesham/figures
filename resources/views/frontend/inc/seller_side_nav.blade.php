<div class="sidebar sidebar--style-3 no-border stickyfill p-0">
    <div class="widget mb-0">
        <div class="widget-profile-box text-center p-3">
            @if (Auth::user()->avatar_original != null)
                <div class="image" style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
            @else
                <img src="{{ asset('frontend/images/user.png') }}" class="image rounded-circle">
            @endif
            @if(Auth::user()->user_type == 'seller' && Auth::user()->seller->verification_status == 1)
                <div class="name mb-0">{{ Auth::user()->name }} <span class="ml-2"><i class="fa fa-check-circle" style="color:green"></i></span></div>
            @else
                <div class="name mb-0">{{ Auth::user()->name }} <span class="ml-2"><i class="fa fa-times-circle" style="color:red"></i></span></div>
            @endif
            <div class="text-center badge badge-success">
                @php
                    $seller = \App\Models\Seller::where('user_id',Auth::id())->first();
                @endphp
                @if($seller && $seller->seller_type == 'social')
                    Social 
                @else 
                    Seller
                @endif
            </div>
            <br>
            <br>
            @if($seller && $seller->seller_type == 'social')
                <span class="badge badge-info">كود الخصم {{ $seller->discount_code }}</span>
                <span class="badge badge-warning"> <b>{{ $seller->discount }}%</b></span> 
            @endif
        </div>
        <div class="sidebar-widget-title py-3">
            <span>{{__('Menu')}}</span>
        </div>
        <div class="widget-profile-menu py-3">
            <ul class="categories categories--style-3">
                <li>
                    <a href="{{ route('user.orders.index') }}" class="{{ areActiveRoutesHome(['user.orders.index','dashboard'])}}">
                        <i class="la la-file-text" style="font-size: 18px !important"></i>
                        <span class="category-name">
                            {{__('Orders')}} 
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" >
                        <i class="la la-dashboard" style="font-size: 18px !important"></i>
                        <span class="category-name">
                            {{__('Continue to Shipping')}} 
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
                
                <li>
                    <a href="{{ route('profile') }}" class="{{ areActiveRoutesHome(['profile'])}}">
                        <i class="la la-user" style="font-size: 18px !important"></i>
                        <span class="category-name">
                            {{__('Profile')}}
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                        <i class="la la-heart-o" style="font-size: 18px !important"></i>
                        <span class="category-name">
                            {{__('Wishlist')}}
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

                <li>
                    <a href="{{ route('seller.common_questions') }}" class="{{ areActiveRoutesHome(['seller.common_questions'])}}">
                        <i class="la la-user" style="font-size: 18px !important"></i>
                        <span class="category-name">
                            {{__('Common Questions')}}
                        </span>
                    </a>
                </li>
        
            </ul>
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
        </div>

    </div>
</div>
