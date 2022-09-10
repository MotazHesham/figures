<?php

namespace App\Http\Controllers\Admin\DeliveryMan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\Order;
use App\Models\Seller_balance;
use App\Models\ReceiptCompany;  
use App\Models\User;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\Receipt_social;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ReceiptCompanyResource;
use App\Http\Resources\ReceiptSocialResource;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Excel; 
use App\Support\Collection;

class DeliveryManOrdersController extends Controller
{
    public function print($order_code){
        $delivery = $this->order($order_code);  
        return view('admin.delivery_orders.delivery_order',compact('delivery'));
    }  

    public function index(Request $request)
    {
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors); 
        $delivery_man_users = User::where('user_type','delivery_man')->get();
        $countries = Country::get();


        $orders = Order::with('OrderDetails')->orderBy('send_to_deliveryman_date','desc');
        $receipt_companies = ReceiptCompany::orderBy('send_to_deliveryman_date','desc');
        $receipt_social = Receipt_social::with('receipt_social_products')->orderBy('send_to_deliveryman_date','desc');


        $type = $request->type;
        $phone = null;
        $client_name = null;
        $order_num = null;  
        $payment_status = null;
        $delivery_man_id = null;
        $from = null;
        $to = null;
        $calling = null;
        $country_id = null;

        if( Auth::user()->user_type == 'delivery_man'){
            $orders = $orders->where('delivery_man',Auth::user()->id);
            $receipt_companies = $receipt_companies->where('delivery_man',Auth::user()->id);
            $receipt_social = $receipt_social->where('delivery_man',Auth::user()->id);
        }
        if ($request->country_id != null) {
            $country_id = $request->country_id;
            $orders = $orders->where('shipping_country_id',$country_id);
            $receipt_companies = $receipt_companies->where('shipping_country_id',$country_id);
            $receipt_social = $receipt_social->where('shipping_country_id',$country_id);
        }
        if ($request->payment_status != null) {
            $payment_status = $request->payment_status;
            $orders = $orders->where('payment_status',$payment_status);
            $receipt_companies = $receipt_companies->where('payment_status',$payment_status);
            $receipt_social = $receipt_social->where('payment_status',$payment_status);
        }
        if( $request->delivery_man_id != null){
            $delivery_man_id = $request->delivery_man_id;
            $orders = $orders->where('delivery_man',$request->delivery_man_id);
            $receipt_companies = $receipt_companies->where('delivery_man',$request->delivery_man_id);
            $receipt_social = $receipt_social->where('delivery_man',$request->delivery_man_id);
        }
        if ($request->client_name != null){
            $orders = $orders->where('client_name', 'like', '%'.$request->client_name.'%');
            $receipt_companies = $receipt_companies->where('client_name', 'like', '%'.$request->client_name.'%');
            $receipt_social = $receipt_social->where('client_name', 'like', '%'.$request->client_name.'%');
            $client_name = $request->client_name;
        }
        if ($request->order_num != null){
            $orders = $orders->where('code', 'like', '%'.$request->order_num.'%');
            $receipt_companies = $receipt_companies->where('order_num', 'like', '%'.$request->order_num.'%');
            $receipt_social = $receipt_social->where('order_num', 'like', '%'.$request->order_num.'%');
            $order_num = $request->order_num;
        }
        if ($request->phone != null){
            global $phone;
            $phone = $request->phone; 

            $orders = $orders->where(function ($query) {
                                    $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                            ->orWhere('phone_number2', 'like', '%'.$GLOBALS['phone'].'%');
                                }); 

            $receipt_companies = $receipt_companies->where(function ($query) {
                                    $query->where('phone', 'like', '%'.$GLOBALS['phone'].'%')
                                            ->orWhere('phone2', 'like', '%'.$GLOBALS['phone'].'%');
                                }); 

            $receipt_social = $receipt_social->where(function ($query) {
                                    $query->where('phone', 'like', '%'.$GLOBALS['phone'].'%')
                                            ->orWhere('phone2', 'like', '%'.$GLOBALS['phone'].'%');
                                }); 
        } 
        if ($request->calling != null) { 
            $calling = $request->calling;
            $orders = $orders->where('calling',$request->calling);
            $receipt_companies = $receipt_companies->where('calling',$request->calling);
            $receipt_social = $receipt_social->where('calling',$request->calling);
        }
        if ($request->from != null && $request->to != null) {
            $from = strtotime($request->from);
            $to = strtotime($request->to);
            $orders = $orders->whereBetween('created_at',[date('Y-m-d',$from),date('Y-m-d',$to + 86400)]); 
            $receipt_companies = $receipt_companies->whereBetween('created_at',[date('Y-m-d',$from),date('Y-m-d',$to + 86400)]); 
            $receipt_social = $receipt_social->whereBetween('created_at',[date('Y-m-d',$from),date('Y-m-d',$to + 86400)]); 
        }

        if($type == 'supplied'){
            $orders = $orders->where('supplied',1);
            $receipt_companies = $receipt_companies->where('supplied',1);
            $receipt_social = $receipt_social->where('supplied',1);
        }else{
            $orders = $orders->where('supplied',0)->where('delivery_status',$type);
            $receipt_companies = $receipt_companies->where('supplied',0)->where('delivery_status',$type);
            $receipt_social = $receipt_social->where('supplied',0)->where('delivery_status',$type);
        }

        $orders_collection = collect(OrderResource::collection($orders->get()));
        $receipt_companies_collection = collect(ReceiptCompanyResource::collection($receipt_companies->get()));
        $receipt_social_collection = collect(ReceiptSocialResource::collection($receipt_social->get()));
        
        $merge = $orders_collection->merge($receipt_companies_collection);
        $items = $merge->merge($receipt_social_collection)->sortBy('send_to_deliveryman_date')->reverse()->values()->all();
        $items = (new Collection($items))->paginate(15); 
        return view('admin.delivery_orders.index',compact('items','generalsetting','delivery_man_users','countries','type',
                                                    'phone','client_name','order_num', 'payment_status',
                                                    'delivery_man_id','from', 'to','calling','country_id'));
    } 
    
    public function show($order_code){
        
        $order = $this->order($order_code);  
        if( Auth::user()->user_type == 'delivery_man'){ 
            if(in_array($order['delivery_status'],['on_delivery','delay'])){
                return view('admin.delivery_orders.show',compact('order'));
            }else{
                flash('Not Auth')->error();
                return redirect()->route('deliveryman.orders.index','on_delivery');
            } 
        }else{
            return view('admin.delivery_orders.show',compact('order'));
        } 
    }

    public function order($order_code){
        $order = ReceiptCompany::where('order_num',decrypt($order_code))->first();
        
        if($order){
            $resource = collect(new ReceiptCompanyResource($order)); 
        }else{
            $order = Order::where('code',decrypt($order_code))->first();
            if($order){ 
                $resource = collect(new OrderResource($order));
            }else{
                $order = Receipt_social::where('order_num',decrypt($order_code))->first();
                if($order){
                    $resource = collect(new ReceiptSocialResource($order));
                }else{
                    flash('Not Found')->error();
                    return redirect()->route('deliveryman.orders.index','on_delivery');
                }
            }
        }
        return $resource;
    }    


}
