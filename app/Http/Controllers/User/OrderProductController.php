<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Color;
use App\Models\Category;
use App\Models\Country;
use App\Models\OrderDetail; 
use App\Models\User;
use Auth;
use DB;

class OrderProductController extends Controller
{
    public function product_details_of_order(Request $request){
        $order_details = OrderDetail::find($request->id);
        return view('frontend.orders.product_details_of_order', compact('order_details'));
    }
    
    public function store(Request $request){

        $this->validate($request,[
            'quantity' => 'required|integer',
            'photos.*' => 'nullable|mimes:jpeg,png,jpg,ico|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048'
        ]); 

        try {

            DB::beginTransaction();
            
            $order = Order::find($request->order_code);
            $product = Product::find($request->product_id); 
            $product->num_of_sale += $request->quantity;
            $product->save();

            
            if($product->variant_product == 1){
        
                $product_stock = $product->stocks->where('variant', $request->variant)->first();
                $product_stock->qty -= $request->quantity;
                $product_stock->save();

                $commission = ($product_stock->price  - $product_stock->purchase_price) * $request->quantity; 
                $order->commission = $order->commission + $commission;
                
            }
            else {
                $product->current_stock -= $request->quantity;
                $product->save();

                $commission = ($product->unit_price  - $product->purchase_price) * $request->quantity; 
                $order->commission = $order->commission + $commission;
                
            }

            $order_detail = new OrderDetail;
            $order_detail->order_id  =$order->id;
            $order_detail->product_id = $product->id; 
            $order_detail->variation = $request->variant;
            $order_detail->link = $request->link;
            $order_detail->description = $request->description;
            $order_detail->commission = $commission;
            $order_detail->email_sent = $request->file_sent == 'on' ? 1 : 0;
            $order_detail->quantity = $request->quantity; 
            $order_detail->price = $request->price;
            $order_detail->total_cost = $request->price * $request->quantity;

            $order->required_to_pay = $order->required_to_pay + ( $request->price * $request->quantity ) ;

            $photos = array();
            $photos_note = array();

            if($request->hasFile('photos')){
                foreach ($request->photos as $key => $photo) {
                    $path = $photo->store('uploads/seller/products/photos');
                    array_push($photos, $path);
                    //ImageOptimizer::optimize(base_path('public/').$path);
                }
                $order_detail->photos = json_encode($photos);
            }
            
            if($request->has('photos_note')){ 
                foreach ($request->photos_note as $key => $note) { 
                    array_push($photos_note, $note); 
                }
                
                $order_detail->photos_note = json_encode($photos_note);
            }

            if($request->hasFile('pdf')){
                $order_detail->pdf = $request->pdf->store('uploads/seller/products/pdf');
            }
            

            $order_detail->save();
            $order->save(); 

            DB::commit();

            flash(__('Product has been added to order successfully'))->success();
            return redirect()->back();
        }catch (\Exception $ex){
            flash(__('SomeThing Went Wrong!'))->error();
            return redirect()->back();
        }
    }

    public function edit($id){
        $order_details = OrderDetail::find($id);
        $order = Order::find($order_details->order_id);
        
        if(!$order_details){ 
            flash(__('Not found'))->error();
            return back();
        }
        if(Auth::user()->user_type == 'staff' || Auth::user()->user_type == 'admin'){
            //nothing   
        }elseif($order->user_id != Auth::user()->id){ 
            flash(__('Not ِAuth'))->error();
            return back();
        } 

        if($order->delivery_status == 'pending'){
            return view('frontend.orders.edit_product_of_order', compact('order_details','order')); 
        }else{ 
            flash(__('Cant edit this product right now'))->error();
            return back();
            
        }
    }

    public function update(Request $request, $id){

        $this->validate($request,[
            'quantity' => 'required|integer',
            'photos.*' => 'nullable|mimes:jpeg,png,jpg,ico|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048'
        ]); 
        
        try{
            
            $order_detail = OrderDetail::find($id); 
            $order = Order::find($request->order_id); 

            if($order->delivery_status != 'pending'){ 
                flash(__('Cant edit this product right now'))->error();
                return back();
            }

            $product = Product::find($request->product_id); 
            $product->num_of_sale = $product->num_of_sale + ($request->quantity - $order_detail->quantity);
            $product->save(); 

            if($product->variant_product == 1){
                $product_stock = $product->stocks->where('variant', $order_detail->variation)->first();
                $product_stock->qty = $product_stock->qty - ($request->quantity - $order_detail->quantity);
                $product_stock->save();

                if(Auth::user()->user_type == 'seller'){
                    $commission = ($product_stock->price - $product_stock->purchase_price) * $request->quantity; 
                    $old_commission = ($product_stock->price - $product_stock->purchase_price) * $order_detail->quantity; 
                    $order->commission = $order->commission + ($commission - $old_commission );
                }
                $total = $order_detail->price * $request->quantity; 
            }
            else {
                $product->current_stock = $product->current_stock - ($request->quantity - $order_detail->quantity);
                $product->save();

                if(Auth::user()->user_type == 'seller'){
                    $commission = ($product->unit_price - $product->purchase_price) * $request->quantity; 
                    $old_commission = ($product->unit_price - $product->purchase_price) * $order_detail->quantity; 
                    $order->commission = $order->commission + ($commission - $old_commission );
                }
                $total = $order_detail->price * $request->quantity;
            } 

            $order->required_to_pay = $order->required_to_pay + ( $total - $order_detail->total_cost) ;
            
            $order_detail->link = $request->link;
            $order_detail->quantity = $request->quantity;  
            $order_detail->total_cost = $total;
            $order_detail->description = $request->description;
            $order_detail->email_sent = $request->file_sent == 'on' ? 1 : 0;
            

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
            
            $order_detail->photos = json_encode($photos);
            
            
            if($request->has('photos_note')){ 
                foreach ($request->photos_note as $key => $note) { 
                    array_push($photos_note, $note); 
                }
                
                $order_detail->photos_note = json_encode($photos_note);
            }

            if($request->hasFile('pdf')){
                $order_detail->pdf = $request->pdf->store('uploads/seller/products/pdf');
            }
            

            $order_detail->save();
            $order->save();
            
            flash(__('Product has been Updated successfully'))->success();
            return redirect()->back();
        }catch (\Exception $ex){
            flash(__('SomeThing Went Wrong!'))->error();
            return redirect()->back();
        }
    }

    public function delete($id){ 
        $order_detail = OrderDetail::find($id);
        $order = Order::with('orderDetails')->find($order_detail->order_id);
        $product = Product::find($order_detail->product_id);

        if($order->delivery_status != 'pending'){ 
            flash(__('Cant delete this product right now'))->error();
            return back();
        }

        if($order_detail->quantity > 0 ){
            $qty = $order_detail->quantity;
        }else{
            $qty = 1;
        }

        $product->num_of_sale -= $qty;
        $product->save();

        if($order_detail->variation != null ){
            $product_stock = $product->stocks->where('variant', $order_detail->variation)->first();
            $product_stock->qty +=  $qty ;
            $product_stock->save();

            
            $commission = ($product_stock->price - $product_stock->purchase_price) * $qty; 
            $order->commission = $order->commission - $commission;
            $order->required_to_pay = $order->required_to_pay - $order_detail->total_cost;
            $order->save();
        }else{ 
            $product->current_stock += $qty ;
            $product->save();

            $commission = ($product->unit_price - $product->purchase_price) * $qty; 
            $order->commission = $order->commission - $commission;
            $order->required_to_pay = $order->required_to_pay - $order_detail->total_cost;
            $order->save();
        }
        

        $order_detail->delete();

        flash(__('Product Deleted From Order'))->success();
        return back();
    } 
}
