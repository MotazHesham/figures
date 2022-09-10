<?php

namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Language;
use App\Models\Seller_product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Attribute;
use Auth;
use Session;
use ImageOptimizer;
use DB;
use Image;

class ProductController extends Controller
{

    protected $view = 'admin.products.products.';

    public function duplicate($id)
    {
        $product = Product::find($id);
        $product_new = $product->replicate();
        $product_new->slug = substr($product_new->slug, 0, -5).str_random(5);

        if($product_new->save()){
            flash(__('Product has been duplicated successfully'))->success();
            return redirect()->route('products.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return redirect()->route('products.index');
        }
    }

    public function get_products_by_subsubcategory(Request $request)
    {
        $products = Product::where('subsubcategory_id', $request->subsubcategory_id)->get();
        return $products;
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function update_stock(Request $request){
        $products = Product::where('category_id',$request->category_id);
        if($request->has('subcategory_id') && $request->subcategory_id != null ){
            $products = $products->where('subcategory_id',$request->subcategory_id);
        }
        if($request->has('subsubcategory_id') && $request->subsubcategory_id != null){
            $products = $products->where('subsubcategory_id',$request->subsubcategory_id);
        }

        $products = $products->get();

        if($products->count() < 1){
            flash(__('No Products Found'))->error();
            return back();
        }

        foreach($products as $product){
            $product->unit = $request->unit;
            $product->current_stock = $request->quantity;
            $product->save();
            if($product->variant_product){
                $product_stock = ProductStock::where('product_id',$product->id)->get();
                $count = $product_stock->count();
                if($count > 1){
                    foreach($product_stock as $stock){
                        $stock->qty = $request->quantity;
                        $stock->save();
                    }
                }
            }
        }


        flash(__('Stock Updated Successfully'))->success();

        return redirect()->route('products.index');
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;


        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $purchase_price = $request->purchase_price;
        $product_name = $request->name;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations', compact('combinations', 'unit_price', 'purchase_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;
        $purchase_price = $request->purchase_price;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations_edit', compact('combinations', 'unit_price', 'purchase_price', 'colors_active', 'product_name', 'product'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $categories = Category::all();
        $subcategories = SubCategory::all();
        $subsubcategories = SubSubCategory::all();

        $sort_search = null;
        $sort_categories = null;
        $sort_subcategories = null;
        $sort_subsubcategories = null;
        $featured = null;
        $special = null;

        $products = Product::with('stocks');

        if ($request->categories != null){
            $products = $products->where('category_id', $request->categories);
            $sort_categories = $request->categories;
        }
        if ($request->subcategories != null){
            $products = $products->where('subcategory_id', $request->subcategories);
            $sort_subcategories = $request->subcategories;
        }
        if ($request->subsubcategories != null){
            $products = $products->where('subsubcategory_id', $request->subsubcategories);
            $sort_subsubcategories = $request->subsubcategories;
        }
        if ($request->featured != null){
            $products = $products->where('featured', $request->featured);
            $featured = $request->featured;
        }
        if ($request->special != null){
            $products = $products->where('special', $request->special);
            $special = $request->special;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(10);
        return view($this->view . 'index', compact('products', 'sort_search','featured' ,'special',
                                            'sort_categories', 'sort_subcategories' , 'sort_subsubcategories',
                                            'categories', 'subcategories','subsubcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::orderBy('name', 'asc')->get();
        $attributes = Attribute::all();
        return view($this->view . 'create', compact('categories','brands','colors','attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->description == null){
            flash('حقل الوصف مطلوب')->error();
            return back();
        }

        $product = new Product;
        $product->name = $request->name;
        $product->added_by = $request->added_by;
        $product->user_id = User::where('user_type', 'admin')->first()->id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->special = $request->special;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;



        $product->unit = $request->unit;
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->shipping_type = $request->shipping_type;
        if ($request->has('shipping_type')) {
            if($request->shipping_type == 'free'){
                $product->shipping_cost = 0;
            }
            elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            }
        }
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        // if($request->hasFile('meta_img')){
        //     $product->meta_img = $request->meta_img->store('uploads/products/meta');
        //     //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        // }


        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        }
        else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        //$variations = array();

        $product->save();


        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $image_name = time() . '_' . $key . rand(1,20) . '.' .$photo->getClientOriginalExtension();
                $destinationPath = public_path('uploads/products/photos/'.$image_name);
                $img = Image::make($photo->getRealPath());
                $img->resize(700, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath);
                array_push($photos, 'uploads/products/photos/'.$image_name);
            }
        }
        $product->photos = json_encode($photos);

        $same_image =  $product->photos;

        if(count(json_decode($same_image)) > 1){
            $q = strstr(substr($same_image,2),',',true);
            $q = substr($q,0,-1);
        }else{
            $q = substr($same_image,2,-2);
        }

        $q = str_replace("\\","",$q);

        $product->thumbnail_img = $q ;
        $product->featured_img = $q ;
        $product->flash_deal_img = $q ;
        $product->meta_img = $q ;

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Models\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                // $item = array();
                // $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                // $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                // $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                // $variations[$str] = $item;

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }

                $product_stock->variant = $str;
                $product_stock->price = $request['price_'.str_replace('.', '_', $str)];
                $product_stock->purchase_price = $request['purchase_price_'.str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];
                $product_stock->save();
            }
        }
        //combinations end

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     $data[$product->name] = $product->name;
        //     saveJSONFile($language->code, $data);
        // }

	    $product->save();

        flash(__('Product has been inserted successfully'))->success();
        return redirect()->route('products.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        $tags = json_decode($product->tags);
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::orderBy('name', 'asc')->get();
        $attributes = Attribute::all();
        return view($this->view . 'edit', compact('product', 'categories', 'tags','brands','colors','attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->special = $request->special;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;

        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $image_name = time() . '_' . $key . rand(1,20) . '.' . $photo->getClientOriginalExtension();
                $destinationPath = public_path('uploads/products/photos/'.$image_name);
                $img = Image::make($photo->getRealPath());
                $img->resize(700, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath);
                array_push($photos, 'uploads/products/photos/'.$image_name);
            }
        }
        $product->photos = json_encode($photos);

        $same_image =  $product->photos;

        if(count(json_decode($same_image)) > 1){
            $q = strstr(substr($same_image,2),',',true);
            $q = substr($q,0,-1);
        }else{
            $q = substr($same_image,2,-2);
        }

        $q = str_replace("\\","",$q);

        $product->thumbnail_img = $q ;
        $product->featured_img = $q ;
        $product->flash_deal_img = $q ;
        $product->meta_img = $q ;

        $product->unit = $request->unit;
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->shipping_type = $request->shipping_type;
        if ($request->has('shipping_type')) {
            if($request->shipping_type == 'free'){
                $product->shipping_cost = 0;
            }
            elseif ($request->shipping_type == 'flat_rate') {
                $product->shipping_cost = $request->flat_shipping_cost;
            }
        }
        $product->discount_type = $request->discount_type;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;


        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.substr($product->slug, -5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if($product->attributes != json_encode($request->choice_attributes)){
            foreach ($product->stocks as $key => $stock) {
                $stock->delete();
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        }
        else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$product->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // }

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Models\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }

                $product_stock->variant = $str;
                $product_stock->price = $request['price_'.str_replace('.', '_', $str)];
                $product_stock->purchase_price = $request['purchase_price_'.str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];

                $product_stock->save();
            }
        }

        $product->save();

        flash(__('Product has been updated successfully'))->success();
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if(Product::destroy($id)){
            // foreach (Language::all() as $key => $language) {
            //     $data = openJSONFile($language->code);
            //     unset($data[$product->name]);
            //     saveJSONFile($language->code, $data);
            // }
            flash(__('Product has been deleted successfully'))->success();
            return redirect()->route('products.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return redirect()->route('products.index');
        }
    }

}
