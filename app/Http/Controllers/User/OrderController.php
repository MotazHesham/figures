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
use App\Models\Seller;
use App\Models\OrdersExport;
use App\Models\GeneralSetting;
use Auth;
use DB;
use Excel;
use Carbon\Carbon;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Controllers\Admin\WaslaController;

class OrderController extends Controller
{
    protected $view = 'frontend.orders.';

    public function print($id){
		$order = Order::findOrFail($id);
        if($order->user_id != Auth::user()->id){ 
            abort(403);
        }
        $generalsetting = GeneralSetting::first();
        return view('admin.orders.print',compact('order','generalsetting'));
    }

    public function check_phone_number(Request $request){
        $orders = Order::where('phone_number',$request->phone_number)->orWhere('phone_number2',$request->phone_number)->get();
        
        $output = '';

        if(count($orders) > 0){
            $output = '<p style="display:inline">*<b style="color:#9d3bd0">(Note)</b>This Phone Number has previous orders...</p><a href="'. route('view.orders.by.phone',$request->phone_number) .'" class="btn btn-outline-success" style="border-radius: 67%;">View ordes</ap>';
        }

        return $output;
    }

    public function view_orders_by_phone($phone){
        $orders = Order::where('phone_number',$phone)->orWhere('phone_number2',$phone)->paginate(10);

        return view($this->view . 'orders_by_phone',compact('orders'));
    }

    public function details(Request $request){
        $order = Order::findOrFail($request->order_id);
        $order->save();
        $authenticated_to_edit = $request->authenticated_to_edit;
        
        return view($this->view . 'show', compact('order','authenticated_to_edit'));
    }


    public function index(Request $request){ 

        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors); 
        $categories = Category::all();

        $authenticated_to_edit = 1;

        if(Auth::user()->user_type == 'staff' || Auth::user()->user_type == 'admin'){
            $orders = Order::where('user_id', '!=',null);
        }else{
            if(Auth::user()->user_type == 'seller'){
                $seller = Seller::where('user_id',Auth::id())->first();
                if($seller && $seller->seller_type == 'social'){
                    $orders = Order::where('social_user_id', Auth::user()->id);
                    $authenticated_to_edit = 0;
                }else{ 
                    $orders = Order::where('user_id', Auth::user()->id);
                }
            }else{
                $orders = Order::where('user_id', Auth::user()->id);
            }
        }

        $delivery_status = null;
        $phone = null;
        $from = null;
        $to = null; 
        $code = null; 

        if ($request->code != null) {
            $code = $request->code;
            $orders = $orders->where('code', 'like', '%'.$code.'%');
        }

        if ($request->delivery_status != null) {
            $delivery_status = $request->delivery_status;
            $orders = $orders->where('delivery_status',$delivery_status);
        }
        
        if ($request->has('phone') && $request->phone != null){
            global $phone;
            $phone = $request->phone;
            $orders = $orders->where(function ($query) {
                $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                        ->orWhere('phone_number2', 'like', '%'.$GLOBALS['phone'].'%');
            });
        } 

        if ($request->from != null && $request->to != null) {
            $from = strtotime($request->from);
            $to = strtotime($request->to);
            $orders = $orders->whereBetween('created_at',[date('Y-m-d',$from),date('Y-m-d',$to + 86400)]); 
        }


        if($request->has('download')){
            return Excel::download(new OrdersExport($orders->get()), 'orders.xlsx');
        }

        $orders = $orders->orderBy('created_at', 'desc')->paginate(6);
        if(Auth::user()->user_type == 'seller'){
            return view($this->view . 'index', compact('orders','categories' ,'delivery_status', 'phone' ,'from' , 'to','generalsetting','code','authenticated_to_edit'));
        }else{
            return view($this->view . 'index2', compact('orders','categories' ,'delivery_status', 'phone' ,'from' , 'to','generalsetting','code'));
        }
    }

    public function store(OrderRequest $request){
        
        try {
            DB::beginTransaction();

            $validated_request = $request->all(); 

            $validated_request['user_id']  = Auth::user()->id;
            $validated_request['date_of_receiving_order']  = strtotime($request->date_of_receiving_order);
            $validated_request['excepected_deliverd_date']  = strtotime($request->excepected_deliverd_date); 

            $country = Country::findOrFail($request->shipping_country);

            $validated_request['shipping_country_id']  = $country->id;
            $validated_request['shipping_country_name']  = $country->name;
            $validated_request['shipping_country_cost']  = $country->cost;

            $validated_request['commission']  = 0;
            $order = Order::create($validated_request);
            
            $order->code = 'order#' . $order->id;
    
            $product = Product::find($request->product_id); 
            $product->num_of_sale += $request->quntity;
            $product->save();
            
            if($product->variant_product == 1){
                
                //remove requested quantity from stock
                $product_stock = $product->stocks->where('variant', $request->variant)->first();
                $product_stock->qty -= $request->quntity;
                $product_stock->save();

                //add commission to seller
                $commission = ($product_stock->price  - $product_stock->purchase_price) * $request->quntity; 
                $order->commission = $order->commission + $commission;
                
            }
            else {
                //remove requested quantity from stock
                $product->current_stock -= $request->quntity;
                $product->save();

                //add commission to seller
                $commission = ($product->unit_price  - $product->purchase_price) * $request->quntity; 
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
            $order_detail->quantity = $request->quntity; 
            $order_detail->price = $request->price;
            $order_detail->total_cost = $request->price * $request->quntity;
            
            $order->required_to_pay = $order->required_to_pay + ( $request->price * $request->quntity );
    
            $photos = array();
            $photos_note = array();
    
            if($request->hasFile('photos')){
                foreach ($request->photos as $key => $photo) {
                    $path = $photo->store('uploads/seller/products/photos');
                    array_push($photos, $path); 
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

            $seller = Seller::where('user_id',$order->user_id)->first();
            $seller->order_in_website += 1 ;
            $seller->save();
            
            DB::commit();
    
            flash(__('Order has been requested successfully'))->success();
            return redirect()->back();
            
        }catch (\Exception $ex){
            DB::rollback();
            flash(__('SomeThing Went Wrong!'))->error();
            return redirect()->back();
        }
    } 

    public function edit($id){
        $order = Order::find($id);
        $countries = Country::all(); 
        
        if(Auth::user()->user_type == 'staff' || Auth::user()->user_type == 'admin'){
            // nothing
        }elseif($order->user_id != Auth::user()->id){ 
            flash(__('Not Auth'))->error();
            return back();
        } 
        
        if($order->delivery_status == 'pending'){
            return view($this->view . 'edit', compact('order','countries'));
        }else{
            flash(__('Order can not be edit'));
            return back();
        }
    }

    public function update(UpdateOrderRequest $request, $id){
        
        $order = Order::with('OrderDetails.product')->find($id);
        $validated_request = $request->except(['date_of_receiving_order','excepected_deliverd_date']);
        
        DB::beginTransaction();

        if($request->date_of_receiving_order != null){
            $validated_request['date_of_receiving_order']  = strtotime($request->date_of_receiving_order);
        }
        if($request->excepected_deliverd_date != null){
            $validated_request['excepected_deliverd_date']  = strtotime($request->excepected_deliverd_date); 
        }
        
        $country = Country::findOrFail($request->shipping_country);

        $validated_request['shipping_country_id']  = $country->id;
        $validated_request['shipping_country_name']  = $country->name;
        $validated_request['shipping_country_cost']  = $country->cost;
        $validated_request['free_shipping'] = $request->free_shipping == 'on' ? 1 : 0;;


        $order->update($validated_request);  

        DB::commit();

        flash(__('Order Updated Successfully'))->success();
        return back();
    }
    
    public function destroy($id){
        
        $order = Order::find($id);

        if($order->delivery_status == 'pending'){
            DB::beginTransaction();

            foreach($order->orderDetails as $raw){

                $product = Product::with('stocks')->find($raw->product_id); 
                $product->num_of_sale -= $raw->quantity;
                $product->save();

                if($raw->variation != null ){
                    $product_stock = $product->stocks->where('variant', $raw->variation)->first();
                    $product_stock->qty +=  $raw->quantity ;
                    $product_stock->save();
                    
                }else{ 
                    $product->current_stock += $raw->quantity ;
                    $product->save();
                }

                $raw->delete();
            } 
            if($order->delete()){
                if($order->user && $order->user->user_type == 'seller'){
                    $seller = Seller::where('user_id',$order->user_id)->first();
                    $seller->order_in_website -= 1 ;
                    $seller->save();
                }
            }
            
            DB::commit();
            flash(__('Order Deleted Successfully'))->success();
            return back();
        }else{
            flash(__('Order can not be edit'))->error();
            return back();
        }
    }
    
}
