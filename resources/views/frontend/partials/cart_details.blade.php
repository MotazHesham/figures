<div class="container">
    <div class="row cols-xs-space cols-sm-space cols-md-space">
        <div class="col-xl-8">
            <!-- <form class="form-default bg-white p-4" data-toggle="validator" role="form"> -->
            <div class="form-default bg-white p-4">
                <div class="">
                    <div class="">
                        <table class="table-cart border-bottom">
                            <thead>
                                <tr>
                                    <th class="product-image"></th>
                                    <th class="product-name">{{__('Product')}}</th>
                                    <th class="product-quanity d-none d-md-table-cell">{{__('Quantity')}}</th>
                                    <th class="product-price d-none d-lg-table-cell">{{__('Price')}}</th>
                                    @if(auth()->user()->user_type == 'seller')
                                        <th class="product-price d-none d-lg-table-cell">{{__('Commission')}}</th>
                                    @endif
                                    <th class="product-total">{{__('Total')}}</th>
                                    <th class="product-remove"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total = 0;
                                @endphp
                                @foreach(auth()->user()->carts as $key => $cartItem)
                                    @php
                                    $product = \App\Models\Product::find($cartItem['product_id']);
                                    $total = $total + $cartItem['total_cost'];
                                    $product_name_with_choice = $product->name;
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice = $product->name.' - '.$cartItem['variation'];
                                    }
                                    // if(isset($cartItem['color'])){
                                    //     $product_name_with_choice .= ' - '.\App\Models\Color::where('code', $cartItem['color'])->first()->name;
                                    // }
                                    // foreach (json_decode($product->choice_options) as $choice){
                                    //     $str = $choice->name; // example $str =  choice_0
                                    //     $product_name_with_choice .= ' - '.$cartItem[$str];
                                    // }
                                    @endphp
                                    <tr class="cart-item">
                                        <td class="product-image">
                                            <a href="#" class="mr-3">
                                                <img loading="lazy"  src="{{ asset($cartItem->chosen_photo ?? $product->thumbnail_img) }}">
                                            </a>
                                        </td>

                                        <td class="product-name">
                                            <span class="pr-4 d-block">{{ $product_name_with_choice }}</span>
                                        </td>

                                        <td class="product-quantity d-none d-lg-table-cell">
                                            <span class="pr-3 d-block">{{ $cartItem['quantity'] }}</span>
                                        </td>
                                        <td class="product-price d-none d-lg-table-cell">
                                            <span class="pr-3 d-block">{{ single_price($cartItem['price']) }}</span>
                                        </td>
                                        @if(auth()->user()->user_type == 'seller')
                                            <td class="product-price d-none d-lg-table-cell">
                                                <span class="pr-3 d-block">{{ single_price($cartItem['commission']) }}</span>
                                            </td>
                                        @endif
                                        <td class="product-total">
                                            <span>{{ single_price($cartItem['total_cost']) }}</span>
                                        </td>
                                        <td class="product-remove">
                                            <a style="display:inline" role="button" onclick="editProductInCartModal({{$cartItem['id']}})">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" onclick="removeFromCartView(event, {{ $cartItem['id'] }})" style="display:inline">
                                                <i class="la la-trash"></i>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row align-items-center pt-4">
                    <div class="col-md-6">
                        <a href="{{ route('home') }}" class="link link--style-3">
                            <i class="la la-mail-reply"></i>
                            {{__('Return to shop')}}
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        @if(Auth::check())
                            <a href="{{ route('checkout.shipping_info') }}" class="btn btn-styled btn-base-1">{{__('Continue to Shipping Info')}}</a>
                        @else
                            <button class="btn btn-styled btn-base-1" onclick="showCheckoutModal()">{{__('Continue to Shipping Info')}}</button>
                        @endif
                    </div>
                </div>
            </div>
            <!-- </form> -->
        </div>

        <div class="col-xl-4 ml-lg-auto">
            @include('frontend.partials.cart_summary')
        </div>
    </div>
</div>
