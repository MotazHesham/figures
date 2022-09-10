<div class="modal-body p-4">
    <div class="row no-gutters cols-xs-space cols-sm-space cols-md-space">
        <div class="col-lg-6">
            <div class="product-gal sticky-top d-flex flex-row-reverse">
                @if(is_array(json_decode($product->photos)) && count(json_decode($product->photos)) > 0)
                    <div class="product-gal-img">
                        <img src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom img-fluid lazyload"
                             src="{{ asset('frontend/images/placeholder.jpg') }}"
                             data-src="{{ asset(json_decode($product->photos)[0]) }}"
                             xoriginal="{{ asset(json_decode($product->photos)[0]) }}"/>
                    </div>
                    <div class="product-gal-thumb">
                        <div class="xzoom-thumbs">
                            @foreach (json_decode($product->photos) as $key => $photo)
                                <a href="{{ asset($photo) }}">
                                    <img src="{{ asset('frontend/images/placeholder.jpg') }}"
                                         class="xzoom-gallery lazyload"
                                         src="{{ asset('frontend/images/placeholder.jpg') }}" width="80"
                                         data-src="{{ asset($photo) }}"
                                         @if($key == 0) xpreview="{{ asset($photo) }}" @endif>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Product description -->
            <div class="product-description-wrapper">
                <!-- Product title -->
                <h2 class="product-title">
                    {{ __($product->name) }}
                </h2>

                @if(home_price($product->id) != home_discounted_price($product->id))

                    <div class="row no-gutters mt-4">
                        <div class="col-2">
                            <div class="product-description-label">{{__('Price')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price-old">
                                <del>
                                    {{ home_price($product->id) }}
                                    <span>/{{ $product->unit }}</span>
                                </del>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters mt-3">
                        <div class="col-2">
                            <div class="product-description-label mt-1">{{__('Discount Price')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                <strong>
                                    {{ home_discounted_price($product->id) }}
                                </strong>
                                <span class="piece">/{{ $product->unit }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row no-gutters mt-3">
                        <div class="col-2">
                            <div class="product-description-label">{{__('Price')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                <strong>
                                    {{ home_discounted_price($product->id) }}
                                </strong>
                                <span class="piece">/{{ $product->unit }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <hr>

                @php
                    $qty = 0;
                    if($product->variant_product){
                        foreach ($product->stocks as $key => $stock) {
                            $qty += $stock->qty;
                        }
                    }
                    else{
                        $qty = $product->current_stock;
                    }
                @endphp

                <form id="option-choice-form" enctype="multipart/form-data" action="{{route('cart.addToCart')}}" method="POST" style="padding-bottom:40px">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input class="chosen_price_input" type="hidden" name="price">
                    <input class="chosen_variant" type="hidden" name="variant">
                    <input class="chosen_quntity_input" type="hidden" name="quntity" value="1">

                    @php
                        $has_unit_attribute = false;
                    @endphp

                    <!-- Quantity + Add to cart -->
                    @if($product->digital !=1)
                        @if ($product->choice_options != null)
                            @foreach (json_decode($product->choice_options) as $key => $choice)
                            @php
                                if(\App\Models\Attribute::find($choice->attribute_id)->name == 'unit'){
                                    $has_unit_attribute = true;
                                }
                            @endphp
                                <div class="row no-gutters">
                                    <div class="col-2">
                                        <div
                                            class="product-description-label mt-2 ">{{ \App\Models\Attribute::find($choice->attribute_id)->name }}
                                            :
                                        </div>
                                    </div>
                                    <div class="col-10">
                                        <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2">
                                            @foreach ($choice->values as $key => $value)
                                                <li>
                                                    <input type="radio" id="{{ $choice->attribute_id }}-{{ $value }}"
                                                            name="attribute_id_{{ $choice->attribute_id }}"
                                                            value="{{ $value }}" @if($key == 0) checked @endif>
                                                    <label
                                                        for="{{ $choice->attribute_id }}-{{ $value }}">{{ $value }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                            @endforeach
                        @endif

                        @if (count(json_decode($product->colors)) > 0)
                            <div class="row no-gutters">
                                <div class="col-2">
                                    <div class="product-description-label mt-2">{{__('Color')}}:</div>
                                </div>
                                <div class="col-10">
                                    <ul class="list-inline checkbox-color mb-1">
                                        @foreach (json_decode($product->colors) as $key => $color)
                                            <li>
                                                <input type="radio" id="{{ $product->id }}-color-{{ $key }}"
                                                       name="color" value="{{ $color }}" @if($key == 0) checked @endif>
                                                <label style="background: {{ $color }};"
                                                       for="{{ $product->id }}-color-{{ $key }}"
                                                       data-toggle="tooltip"></label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <hr>
                        @endif

                        @if ($has_unit_attribute)
                            <input type="hidden" name="quantity" class="form-control input-number text-center" placeholder="1" value="1" min="1" max="10">
                        @else
                            <div class="row no-gutters">
                                <div class="col-2">
                                    <div class="product-description-label mt-2">{{__('Quantity')}}:</div>
                                </div>
                                <div class="col-10">
                                    <div class="product-quantity d-flex align-items-center">
                                        <div class="input-group input-group--style-2 pr-3" style="width: 160px;">
                                            <span class="input-group-btn">
                                                <button class="btn btn-number" type="button" data-type="minus"
                                                        data-field="quantity" disabled="disabled">
                                                    <i class="la la-minus"></i>
                                                </button>
                                            </span>
                                            <input type="text" name="quantity" class="form-control input-number text-center"
                                                placeholder="1" value="1" min="1" max="10">
                                            <span class="input-group-btn">
                                                <button class="btn btn-number" type="button" data-type="plus"
                                                        data-field="quantity">
                                                    <i class="la la-plus"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <div class="avialable-amount">(<span
                                                id="available-quantity">{{ $qty }}</span> {{__('available')}})
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif
                        <hr>
                    @endif

                    <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                        <div class="col-2">
                            <div class="product-description-label">{{__('Total Price')}}:</div>
                        </div>
                        <div class="col-10">
                            <div class="product-price">
                                <strong id="chosen_price">

                                </strong>
                            </div>
                        </div>
                    </div>

                    @if($product->special)

                        <div class="form-box bg-white mt-4">
                            <div class="form-box-title px-3 py-2">
                                {{__('Images To Print in Product')}}
                            </div>
                            <div class="form-box-content p-3">
                                <div id="product-images">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('Main Images')}}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="file" name="photos[]" id="photos-1" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                                            <label for="photos-1" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{__('Choose image')}}
                                                </strong>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="photos_note[]" class="form-control" placeholder="ملحوظة علي الصورة">
                                        </div>

                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-info mb-3" onclick="add_more_slider_image()">{{ __('Add More') }}</button>
                                </div>

                            </div>
                        </div>


                        @if(auth()->check() && auth()->user()->user_type == 'seller')
                            <div class="form-box bg-white mt-4">
                                <div class="form-box-title px-3 py-2">
                                    {{__('Specification')}}
                                </div>
                                <div class="form-box-content p-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('PDF')}}</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="file" name="pdf" id="file-6" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="pdf/*" />
                                            <label for="file-6" class="mw-100 mb-3">
                                                <span></span>
                                                <strong>
                                                    <i class="fa fa-upload"></i>
                                                    {{__('Choose PDF')}}
                                                </strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('Link')}}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control mb-3" id="link" name="link" value="{{old('link')}}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>{{__('File Sent To Email')}}</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="switch" style="margin-top:5px;">
                                                <input type="checkbox" name="file_sent">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-box bg-white mt-4">
                            <div class="form-box-title px-3 py-2">
                                {{__('Description')}}
                            </div>
                            <div class="form-box-content p-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>{{__('Description')}}</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="mb-3">
                                            <textarea rows="8" cols="50"  name="description">{{old('description')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif

                    <div class="d-table width-100 mt-3">
                        <div class="d-table-cell">
                            <!-- Add to cart button -->
                            @if($qty > 0)
                                @if(Auth::check())
                                    <button type="submit"
                                            class="btn btn-styled btn-alt-base-1 c-white btn-icon-left strong-700 hov-bounce hov-shaddow ml-2 add-to-cart"
                                            >
                                        <i class="la la-shopping-cart"></i>
                                        <span class="d-none d-md-inline-block"> {{__('Add to cart')}}</span>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-styled btn-alt-base-1 c-white btn-icon-left strong-700 hov-bounce hov-shaddow ml-2 add-to-cart"
                                            onclick="showCheckoutModal()">
                                        <i class="la la-shopping-cart"></i>
                                        <span class="d-none d-md-inline-block"> {{__('Add to cart')}}</span>
                                    </button>
                                @endif
                            @else
                                <button type="button" class="btn btn-styled btn-base-3 btn-icon-left strong-700" disabled>
                                    <i class="la la-cart-arrow-down"></i> {{__('Out of Stock')}}
                                </button>
                            @endif
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    cartQuantityInitialize();
    $('#option-choice-form input').on('change', function () {
        getVariantPrice();
    });
</script>
