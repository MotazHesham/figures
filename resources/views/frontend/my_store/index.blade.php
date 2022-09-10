@extends('frontend.layouts.app')

@section('styles')
    <style>
        h1 {
            position: relative;
            padding: 0;
            margin: 0;
            font-family: "Raleway", sans-serif;
            font-weight: 300;
            font-size: 35px;
            color: #080808;
            -webkit-transition: all 0.4s ease 0s;
            -o-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
        }

        .twelve h1 {
            font-size: 21px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            width: 160px;
            text-align: center;
            margin: auto;
            white-space: nowrap;
            padding-bottom: 13px;
        }

        .twelve h1:before {
            background-color: #c50000;
            content: '';
            display: block;
            height: 3px;
            width: 75px;
            margin-bottom: 5px;
        }

        .twelve h1:after {
            background-color: #c50000;
            content: '';
            display: block;
            position: absolute;
            right: 0;
            bottom: 0;
            height: 3px;
            width: 75px;
            margin-bottom: 0.25em;
        }

    </style>
@endsection

@section('content')

    <section class="gry-bg py-4">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="{{ route('my_store.index', $store_name) }}" method="GET">
                <div class="row cols-xs-space cols-sm-space cols-md-space">

                    <div class="col-lg-12">
                        <div class="main-content">
                            <!-- Page title -->
                            <div class="page-title">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-12">
                                        <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        </h2>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xl-3 side-filter d-xl-block">
                                    <div class="filter-overlay filter-close"></div>
                                    <div class="filter-wrapper c-scrollbar">
                                        <div
                                            class="filter-title d-flex d-xl-none justify-content-between pb-3 align-items-center">
                                            <h3 class="h6">Filters</h3>
                                            <button type="button" class="close filter-close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="bg-white sidebar-box mb-3">
                                            <div class="box-title text-center">
                                                {{ __('Categories') }}
                                            </div>
                                            <div class="box-content">
                                                <div class="category-filter">
                                                    <ul>
                                                        @if (!isset($category_id) && !isset($category_id) && !isset($subcategory_id) && !isset($subsubcategory_id))
                                                            @foreach (\App\Models\Category::where('design', 1)->get() as $category)
                                                                <li class=""><a
                                                                        href="{{ route('my_store.category', ['category' => $category->slug, 'store_name' => $store_name]) }}">{{ __($category->name) }}</a>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                        @if (isset($category_id))
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.index', $store_name) }}">{{ __('All Categories') }}</a>
                                                            </li>
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.category', ['category' => \App\Models\Category::find($category_id)->slug, 'store_name' => $store_name]) }}">{{ __(\App\Models\Category::find($category_id)->name) }}</a>
                                                            </li>
                                                            @foreach (\App\Models\Category::find($category_id)->subcategories as $key2 => $subcategory)
                                                                <li class="child"><a
                                                                        href="{{ route('my_store.subcategory', ['subcategory' => $subcategory->slug, 'store_name' => $store_name]) }}">{{ __($subcategory->name) }}</a>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                        @if (isset($subcategory_id))
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.index', $store_name) }}">{{ __('All Categories') }}</a>
                                                            </li>
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.category', ['category' => \App\Models\SubCategory::find($subcategory_id)->category->slug, 'store_name' => $store_name]) }}">{{ __(\App\Models\SubCategory::find($subcategory_id)->category->name) }}</a>
                                                            </li>
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.subcategory', ['subcategory' => \App\Models\SubCategory::find($subcategory_id)->slug, 'store_name' => $store_name]) }}">{{ __(\App\Models\SubCategory::find($subcategory_id)->name) }}</a>
                                                            </li>
                                                            @foreach (\App\Models\SubCategory::find($subcategory_id)->subsubcategories as $key3 => $subsubcategory)
                                                                <li class="child"><a
                                                                        href="{{ route('my_store.subsubcategory', ['subsubcategory' => $subsubcategory->slug, 'store_name' => $store_name]) }}">{{ __($subsubcategory->name) }}</a>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                        @if (isset($subsubcategory_id))
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.index', $store_name) }}">{{ __('All Categories') }}</a>
                                                            </li>
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.category', ['category' => \App\Models\SubsubCategory::find($subsubcategory_id)->subcategory->category->slug, 'store_name' => $store_name]) }}">{{ __(\App\Models\SubSubCategory::find($subsubcategory_id)->subcategory->category->name) }}</a>
                                                            </li>
                                                            <li class="active"><a
                                                                    href="{{ route('my_store.subcategory', ['subcategory' => \App\Models\SubsubCategory::find($subsubcategory_id)->subcategory->slug, 'store_name' => $store_name]) }}">{{ __(\App\Models\SubsubCategory::find($subsubcategory_id)->subcategory->name) }}</a>
                                                            </li>
                                                            <li class="current"><a
                                                                    href="{{ route('my_store.subsubcategory', ['subsubcategory' => \App\Models\SubsubCategory::find($subsubcategory_id)->slug, 'store_name' => $store_name]) }}">{{ __(\App\Models\SubsubCategory::find($subsubcategory_id)->name) }}</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-white sidebar-box mb-3">
                                            <div class="box-title text-center">
                                                {{ __('Price range') }}
                                            </div>
                                            <div class="box-content">
                                                <div class="range-slider-wrapper mt-3">
                                                    <!-- Range slider container -->
                                                    <div id="input-slider-range"
                                                        data-range-value-min="{{ filter_products(\App\Models\Product::query())->get()->min('unit_price') }}"
                                                        data-range-value-max="{{ filter_products(\App\Models\Product::query())->get()->max('unit_price') }}">
                                                    </div>

                                                    <!-- Range slider values -->
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <span class="range-slider-value value-low" @if (isset($min_price))
                                                                data-range-value-low="{{ $min_price }}"
                                                            @elseif($products->min('unit_price') > 0)
                                                                data-range-value-low="{{ $products->min('unit_price') }}"
                                                            @else
                                                                data-range-value-low="0"
                                                                @endif
                                                                id="input-slider-range-value-low">
                                                        </div>

                                                        <div class="col-6 text-right">
                                                            <span class="range-slider-value value-high" @if (isset($max_price))
                                                                data-range-value-high="{{ $max_price }}"
                                                            @elseif($products->max('unit_price') > 0)
                                                                data-range-value-high="{{ $products->max('unit_price') }}"
                                                            @else
                                                                data-range-value-high="0"
                                                                @endif
                                                                id="input-slider-range-value-high">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-white sidebar-box mb-3">
                                            <div class="box-title text-center">
                                                {{ __('Filter by color') }}
                                            </div>
                                            <div class="box-content">
                                                <!-- Filter by color -->
                                                <ul class="list-inline checkbox-color checkbox-color-circle mb-0">
                                                    @foreach ($all_colors as $key => $color)
                                                        <li>
                                                            <input type="radio" id="color-{{ $key }}" name="color"
                                                                value="{{ $color }}" @if (isset($selected_color) && $selected_color == $color) checked @endif
                                                                onchange="filter()">
                                                            <label style="background: {{ $color }};"
                                                                for="color-{{ $key }}" data-toggle="tooltip"
                                                                data-original-title="{{ \App\Models\Color::where('code', $color)->first()->name }}"></label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        @foreach ($attributes as $key => $attribute)
                                            @if (\App\Models\Attribute::find($attribute['id']) != null)
                                                <div class="bg-white sidebar-box mb-3">
                                                    <div class="box-title text-center">
                                                        Filter by
                                                        {{ \App\Models\Attribute::find($attribute['id'])->name }}
                                                    </div>
                                                    <div class="box-content">
                                                        <!-- Filter by others -->
                                                        <div class="filter-checkbox">
                                                            @if (array_key_exists('values', $attribute))
                                                                @foreach ($attribute['values'] as $key => $value)
                                                                    @php
                                                                        $flag = false;
                                                                        if (isset($selected_attributes)) {
                                                                            foreach ($selected_attributes as $key => $selected_attribute) {
                                                                                if ($selected_attribute['id'] == $attribute['id']) {
                                                                                    if (in_array($value, $selected_attribute['values'])) {
                                                                                        $flag = true;
                                                                                        break;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <div class="checkbox">
                                                                        <input type="checkbox"
                                                                            id="attribute_{{ $attribute['id'] }}_value_{{ $value }}"
                                                                            name="attribute_{{ $attribute['id'] }}[]"
                                                                            value="{{ $value }}"
                                                                            @if ($flag) checked @endif onchange="filter()">
                                                                        <label
                                                                            for="attribute_{{ $attribute['id'] }}_value_{{ $value }}">{{ $value }}</label>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        {{-- <button type="submit" class="btn btn-styled btn-block btn-base-4">Apply filter</button> --}}
                                    </div>
                                </div>
                                <div class="col-xl-9">
                                    <!-- <div class="bg-white"> -->
                                    @isset($category_id)
                                        <input type="hidden" name="category"
                                            value="{{ \App\Models\Category::find($category_id)->slug }}">
                                    @endisset
                                    @isset($subcategory_id)
                                        <input type="hidden" name="subcategory"
                                            value="{{ \App\Models\SubCategory::find($subcategory_id)->slug }}">
                                    @endisset
                                    @isset($subsubcategory_id)
                                        <input type="hidden" name="subsubcategory"
                                            value="{{ \App\Models\SubSubCategory::find($subsubcategory_id)->slug }}">
                                    @endisset

                                    <div class="sort-by-bar row no-gutters bg-white mb-3 px-3 pt-2">
                                        <div class="col-xl-4 d-flex d-xl-block justify-content-between align-items-end ">
                                            <div class="sort-by-box flex-grow-1">
                                                <div class="form-group">
                                                    <label>{{ __('Search') }}</label>
                                                    <div class="search-widget">
                                                        <input class="form-control input-lg" type="text" name="q"
                                                            placeholder="{{ __('Search products') }}" @isset($query)
                                                            value="{{ $query }}" @endisset>
                                                        <button type="submit" class="btn-inner">
                                                            <i class="fa fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-xl-none ml-3 form-group">
                                                <button type="button" class="btn p-1 btn-sm" id="side-filter">
                                                    <i class="la la-filter la-2x"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-xl-7 offset-xl-1">
                                            <div class="row no-gutters">
                                                <div class="col-4">
                                                    <div class="sort-by-box px-1">
                                                        <div class="form-group">
                                                            <label>{{ __('Sort by') }}</label>
                                                            <select class="form-control sortSelect"
                                                                data-minimum-results-for-search="Infinity" name="sort_by"
                                                                onchange="filter()">
                                                                <option value="1" @isset($sort_by) @if ($sort_by == '1') selected @endif
                                                                    @endisset>{{ __('Newest') }}</option>
                                                                <option value="2" @isset($sort_by) @if ($sort_by == '2') selected @endif
                                                                    @endisset>{{ __('Oldest') }}</option>
                                                                <option value="3" @isset($sort_by) @if ($sort_by == '3') selected @endif
                                                                    @endisset>{{ __('Price low to high') }}</option>
                                                                <option value="4" @isset($sort_by) @if ($sort_by == '4') selected @endif
                                                                    @endisset>{{ __('Price high to low') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-8 col-end">
                                                    <div class="sort-by-box px-1">
                                                        <div class="form-group" >
                                                            <label> </label>
                                                            <div class="twelve">
                                                                <h1>{{ $store_name }}</h1>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-4">
                                                    <div class="sort-by-box px-1">
                                                        <div class="form-group">
                                                            <label>{{__('Brands')}}</label>
                                                            <select class="form-control sortSelect" data-placeholder="{{__('All Brands')}}" name="brand" onchange="filter()">
                                                                <option value="">{{__('All Brands')}}</option>
                                                                @foreach (\App\Models\Brand::all() as $brand)
                                                                    <option value="{{ $brand->slug }}" @isset($brand_id) @if ($brand_id == $brand->id) selected @endif @endisset>{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="col-4">
                                                    <div class="sort-by-box px-1">
                                                        <div class="form-group">
                                                            <label>{{__('Sellers')}}</label>
                                                            <select class="form-control sortSelect" data-placeholder="{{__('All Sellers')}}" name="seller_id" onchange="filter()">
                                                                <option value="">{{__('All Sellers')}}</option>
                                                                @foreach (\App\Models\Seller::all() as $key => $seller)
                                                                    @if ($seller->user != null && $seller->user->shop != null)
                                                                        <option value="{{ $seller->id }}" @isset($seller_id) @if ($seller_id == $seller->id) selected @endif @endisset>{{ $seller->user->shop->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="min_price" value="">
                                    <input type="hidden" name="max_price" value="">
                                    <div class="products-box-bar p-3 bg-white">
                                        <div class="row sm-no-gutters gutters-5">
                                            @foreach ($products as $key => $product)
                                                <div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6 text-center">
                                                    <div class="product-box-2 bg-white alt-box my-md-2">
                                                        <div class="position-relative overflow-hidden">
                                                            <a href="{{ route('product', $product->slug) }}"
                                                                class="d-block product-image h-100 text-center"
                                                                tabindex="0">
                                                                <img class="img-fit lazyload"
                                                                    src="{{ asset('frontend/images/placeholder.jpg') }}"
                                                                    data-src="{{ asset($product->thumbnail_img) }}"
                                                                    alt="{{ __($product->name) }}">
                                                            </a>
                                                            <div class="product-btns clearfix">
                                                                @php
                                                                    if (auth()->check()) {
                                                                        $loved = \App\Models\Wishlist::where('product_id', $product->id)
                                                                            ->where('user_id', auth()->user()->id)
                                                                            ->first();
                                                                    } else {
                                                                        $loved = false;
                                                                    }
                                                                @endphp
                                                                <button class="btn add-wishlist" title="Add to Wishlist"
                                                                    onclick="addToWishList({{ $product->id }})"
                                                                    @if ($loved) disabled @endif>
                                                                    @if ($loved)
                                                                        <i class="fa fa-heart" style="color: crimson"></i>
                                                                    @else
                                                                        <i class="la la-heart-o"></i>
                                                                    @endif
                                                                </button>
                                                                <button class="btn add-wishlist" tabindex="0">
                                                                    <i class=" "></i>
                                                                </button>
                                                                <a class="btn quick-view" title="Quick view"
                                                                    onclick="showAddToCartModal({{ $product->id }})"
                                                                    tabindex="0">
                                                                    <i class="la la-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="p-md-3 p-2">
                                                            <div class="price-box">
                                                                @if (home_base_price($product->id) != home_discounted_base_price($product->id))
                                                                    <del
                                                                        class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                                                @endif
                                                                <span
                                                                    class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                            </div>
                                                            <div class="star-rating star-rating-sm mt-1">
                                                                {{ renderStarRating($product->rating) }}
                                                            </div>
                                                            <h2 class="product-title p-0">
                                                                <a href="{{ route('product', $product->slug) }}"
                                                                    class=" text-truncate">{{ __($product->name) }}</a>
                                                            </h2>
                                                            @auth
                                                                @if (auth()->user()->user_type == 'seller')
                                                                    <p class="price-box">
                                                                    <div
                                                                        style=" background-color: #348282; color: white; border-radius: 17px;">
                                                                        <b class="product-price strong-600">{{ single_price($product->unit_price - $product->purchase_price) }}
                                                                        </b>
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
                                    <div class="products-pagination bg-white p-3">
                                        <nav aria-label="Center aligned pagination">
                                            <ul class="pagination justify-content-center">
                                                {{ $products->links() }}
                                            </ul>
                                        </nav>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>

@endsection


@section('script')
    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }

        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
