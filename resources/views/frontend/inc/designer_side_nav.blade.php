<div class="sidebar sidebar--style-3 no-border stickyfill p-0">
    <div class="widget mb-0">
        <div class="widget-profile-box text-center p-3">
            @if (Auth::user()->avatar_original != null)
                <div class="image" style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
            @else
                <img src="{{ asset('frontend/images/user.png') }}" class="image rounded-circle">
            @endif  
                <div class="name mb-0">{{ Auth::user()->name }} <span class="ml-2"></div> 
                <div class="text-center badge badge-danger" style="color: whitesmoke">
                    Designer
                </div>
        </div>
        <div class="sidebar-widget-title py-3">
            <span>{{__('Menu')}}</span>
        </div>
        <div class="widget-profile-menu py-3">
            <ul class="categories categories--style-3"> 
                <li>
                    <a href="{{ route('home') }}" >
                        <i class="la la-dashboard" style="font-size: 18px !important"></i>
                        <span class="category-name">
                            {{__('Continue to Shipping')}} 
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
                    <a href="{{ route('user.orders.index') }}" class="{{ areActiveRoutesHome(['user.orders.index','dashboard'])}}">
                        <i class="la la-file-text" style="font-size: 18px !important"></i>
                        <span class="category-name">
                            {{__('Orders')}} 
                        </span>
                    </a>
                </li>
                
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
