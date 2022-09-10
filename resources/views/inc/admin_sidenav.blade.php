<!--MAIN NAVIGATION-->
<!--===================================================-->
<nav id="mainnav-container" style="z-index: 99999">
    <div id="mainnav"   @if(Cookie::has('theme'))
                            @if(Cookie::get('theme') == 'light')
                                style="background: url('{{asset('img/side_nav.jpg')}}') repeat-y top left; "
                            @endif
                        @else
                            style="background: url('{{asset('img/side_nav.jpg')}}') repeat-y top left; "
                        @endif>

        <!--Menu-->
        <!--================================-->
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content">
                    <!--Shortcut buttons-->
                    <!--================================-->
                    <div id="mainnav-shortcut" class="hidden">
                        <ul class="list-unstyled shortcut-wrap">
                            <li class="col-xs-3" data-content="My Profile">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-mint">
                                    <i class="demo-pli-male"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Messages">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-warning">
                                    <i class="demo-pli-speech-bubble-3"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Activity">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-success">
                                    <i class="demo-pli-thunder"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Lock Screen">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-purple">
                                    <i class="demo-pli-lock-2"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!--================================-->
                    <!--End shortcut buttons-->

                    @php
                        $user_auth = Auth::user()->user_type;
                        if(!in_array($user_auth,['admin','delivery_man'])){
                            $permissions = json_decode(Auth::user()->staff->role->permissions);
                        }else{
                            $permissions=[];
                        }
                    @endphp

                    <ul id="mainnav-menu" class="list-group">

                        <!--Category name-->
                        {{-- <li class="list-header">Navigation</li> --}}

                        <!--Menu list item-->
                        @if( $user_auth == 'admin' || in_array('1', $permissions) )
                            <li class="{{ areActiveRoutes(['admin.dashboard'])}}">
                                <a class="nav-link" href="{{route('admin.dashboard')}}">
                                    <i class="fa fa-home"></i>
                                    <span class="menu-title">{{__('Dashboard')}}</span>
                                </a>
                            </li>
                        @endif


                        <!-- Product Menu -->
                        @if( $user_auth == 'admin' || in_array('2', $permissions) )
                            <li>
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="menu-title">{{__('Products')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}">
                                        <a class="nav-link" href="{{route('brands.index')}}">{{__('Brand')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])}}">
                                        <a class="nav-link" href="{{route('categories.index')}}">{{__('Category')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['subcategories.index', 'subcategories.create', 'subcategories.edit'])}}">
                                        <a class="nav-link" href="{{route('subcategories.index')}}">{{__('Subcategory')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['subsubcategories.index', 'subsubcategories.create', 'subsubcategories.edit'])}}">
                                        <a class="nav-link" href="{{route('subsubcategories.index')}}">{{__('Sub Subcategory')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['products.index', 'products.create', 'products.edit'])}}">
                                        <a class="nav-link" href="{{route('products.index')}}">{{__('Products')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['attributes.index','attributes.create','attributes.edit'])}}">
                                        <a class="nav-link" href="{{route('attributes.index')}}">{{__('Attribute')}}</a>
                                    </li>

                                    <li class="{{ areActiveRoutes(['product_bulk_export.export'])}}">
                                        <a class="nav-link" href="{{route('product_bulk_export.index')}}">{{__('Bulk Export')}}</a>
                                    </li>
                                    @php
                                        $review_count = DB::table('reviews')
                                                    ->orderBy('code', 'desc')
                                                    ->join('products', 'products.id', '=', 'reviews.product_id')
                                                    ->where('products.user_id', Auth::user()->id)
                                                    ->where('reviews.viewed', 0)
                                                    ->select('reviews.id')
                                                    ->distinct()
                                                    ->count();
                                    @endphp
                                    <li class="{{ areActiveRoutes(['admin.reviews.index'])}}">
                                        <a class="nav-link" href="{{route('admin.reviews.index')}}">{{__('Product Reviews')}}@if($review_count > 0)<span class="pull-right badge badge-info">{{ $review_count }}</span>@endif</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if( $user_auth == 'admin' || in_array('3', $permissions) )
                            <li class="{{ areActiveRoutes(['calender.admin'])}}">
                                <a class="nav-link" href="{{ route('calender.admin') }}">
                                    <i class="fa fa-calendar"></i>
                                    <span class="menu-title">{{__('Calender')}}</span>
                                </a>
                            </li>
                        @endif

                        @if( $user_auth == 'admin' || in_array('18', $permissions) )
                        <li>
                            <a href="#">
                                <i class="fa fa-paint-brush"></i>
                                <span class="menu-title">{{__('Designers')}}</span>
                                <i class="arrow"></i>
                            </a>
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['mockups.index', 'mockups.create', 'mockups.edit'])}}">
                                    <a class="nav-link" href="{{ route('mockups.index') }}">
                                        <span class="menu-title">{{__('Mockups')}}</span>
                                    </a>
                                </li>
                                <li class="{{ areActiveRoutes(['admin.listings.index'])}}">
                                    <a class="nav-link" href="{{ route('admin.listings.index') }}">
                                        <span class="menu-title">{{__('Listings')}}</span>
                                    </a>
                                </li>
                                <li class="{{ areActiveRoutes(['designers.index', 'designers.create', 'designers.edit'])}}">
                                    <a class="nav-link" href="{{ route('designers.index') }}">
                                        <span class="menu-title">{{__('Designers')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if( $user_auth == 'admin' ||
                            in_array('4', $permissions) ||
                            in_array('5', $permissions) ||
                            in_array('6', $permissions) ||
                            in_array('19', $permissions)||
                            in_array('8', $permissions)  )
                            <li>
                                <a href="#">
                                    <i class="fa fa-folder-open"></i>
                                    <span class="menu-title">{{__('Receipts')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">

                                    @if( $user_auth == 'admin' || in_array('19', $permissions) )

                                        <li class=" ">
                                            <a href="#">
                                                <span class="menu-title">{{__('Social Receipt')}}</span>
                                                <i class="arrow"></i>
                                            </a>

                                            <!--Submenu-->
                                            <ul class="collapse">


                                                <li class="@if(request()->is('admin/receipt/social/index/social/all') || request()->is('admin/receipt/social/index/add/social')) active-link @endif">
                                                    <a class="nav-link" href="{{ route('receipt.social',['receipt_type' => 'social' , 'confirm'=>'all' ]) }}">الكل</a>
                                                </li>
                                                <li class="@if(request()->is('admin/receipt/social/index/social/1')) active-link @endif">
                                                    <a class="nav-link" href="{{route('receipt.social',['receipt_type' => 'social' , 'confirm'=>1 ]) }}">تم التأكيد</a>
                                                </li>
                                                <li class="@if(request()->is('admin/receipt/social/index/social/0')) active-link @endif">
                                                    <a class="nav-link" href="{{route('receipt.social',['receipt_type' => 'social' , 'confirm'=>0 ]) }}">لم يتم التأكيد</a>
                                                </li>
                                            </ul>

                                        </li>
                                    @endif

                                    @if( $user_auth == 'admin' || in_array('24', $permissions) )

                                        <li class=" ">
                                            <a href="#">
                                                <span class="menu-title">{{__('Figures Receipt')}}</span>
                                                <i class="arrow"></i>
                                            </a>

                                            <!--Submenu-->
                                            <ul class="collapse">

                                                <li class="@if(request()->is('admin/receipt/social/index/figures/all') || request()->is('admin/receipt/social/index/add/figures')) active-link @endif">
                                                    <a class="nav-link" href="{{ route('receipt.social',['receipt_type' => 'figures' , 'confirm'=>'all' ]) }}">الكل</a>
                                                </li>
                                                <li class="@if(request()->is('admin/receipt/social/index/figures/1')) active-link @endif">
                                                    <a class="nav-link" href="{{route('receipt.social',['receipt_type' => 'figures' , 'confirm'=>1 ]) }}">تم التأكيد</a>
                                                </li>
                                                <li class="@if(request()->is('admin/receipt/social/index/figures/0')) active-link @endif">
                                                    <a class="nav-link" href="{{route('receipt.social',['receipt_type' => 'figures' , 'confirm'=>0 ]) }}">لم يتم التأكيد</a>
                                                </li>
                                            </ul>

                                        </li>
                                    @endif

                                    @if( $user_auth == 'admin' || in_array('4', $permissions) )
                                        <li class="{{ areActiveRoutes(['receipt.company','receipt.company.add','receipt.company.edit'])}}">
                                            <a class="nav-link" href="{{ route('receipt.company') }}">{{__('Company Receipts')}}</a>
                                        </li>
                                    @endif
                                    @if( $user_auth == 'admin' || in_array('5', $permissions) )
                                        <li class="{{ areActiveRoutes(['receipt.client','receipt.client.add','receipt.client.edit','receipt.client.edit_product'])}}">
                                            <a class="nav-link" href="{{route('receipt.client')}}">{{__('Clients Receipt')}}</a>
                                        </li>
                                    @endif

                                    @if($user_auth == 'admin' || in_array('8', $permissions))
                                        <li class="@if(str_contains(url()->current(),'admin/orders/customer')) active-link @endif" >
                                            <a class="nav-link" href="{{ route('admin.orders.index','customer') }}">
                                                {{__('Customers Orders')}}
                                            </a>
                                        </li>
                                        <li class="@if(str_contains(url()->current(),'admin/orders/seller')) active-link @endif" >
                                            <a class="nav-link" href="{{ route('admin.orders.index','seller') }}">
                                                {{__('Sellers Orders')}}
                                            </a>
                                        </li>
                                    @endif

                                    @if( $user_auth == 'admin' || in_array('6', $permissions) )
                                        <li class="{{ areActiveRoutes(['receipt.outgoings','receipt.outgoings.add','receipt.outgoings.edit','receipt.outgoings.edit_product'])}}">
                                            <a class="nav-link" href="{{route('receipt.outgoings')}}">{{__('Outgoings Receipt')}}</a>
                                        </li>
                                    @endif


                                    @if( $user_auth == 'admin' || in_array('23', $permissions) )
                                        <li class="{{ areActiveRoutes(['receipt.price_view','receipt.price_view.add','receipt.price_view.edit'])}}">
                                            <a class="nav-link" href="{{route('receipt.price_view')}}">{{__('Price View Receipt')}}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if( $user_auth == 'admin' ||
                            in_array('20', $permissions) ||
                            in_array('21', $permissions) ||
                            in_array('22', $permissions) )
                            <li>
                                <a href="#">
                                    <i class="fa fa-align-left"></i>
                                    <span class="menu-title">قوائم التشغيل</span>
                                    <i class="arrow"></i>
                                </a>
                                <ul class="collapse">
                                    @if( $user_auth == 'admin' || in_array('20', $permissions) )
                                        <li class="@if(str_contains(url()->current(),'admin/playlist/design')) active-link @endif">
                                            <a class="nav-link" href="{{route('playlist.index','design')}}">
                                                <span class="menu-title">الديزاين</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if( $user_auth == 'admin' || in_array('21', $permissions) )
                                        <li class="@if(str_contains(url()->current(),'admin/playlist/manufacturing')) active-link @endif">
                                            <a class="nav-link" href="{{route('playlist.index','manufacturing')}}">
                                                <span class="menu-title">تصنيع</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if( $user_auth == 'admin' || in_array('22', $permissions) )
                                        <li class="@if(str_contains(url()->current(),'admin/playlist/prepare')) active-link @endif">
                                            <a class="nav-link" href="{{route('playlist.index','prepare')}}">
                                                <span class="menu-title">تجهيز</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if($user_auth == 'admin' || in_array('7', $permissions))
                            <li class="{{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
                                <a class="nav-link" href="{{ route('flash_deals.index') }}">
                                    <i class="fa fa-bolt"></i>
                                    <span class="menu-title">{{__('Flash Deal')}}</span>
                                </a>
                            </li>
                        @endif



                        @if(\App\Models\GeneralSetting::first()->delivery_system == 'ebtekar')
                            @if($user_auth == 'admin' || $user_auth == 'delivery_man' || in_array('9', $permissions ))
                            <li>
                                <a href="#">
                                    <i class="fa fa-truck"></i>
                                    <span class="menu-title">{{__('Delivery Orders')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">

                                    <li class="@if(str_contains(url()->current(),'deliveryman/orders/on_delivery')) active-link @endif">
                                        <a class="nav-link" href="{{ route('deliveryman.orders.index','on_delivery') }}">
                                            <span class="menu-title">{{__('With Delivery Man')}} </span>
                                        </a>
                                    </li>
                                    <li class="@if(str_contains(url()->current(),'deliveryman/orders/delivered')) active-link @endif">
                                        <a class="nav-link" href="{{ route('deliveryman.orders.index','delivered') }}">
                                            <span class="menu-title">{{__('Delivered')}} </span>
                                        </a>
                                    </li>
                                    <li class="@if(str_contains(url()->current(),'deliveryman/orders/cancel')) active-link @endif">
                                        <a class="nav-link" href="{{ route('deliveryman.orders.index','cancel') }}">
                                            <span class="menu-title">{{__('Canceld orders')}} </span>
                                        </a>
                                    </li>
                                    <li class="@if(str_contains(url()->current(),'deliveryman/orders/delay')) active-link @endif">
                                        <a class="nav-link" href="{{ route('deliveryman.orders.index','delay') }}">
                                            <span class="menu-title">{{__('Delay orders')}} </span>
                                        </a>
                                    </li>
                                    <li class="@if(str_contains(url()->current(),'deliveryman/orders/supplied')) active-link @endif">
                                        <a class="nav-link" href="{{ route('deliveryman.orders.index','supplied') }}">
                                            <span class="menu-title">{{__('Supplied')}} </span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            @endif
                        @endif

                        @if(\App\Models\GeneralSetting::first()->delivery_system == 'ebtekar')
                            @if($user_auth == 'admin' || in_array('16', $permissions))
                                <li class="{{ areActiveRoutes(['deliveryman.index'])}}">
                                    <a class="nav-link" href="{{ route('deliveryman.index') }}">
                                        <i class="fa fa-user-secret"></i>
                                        <span class="menu-title">{{__('DeliveryMan List')}}</span>
                                    </a>
                                </li>
                            @endif
                        @endif

                        @if($user_auth == 'admin' || in_array('10', $permissions))
                            <li>
                                <a href="#">
                                    <i class="fa fa-user-plus"></i>
                                    <span class="menu-title">{{__('Sellers')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['sellers.index', 'sellers.create', 'sellers.edit', 'sellers.payment_history','sellers.approved','sellers.profile_modal'])}}">
                                        <a class="nav-link" href="{{route('sellers.index')}}">{{__('Seller List')}} </a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['common_questions.index','common_questions.create','common_questions.edit'])}}">
                                        <a class="nav-link" href="{{ route('common_questions.index') }}">{{__('Common Questions')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['orders.request_commission.index', 'orders.request_commission.edit'])}}">
                                        <a class="nav-link" href="{{ route('orders.request_commission.index') }}">
                                            <span class="menu-title">{{__('Commission Requests')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif




                        @if($user_auth == 'admin' || in_array('17', $permissions))
                            <li class="{{ areActiveRoutes(['customer.index'])}}">
                                <a class="nav-link" href="{{ route('customer.index') }}">
                                    <i class="fa fa-user"></i>
                                    <span class="menu-title">{{__('Customer List')}}</span>
                                </a>
                            </li>
                        @endif

                        @if($user_auth == 'admin' || in_array('11', $permissions))
                            @php
                                $conversation = \App\Models\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', '0')->get();
                            @endphp
                            <li class="{{ areActiveRoutes(['admin.conversation.index','admin.conversation.show'])}}">
                                <a class="nav-link" href="{{ route('admin.conversation.index') }}">
                                    <i class="fa fa-envelope"></i>
                                    <span class="menu-title">{{__('Conversations')}}
                                        @if(count($conversation))
                                            <span class="pull-right badge badge-info">
                                                    {{count($conversation)}}
                                            </span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endif


                        @if($user_auth == 'admin' || in_array('12', $permissions))
                            <li>
                                <a href="#">
                                    <i class="fa fa-desktop"></i>
                                    <span class="menu-title">{{__('Settings')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['home_settings.index', 'home_banners.index', 'sliders.index', 'home_categories.index', 'home_banners.create', 'home_categories.create', 'home_categories.edit', 'sliders.create'])}}">
                                        <a class="nav-link" href="{{route('home_settings.index')}}">{{__('Home')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['social.index','social.create','social.edit'])}}">
                                        <a class="nav-link" href="{{route('social.index')}}">سوشيال</a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="menu-title">{{__('Policy Pages')}}</span>
                                            <i class="arrow"></i>
                                        </a>

                                        <!--Submenu-->
                                        <ul class="collapse">

                                            <li class="{{ areActiveRoutes(['sellerpolicy.index'])}}">
                                                <a class="nav-link" href="{{route('sellerpolicy.index', 'seller_policy')}}">{{__('Seller Policy')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['returnpolicy.index'])}}">
                                                <a class="nav-link" href="{{route('returnpolicy.index', 'return_policy')}}">{{__('Return Policy')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['supportpolicy.index'])}}">
                                                <a class="nav-link" href="{{route('supportpolicy.index', 'support_policy')}}">{{__('Support Policy')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['terms.index'])}}">
                                                <a class="nav-link" href="{{route('terms.index', 'terms')}}">{{__('Terms & Conditions')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['privacypolicy.index'])}}">
                                                <a class="nav-link" href="{{route('privacypolicy.index', 'privacy_policy')}}">{{__('Privacy Policy')}}</a>
                                            </li>
                                        </ul>

                                    </li>
                                    <li class="{{ areActiveRoutes(['generalsettings.index'])}}">
                                        <a class="nav-link" href="{{route('generalsettings.index')}}">{{__('General Settings')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['countries.index'])}}">
                                        <a class="nav-link" href="{{route('countries.index')}}">{{__('Shipping Countries Settings')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['generalsettings.logo'])}}">
                                        <a class="nav-link" href="{{route('generalsettings.logo')}}">{{__('Logo Settings')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['generalsettings.color'])}}">
                                        <a class="nav-link" href="{{route('generalsettings.color')}}">{{__('Color Settings')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}">
                                        <a class="nav-link" href="{{route('languages.index')}}">{{__('Languages')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['banned_phones.index', 'banned_phones.create', 'banned_phones.store', 'banned_phones.show', 'banned_phones.edit'])}}">
                                        <a class="nav-link" href="{{route('banned_phones.index')}}">{{__('Banned Phones')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if($user_auth == 'admin' || in_array('13', $permissions))
                            <li class="{{ areActiveRoutes(['seosetting.index'])}}">
                                <a class="nav-link" href="{{ route('seosetting.index') }}">
                                    <i class="fa fa-search"></i>
                                    <span class="menu-title">{{__('SEO Setting')}}</span>
                                </a>
                            </li>
                        @endif

                        @if($user_auth == 'admin' || in_array('14', $permissions))
                            <li>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    <span class="menu-title">{{__('Staffs')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                        <a class="nav-link" href="{{ route('staffs.index') }}">{{__('All staffs')}}</a>
                                    </li>
                                    @if($user_auth == 'admin')
                                    <li class="{{ areActiveRoutes(['admins.index', 'admins.create', 'admins.edit'])}}">
                                        <a class="nav-link" href="{{ route('admins.index') }}">{{__('All Admins')}}</a>
                                    </li>
                                    @endif
                                    <li class="{{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                        <a class="nav-link" href="{{route('roles.index')}}">{{__('Staff permissions')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['quality_responsible.show'])}}">
                                        <a class="nav-link" href="{{route('quality_responsible.show')}}">{{__('Quality Responsible')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['borrow.index','borrow.edit'])}}">
                                        <a class="nav-link" data-toggle="modal" data-target="#borrow" href="#">الخصومات والسلف</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['tasks.index', 'tasks.create', 'tasks.edit'])}}">
                                        <a class="nav-link" href="{{route('tasks.index')}}">{{__('Tasks')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['auditlogs', 'auditlogs.show'])}}">
                                        <a class="nav-link" href="{{route('auditlogs')}}">{{__('Audit Logs')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if( !in_array($user_auth,['admin','delivery_man']))
                            <li>
                                <a href="#">
                                    <i class="fa fa-tasks"></i>
                                    <span class="menu-title">{{__('My Tasks')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['tasks.in_progress'])}}">
                                        <a class="nav-link" href="{{ route('tasks.in_progress') }}">In Progress</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['tasks.done'])}}">
                                        <a class="nav-link" href="{{route('tasks.done')}}">Done</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['tasks.out_date'])}}">
                                        <a class="nav-link" href="{{route('tasks.out_date')}}">Out Date</a>
                                    </li>
                                </ul>
                            </li>
                        @endif


                    </ul>
                </div>
            </div>
        </div>
        <!--================================-->
        <!--End menu-->

    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="borrow" tabindex="-1" aria-labelledby="borrowLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowLabel">{{__('Borrows')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('borrow.all')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" required placeholder="Password">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


