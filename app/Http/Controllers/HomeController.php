<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\PickupPoint;
use App\Models\CustomerPackage;
use App\Models\CustomerProduct;
use App\Models\User;
use App\Models\Seller_balance_request;
use App\Models\Calender;
use App\Models\Seller_balance_method;
use App\Models\Seller;
use App\Models\Seller_balance;
use App\Models\Shop;
use App\Models\OrderDetail;
use App\Models\Favorite;
use App\Models\Color;
use App\Models\FlashDealProduct;
use App\Models\Order;
use App\Models\Language; 
use App\Models\Receipt_client_Product;
use App\Models\Receipt_outgoings_products;
use App\Models\BusinessSetting;
use App\Models\AuditLog;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie; 
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Http\Controllers\Admin\ConversionApiController;

class HomeController extends Controller
{ 
    public function logs(Request $request){
        $logs = AuditLog::where('subject_type',$request->model)->where('subject_id',$request->subject_id)->orderBy('created_at','asc')->get()->reverse();  
        return view($request->view . '.logs',compact('logs'));
    }

    public function changeLanguage(Request $request)
    {
    	$request->session()->put('locale', $request->locale);
        $language = Language::where('code', $request->locale)->first();
    	flash(__('Language changed to ').$language->name)->success();
    }

    public function admin_dashboard(Request $request){   
        
        $month_bar = date("m",strtotime('now'));
        $year_bar = date("Y",strtotime('now'));
        
        
        if($request->has('month_bar')){
            $month_bar = $request->month_bar;
        }
        
        if($request->has('year_bar')){
            $year_bar = $request->year_bar;
        } 

        $settings2 = [
            'bar_label'                 => 'Orders',
            'chart_title'               => 'Sellers Orders Count',
            'chart_type'                => 'bar',
            'report_type'               => 'group_by_date',
            'model'                     => 'App\Models\Order',
            'group_by_field'            => 'created_at',
            'group_by_period'           => 'day',
            'aggregate_function'        => 'count', 
            'filter_field'              => 'created_at', 
            'group_by_field_format'     => 'd/m/Y H:i:s',
            'date_format_filter_days'   => 'd/m/Y H:i:s',
            'column_class'              => 'col-md-8',
            'entries_number'            => '5',
            'range_date_start'          => $year_bar.'/'.$month_bar.'/1 00:00:00',
            'range_date_end'            => $year_bar.'/'.$month_bar.'/31 23:59:59',
            'continuous_time'           => 'd/m/Y H:i:s',
        ];

        $chart2 = new LaravelChart($settings2);  
        return view('dashboard',compact('chart2','year_bar','month_bar')); 
    }

    
    public function index(){
        return view('frontend.index');
    }

    public function seller_landingpage(){ 
        return view('frontend.landing_page_seller');
    }

    public function designer_landingpage(){ 
        return view('frontend.landing_page_designer');
    }

    public function products_by_subcategory(Request $request){
        $products = Product::where('subcategory_id', $request->subcategory_id)->get();
        return $products;
    }
    public function products_by_subsubcategory(Request $request){
        $products = Product::where('subsubcategory_id', $request->subsubcategory_id)->get();
        return $products;
    }
    public function product_by_id(Request $request){
        $product = Product::find($request->product_id);
        $price = $product->unit_price;
        $quantity = $product->current_stock;
        if($product->variant_product == 1){
            $product_stock = \App\Models\ProductStock::where('product_id', $product->id)->first();
            $quantity = $product_stock->qty;
        }

        //discount calculation
        $flash_deals = \App\Models\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }
        return array('price' => $price, 'quantity' => $quantity, 'commission' => $product->unit_price - $product->purchase_price);
    }
    public function product_variant_by_id(Request $request){
        $product = Product::find($request->product_id);

        $output = '';
        if (count(json_decode($product->choice_options)) > 0 || count(json_decode($product->colors)) > 0){
            $output .= '<input type="hidden" name="id" value="'. $product->id .'">';
            $output .= '<div class="col-md-2">';
            $output .=      '<label>'.__("Product Variation").' <span class="required-star">*</span></label>';
            $output .= '</div>';
        }
        
        $output .= '<div class="col-md-10" id="product_variant">';
                if ($product->choice_options != null){
                    foreach (json_decode($product->choice_options) as $key => $choice){

                    $output .= '<div class="row no-gutters">';
                    $output .=    '<div class="col-2">';
                    $output .=        '<div class="product-description-label mt-2 ">' . \App\Models\Attribute::find($choice->attribute_id)->name . ':</div>';
                    $output .=    '</div>';
                    $output .=    '<div class="col-10">';
                    $output .=          '<ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2">';
                                            foreach ($choice->values as $key => $value){
                    $output .=                  '<li>';
                                                    if(($key == 0)){
                                                        $checked = 'checked';
                                                    }else{
                                                        $checked ='';
                                                    }
                    $output .=                      '<input type="radio" id="' . $choice->attribute_id .'-'. $value .'"   name="attribute_id_' . $choice->attribute_id . '"   value="'. $value .'"  '.$checked.'>';
                    $output .=                      '<label for="'. $choice->attribute_id .'-'. $value .'">'. $value .'</label>';
                    $output .=                  '</li>';
                                            }
                    $output .=          '</ul>';
                    $output .=     '</div>';
                    $output .= '</div>';

                    }
                }

                if (count(json_decode($product->colors)) > 0){
                    $output .= '<div class="row no-gutters">';
                    $output .=      '<div class="col-2">';
                    $output .=          '<div class="product-description-label mt-2">'.__("Color").':</div>';
                    $output .=      '</div>';
                    $output .=      '<div class="col-10">';
                    $output .=          '<ul class="list-inline checkbox-color mb-1">';
                                            foreach (json_decode($product->colors) as $key => $color){
                    $output .=                  '<li>';
                                                    if(($key == 0)){
                                                        $checked = 'checked';
                                                    }else{
                                                        $checked ='';
                                                    }
                    $output .=                      '<input type="radio" id="'. $product->id .'-color-'. $key .'" name="color" value="'. $color .'" '.$checked.'>';
                    $output .=                      '<label style="background: '. $color .';" for="'. $product->id .'-color-'. $key .'" data-toggle="tooltip"></label>';
                    $output .=                  '</li>';
                                            }
                    $output .=          '</ul>';
                    $output .=      '</div>';
                    $output .=  '</div><hr>'; 
                }
        $output .= '</div>';      
        return $output;
    }

    

    
    
    
    
    

    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_featured_section(){
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section(){
        return view('frontend.partials.best_selling_section');
    }

    public function load_home_categories_section(){
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section(){
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if($request->has('order_code')){
            $order = Order::where('code', $request->order_code)->first();
            if($order != null){
                return view('frontend.track_order', compact('order'));
            }else{
                flash(__('Not Found'))->error();
            }
        }
        return view('frontend.track_order');
    }

    public function cart_login(Request $request)
    {
        $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        if($user != null){
            updateCartSetup();
            if(Hash::check($request->password, $user->password)){
                if($request->has('remember')){
                    auth()->login($user, true);
                }
                else{
                    auth()->login($user, false);
                }
            }
        }
        return back();
    }

    public function product(Request $request, $slug)
    {
        
        $detailedProduct  = Product::where('slug', $slug)->first();

        if(Auth::check()){
            $conversionApiController = new ConversionApiController;
            $conversionApiController->event($detailedProduct->id,1,$detailedProduct->price,route('product', $detailedProduct->slug),'View Content');
        }

        if($detailedProduct!=null && $detailedProduct->published){
            updateCartSetup();
            if($request->has('product_referral_code')){
                Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
                Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
            }
            if($detailedProduct->digital == 1){
                return view('frontend.digital_product_details', compact('detailedProduct'));
            }
            else {
                return view('frontend.product_details', compact('detailedProduct'));
            }
            // return view('frontend.product_details', compact('detailedProduct'));
        }
        abort(404);
    }

    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null){
            $seller = Seller::where('user_id', $shop->user_id)->first();
            if ($seller->verification_status != 0){
                return view('frontend.seller_shop', compact('shop'));
            }
            else{
                return view('frontend.seller_shop_without_verification', compact('shop', 'seller'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null && $type != null){
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function listing(Request $request)
    {
        // $products = filter_products(Product::orderBy('created_at', 'desc'))->paginate(12);
        // return view('frontend.product_listing', compact('products'));
        return $this->search($request);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_category', compact('categories'));
    }
    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        if(\App\Models\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Models\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_uploads > 0){
                $categories = Category::all();
                return view('frontend.seller.product_upload', compact('categories'));
            }
            else {
                flash('Upload limit has been reached. Please upgrade your package.')->warning();
                return back();
            }
        }
        $categories = Category::all();
        return view('frontend.seller.product_upload', compact('categories'));
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $categories = Category::all();
        $product = Product::find(decrypt($id));
        return view('frontend.seller.product_edit', compact('categories', 'product'));
    }
    

    public function ajax_search(Request $request)
    {
        $keywords = array();
        $products = Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',',$product->tags) as $key => $tag) {
                if(stripos($tag, $request->search) !== false){
                    if(sizeof($keywords) > 5){
                        break;
                    }
                    else{
                        if(!in_array(strtolower($tag), $keywords)){
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::where('published', 1)->where('name', 'like', '%'.$request->search.'%'))->get()->take(3);

        $subsubcategories = SubSubCategory::where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        if(sizeof($keywords)>0 || sizeof($subsubcategories)>0 || sizeof($products)>0 || sizeof($shops) >0){
            return view('frontend.partials.search_content', compact('products', 'subsubcategories', 'keywords', 'shops'));
        }
        return '0';
    }

    public function search(Request $request)
    {
        $query = $request->q;
        $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
        $sort_by = $request->sort_by;
        $category_id = (Category::where('slug', $request->category)->first() != null) ? Category::where('slug', $request->category)->first()->id : null;
        $subcategory_id = (SubCategory::where('slug', $request->subcategory)->first() != null) ? SubCategory::where('slug', $request->subcategory)->first()->id : null;
        $subsubcategory_id = (SubSubCategory::where('slug', $request->subsubcategory)->first() != null) ? SubSubCategory::where('slug', $request->subsubcategory)->first()->id : null;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;

        $conditions = ['published' => 1];

        if($brand_id != null){
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }
        if($category_id != null){
            $conditions = array_merge($conditions, ['category_id' => $category_id]);
        }
        if($subcategory_id != null){
            $conditions = array_merge($conditions, ['subcategory_id' => $subcategory_id]);
        }
        if($subsubcategory_id != null){
            $conditions = array_merge($conditions, ['subsubcategory_id' => $subsubcategory_id]);
        }
        if($seller_id != null){
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }

        $products = Product::where($conditions);

        if($min_price != null && $max_price != null){
            $products = $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if($query != null){
            $searchController = new SearchController;
            $searchController->store($request);
            $products = $products->where('name', 'like', '%'.$query.'%')->orWhere('tags', 'like', '%'.$query.'%');
        }

        if($sort_by != null){
            switch ($sort_by) {
                case '1':
                    $products->orderBy('created_at', 'desc');
                    break;
                case '2':
                    $products->orderBy('created_at', 'asc');
                    break;
                case '3':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case '4':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    // code...
                    break;
            }
        }


        $non_paginate_products = filter_products($products)->get();

        //Attribute Filter

        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if($product->attributes != null && is_array(json_decode($product->attributes))){
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if($attribute['id'] == $value){
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if(!$flag){
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    }
                    else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                foreach ($choice_option->values as $key => $value) {
                                    if(!in_array($value, $attributes[$pos]['values'])){
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {
            if($request->has('attribute_'.$attribute['id'])){
                foreach ($request['attribute_'.$attribute['id']] as $key => $value) {
                    $str = '"'.$value.'"';
                    $products = $products->where('choice_options', 'like', '%'.$str.'%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_'.$attribute['id']];
                array_push($selected_attributes, $item);
            }
        }


        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if(!in_array($color, $all_colors)){
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_color = null;

        if($request->has('color')){
            $str = '"'.$request->color.'"';
            $products = $products->where('colors', 'like', '%'.$str.'%');
            $selected_color = $request->color;
        }


        $products = filter_products($products)->paginate(12)->appends(request()->query());

        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'subcategory_id', 'subsubcategory_id', 'brand_id', 'sort_by', 'seller_id','min_price', 'max_price', 'attributes', 'selected_attributes', 'all_colors', 'selected_color'));
    }

    public function product_content(Request $request){
        $connector  = $request->connector;
        $selector   = $request->selector;
        $select     = $request->select;
        $type       = $request->type;
        productDescCache($connector,$selector,$select,$type);
    }

    

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;

        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if(json_decode(Product::find($request->id)->choice_options) != null){
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }



        if($str != null && $product->variant_product){
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;
            $comission = ( $product_stock->price - $product_stock->purchase_price);
            $quantity = $product_stock->qty;
        }
        else{
            $price = $product->unit_price;
            $comission = ( $product->unit_price -$product->purchase_price );
            $quantity = $product->current_stock;
        }

        //discount calculation
        $flash_deals = \App\Models\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        $before_discount = 0;
        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $before_discount = $price;
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $before_discount = $price;
                $price -= $product->discount;
            }
        }

        return array(   'total_price' => single_price( $price * $request->quantity ),
                        'total_commission' => single_price( $comission * $request->quantity),
                        'price' => single_price($price),
                        'price_input' => $price,
                        'variant' => $str,
                        'before_discount' => single_price($before_discount),
                        'quantity' => $quantity, 
                        'digital' => $product->digital ,
                        'commission' => $product->unit_price - $product->purchase_price);

    }

    public function get_pick_ip_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request){
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.seller.digitalproducts.products', compact('products'));
    }
    public function show_digital_product_upload_form(Request $request)
    {
        if(\App\Models\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Models\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_digital_uploads > 0){
                $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
                $categories = Category::where('digital', 1)->get();
                return view('frontend.seller.digitalproducts.product_upload', compact('categories'));
            }
            else {
                flash('Upload limit has been reached. Please upgrade your package.')->warning();
                return back();
            }
        }

        $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
        $categories = Category::where('digital', 1)->get();
        return view('frontend.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->get();
        $product = Product::find(decrypt($id));
        return view('frontend.seller.digitalproducts.product_edit', compact('categories', 'product'));
    }
}
