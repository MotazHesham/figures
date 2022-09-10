<div class="card sticky-top">
    <div class="card-title py-3">
        <div class="row align-items-center">
            <div class="col-6">
                <h3 class="heading heading-3 strong-400 mb-0">
                    <span>{{__('Summary')}}</span>
                </h3>
            </div>

            <div class="col-6 text-right"> 
                <span class="badge badge-md badge-success">{{ count(auth()->user()->carts) }} {{__('Items')}}</span>  
            </div>
        </div>
    </div>

    <div class="card-body">  
        <table class="table-cart table-cart-review">
            <thead>
                <tr>
                    <th class="product-name">{{__('Product')}}</th>
                    <th class="product-total text-right">{{__('Total')}}</th>
                </tr>
            </thead>
            <tbody> 
                @php
                    $subtotal = 0;
                    $commission = 0;
                @endphp
                @foreach (auth()->user()->carts as $key => $cartItem)
                    @php
                        $product = \App\Models\Product::find($cartItem['product_id']);
                        $subtotal += $cartItem['total_cost'];
                        $commission += $cartItem['commission'];
                        $product_name_with_choice = $product->name;
                        if ($cartItem['variation'] != null) {
                            $product_name_with_choice = $product->name.' - '.$cartItem['variation'];
                        }
                    @endphp
                    <tr class="cart_item">
                        <td class="product-name">
                            {{ $product_name_with_choice }}
                            <strong class="product-quantity">× {{ $cartItem['quantity'] }}</strong>
                        </td>
                        <td class="product-total text-right">
                            <span class="pl-4">{{ single_price($cartItem['total_cost']) }}</span>
                        </td>
                    </tr>
                @endforeach 
            </tbody>
        </table> 
        <hr>
        <table class="table-cart table-cart-review">

            <tfoot>
                <tr class="cart-subtotal">
                    <th>{{__('Subtotal')}}</th>
                    <td class="text-right">
                        <span class="strong-600">{{ single_price($subtotal) }}</span>
                    </td>
                </tr>
                @if(auth()->user()->user_type == 'seller')
                    <tr class="cart-subtotal">
                        <th>{{__('Total')}} {{__('Commission')}}</th>
                        <td class="text-right">
                            <span class="strong-600">{{ single_price($commission) }}</span>
                        </td>
                    </tr>
                @endif


                @php 
                    if(isset($discount)){
                        $discount_cost = $discount > 0 ?  $subtotal * ($discount /100) : 0; 

                        $subtotal -= $discount_cost;
                    }
                @endphp

                @if(isset($discount) && $discount > 0)
                    <tr class="cart-shipping">
                        <th>{{__('Discount')}}</th>
                        <td class="text-right">
                            <span class="text-italic">{{ single_price($discount_cost) }}</span>
                        </td>
                    </tr>
                @endif

                @php 
                    $shipping = $shipping_country_cost ?? 0; 
                @endphp
                <tr class="cart-shipping">
                    <th>{{__('Total Shipping')}}</th>
                    <td class="text-right">
                        <span class="text-italic">{{ single_price($shipping) }}</span>
                    </td>
                </tr>


                @php
                    $total = $subtotal + $shipping; 
                @endphp
                <tr class="cart-total">
                    <th><span class="strong-600">{{__('Total')}}</span></th>
                    <td class="text-right">
                        <strong><span>{{ single_price($total) }}</span></strong>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="row align-items-center pt-4">
            <div class="col-md-6">
                <a href="{{ route('home') }}" class="link link--style-3">
                    <i class="la la-mail-reply"></i>
                    {{__('Return to shop')}}
                </a>
            </div> 
        </div>
    </div>
</div>
