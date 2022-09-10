<a href="" class="nav-box-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="la la-shopping-cart d-inline-block nav-box-icon"></i>
    <span class="nav-box-text d-none d-xl-inline-block">{{__('Cart')}}</span>
    @if(auth()->user()->carts)
        <span class="nav-box-number">{{ count(auth()->user()->carts) }}</span>
    @else
        <span class="nav-box-number">0</span>
    @endif
</a>
<ul class="dropdown-menu dropdown-menu-right px-0">
    <li>
        <div class="dropdown-cart px-0">
            @if(auth()->user()->carts)
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
                                            <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->thumbnail_img) }}" class="img-fluid lazyload" alt="{{ __($product->name) }}">
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