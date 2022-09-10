<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubSubCategory;
use App\Models\Category;
use Session;
use App\Models\Color;
use App\Models\Cart;
use Cookie;
use Auth;
use App\Http\Controllers\Admin\ConversionApiController;

class CartController extends Controller
{
    public function index(Request $request)
    {
        //dd($cart->all());
        $categories = Category::all();
        return view('frontend.view_cart', compact('categories'));
    }

    public function update(Request $request, $id){

        $this->validate($request,[
            'quantity' => 'required|integer',
            'photos.*' => 'nullable|mimes:jpeg,png,jpg,ico|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048'
        ]);


            $cart = Cart::findOrFail($id);
            $product = Product::find($cart->product_id);

            if($product->variant_product == 1){
                $product_stock = $product->stocks->where('variant', $cart->variation)->first();

                $commission = ($product_stock->price - $product_stock->purchase_price) * $request->quantity;

                $total = $cart->price * $request->quantity;
            }else {
                $commission = ($product->unit_price - $product->purchase_price) * $request->quantity;

                $total = $cart->price * $request->quantity;
            }

            $cart->link = $request->link;
            $cart->quantity = $request->quantity;
            $cart->total_cost = $total;
            $cart->description = $request->description;
            //if(Auth::user()->user_type == 'seller'){
            $cart->commission = $commission;
            //}
            $cart->email_sent = $request->file_sent == 'on' ? 1 : 0;

            $photos_note = array();

            if($request->has('previous_photos')){
                $photos = $request->previous_photos;
            }
            else{
                $photos = array();
            }

            if($request->hasFile('photos')){
                foreach ($request->photos as $key => $photo) {
                    $path = $photo->store('uploads/seller/products/photos');
                    array_push($photos, $path);
                }
            }

            $cart->photos = json_encode($photos);


            if($request->has('photos_note')){
                foreach ($request->photos_note as $key => $note) {
                    array_push($photos_note, $note);
                }

                $cart->photos_note = json_encode($photos_note);
            }

            if($request->hasFile('pdf')){
                $cart->pdf = $request->pdf->store('uploads/seller/products/pdf');
            }


            $cart->save();

            flash(__('Product In Cart Updated successfully'))->success();
            return redirect()->back();
    }


    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }

    public function edit(Request $request)
    {
        $cartItem = Cart::find($request->id);
        return view('frontend.partials.edit_product_in_cart', compact('cartItem'));
    }

    public function updateNavCart(Request $request)
    {
        return view('frontend.partials.cart');
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);

        if($product->variant_product == 1){

            $product_stock = $product->stocks->where('variant', $request->variant)->first();

            $commission = ($product_stock->price  - $product_stock->purchase_price) * $request->quantity;

        }else {

            $commission = ($product->unit_price  - $product->purchase_price) * $request->quantity;
        }

        $cart = new Cart;
        $cart->user_id  = Auth::id();
        $cart->product_id = $product->id;
        $cart->variation = $request->variant;
        $cart->description = $request->description;
        $cart->quantity = $request->quantity;
        $cart->price = $request->price;
        $cart->chosen_photo = $request->chosen_photo;
        $cart->total_cost = $request->price * $request->quantity;
        if(Auth::user()->user_type == 'seller'){
            $cart->commission = $commission;
            $cart->email_sent = $request->file_sent == 'on' ? 1 : 0;
            $cart->link = $request->link;
        }else{
            $cart->commission = $commission;
            $cart->email_sent = 0;
            $cart->link = null;
            if($request->hasFile('pdf')){
                $cart->pdf = $request->pdf->store('uploads/seller/products/pdf');
            }
        }

        $photos = array();
        $photos_note = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/seller/products/photos');
                array_push($photos, $path);
            }
            $cart->photos = json_encode($photos);
        }

        if($request->has('photos_note')){
            foreach ($request->photos_note as $key => $note) {
                array_push($photos_note, $note);
            }

            $cart->photos_note = json_encode($photos_note);
        }

        $cart->save();

        $conversionApiController = new ConversionApiController;
        $conversionApiController->event($product->id,$request->quantity,$request->price,route('product', $product->slug),'Add To Cart');

        flash(__('Success Added To Cart'))->success();
        return back();
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::find($request->id)->delete();
        return view('frontend.partials.cart_details');
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cart = Cart::find($request->id);
        $cart->quantity = $request->quantity;
        $cart->total_cost = $request->quantity * $cart->price;
        $cart->save();

        return view('frontend.partials.cart_details');
    }
}
