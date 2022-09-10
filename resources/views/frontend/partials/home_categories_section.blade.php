@foreach (\App\Models\HomeCategory::where('status', 1)->get() as $key => $homeCategory)
    @if ($homeCategory->category != null)
        <section class="mb-4">
            <div class="container">
                <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                    <div class="section-title-1 clearfix">
                        <h3 class="heading-5 strong-700 mb-0 float-lg-left">
                            <span class="mr-4">{{ __($homeCategory->category->name) }}</span>
                        </h3>
                        <ul class="inline-links float-lg-right nav mt-3 mb-2 m-lg-0">
                            <li><a href="{{ route('products.category', $homeCategory->category->slug) }}" class="active">View More</a></li>
                        </ul>
                    </div>
                    <div class="caorusel-box arrow-round gutters-5">
                        <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                        @foreach (filter_products(\App\Models\Product::where('published', 1)->where('category_id', $homeCategory->category->id))->latest()->limit(12)->get() as $key => $product)
                            <div class="caorusel-card">
                                <div class="product-box-2 bg-white alt-box my-2">
                                    <div class="position-relative overflow-hidden">
                                        <a href="{{ route('product', $product->slug) }}" class="d-block product-image h-100 text-center">
                                            <img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->thumbnail_img) }}" alt="{{ __($product->name) }}">
                                        </a>
                                        <div class="product-btns clearfix">
                                            
                                            @php
                                                if(auth()->check()){
                                                    $loved = \App\Models\Wishlist::where('product_id',$product->id)->where('user_id',auth()->user()->id)->first();
                                                }else{
                                                    $loved = false;
                                                }
                                            @endphp
                                            <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" @if($loved) disabled @endif>
                                                @if($loved) 
                                                    <i class="fa fa-heart" style="color: crimson"></i>
                                                @else
                                                    <i class="la la-heart-o"></i>
                                                @endif
                                            </button>
                                            <button class="btn add-wishlist"  tabindex="0">
                                                <i class=" "></i>
                                            </button> 
                                            <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal({{ $product->id }})" tabindex="0">
                                                <i class="la la-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-md-3 p-2 text-center">
                                        <div class="price-box">
                                            @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                            @endif
                                            <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                        </div>
                                        <div class="star-rating star-rating-sm mt-1">
                                            {{ renderStarRating($product->rating) }}
                                        </div>
                                        <h2 class="product-title p-0">
                                            <a href="{{ route('product', $product->slug) }}" class=" text-truncate">{{ __($product->name) }}</a>
                                        </h2>
                                        @auth 
                                            @if(auth()->user()->user_type == 'seller')
                                                <p class="price-box">
                                                    <div style=" background-color: #348282; color: white; border-radius: 17px;">
                                                        <b class="product-price strong-600">{{single_price($product->unit_price - $product->purchase_price)}} </b>
                                                    نسبة الربح
                                                    </div> 
                                                </p>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endforeach
