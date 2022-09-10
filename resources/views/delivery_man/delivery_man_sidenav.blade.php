<!--MAIN NAVIGATION-->
<!--===================================================-->
<nav id="mainnav-container" style="z-index: 99999">
    <div id="mainnav" style="background: url('{{asset('img/side_nav.jpg')}}') repeat-y top left; ">

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

                    <ul id="mainnav-menu" class="list-group">

                        <!--Category name-->
                        {{-- <li class="list-header">Navigation</li> --}}

                        <!--Menu list item--> 

                        <li class="@if(str_contains(url()->current(),'admin/delivery_man/orders/on_delivery')) active-link @endif">
                            <a class="nav-link" href="{{ route('deliveryman.orders.index','on_delivery') }}">
                                <i class="fa fa-truck"></i>
                                <span class="menu-title">{{__('With Delivery Man')}}</span>
                            </a>
                        </li> 

                        <li class="@if(str_contains(url()->current(),'admin/delivery_man/orders/delivered')) active-link @endif">
                            <a class="nav-link" href="{{ route('deliveryman.orders.index','delivered') }}">
                                <i class="fa fa-check"></i>
                                <span class="menu-title">{{__('Delivered')}}</span>
                            </a>
                        </li>  
                        <li class="@if(str_contains(url()->current(),'admin/delivery_man/orders/cancel')) active-link @endif">
                            <a class="nav-link" href="{{ route('deliveryman.orders.index','cancel') }}">
                                <i class="fa fa-ban"></i>
                                <span class="menu-title">{{__('Canceld orders')}} </span>
                            </a>
                        </li>
                        <li class="@if(str_contains(url()->current(),'admin/delivery_man/orders/delay')) active-link @endif">
                            <a class="nav-link" href="{{ route('deliveryman.orders.index','delay') }}">
                                <i class="fa fa-pause-circle"></i>
                                <span class="menu-title">{{__('Delay orders')}} </span>
                            </a>
                        </li>
                        <li class="@if(str_contains(url()->current(),'admin/delivery_man/orders/supplied')) active-link @endif">
                            <a class="nav-link" href="{{ route('deliveryman.orders.index','supplied') }}">
                                <i class="fa fa-check-square"></i>
                                <span class="menu-title">{{__('Supplied')}} </span>
                            </a>
                        </li> 
                            

                    </ul>
                </div>
            </div>
        </div>
        <!--================================-->
        <!--End menu-->

    </div>
</nav>


