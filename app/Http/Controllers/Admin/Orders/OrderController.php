<?php

namespace App\Http\Controllers\Admin\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Color;
use App\Models\Category;
use App\Models\Country;
use App\Models\OrderDetail; 
use App\Models\Seller_balance;
use App\Models\OtpConfiguration;
use App\Models\ReceiptCompany;
use App\Models\User; 
use App\Models\UserAlert; 
use App\Http\Controllers\PushNotificationController;
use App\Models\BusinessSetting;
use App\Models\GeneralSetting;
use App\Models\OrdersExport;
use App\Models\Seller;
use Auth;
use Session;
use DB;
use PDF;
use Mail;
use Excel;
use App\Mail\InvoiceEmailManager;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\Admin\WaslaController;

class OrderController extends Controller
{
    public function send_to_wasla(Request $request){
        $order = Order::find($request->order_id);
        $company_id = Auth::user()->wasla_company_id;

        $data = [
            //from order
            'company_id' => $company_id,
            'receiver_name' => $order->client_name,
            'phone_1' => $order->phone_number,
            'phone_2' => $order->phone_number2,
            'address' => $order->shipping_address, 
            'description' => $order->description ?? '...',
            'note' => $order->note,
            'receipt_code' => $order->code,

            //from form
            'district' => $request->district,
            'type' => $request->type, 
            'cost' => $request->cost,
            'in_return_case' => $request->in_return_case, 
            'country_id' => $request->country_id,
            'status' => $request->status,
        ]; 

        $waslaController = new WaslaController;
        $response = $waslaController->store_order($data); 
        if($response){
            if($response['errNum'] == 200){
                $order->sent_to_wasla = 1;
                $order->save();
                flash('تم أرسال الأوردر لواصلة بنجاح');
            }elseif($response['errNum'] == 401){
                if($response['msg']['company_id'] ?? null){
                    flash($response['msg']['company_id'][0]);
                }
                if($response['msg']['receiver_name'] ?? null){
                    flash($response['msg']['receiver_name'][0]);
                }
                if($response['msg']['phone_1'] ?? null){
                    flash($response['msg']['phone_1'][0]);
                }
                if($response['msg']['phone_2'] ?? null){
                    flash($response['msg']['phone_2'][0]);
                }
                if($response['msg']['district'] ?? null){
                    flash($response['msg']['district'][0]);
                }
                if($response['msg']['address'] ?? null){
                    flash($response['msg']['address'][0]);
                }
                if($response['msg']['cost'] ?? null){
                    flash($response['msg']['cost'][0]);
                }
                if($response['msg']['in_return_case'] ?? null){
                    flash($response['msg']['in_return_case'][0]);
                }
                if($response['msg']['type'] ?? null){
                    flash($response['msg']['type'][0]);
                }
                if($response['msg']['description'] ?? null){
                    flash($response['msg']['description'][0]);
                }
                if($response['msg']['note'] ?? null){
                    flash($response['msg']['note'][0]);
                }
                if($response['msg']['receipt_code'] ?? null){
                    flash($response['msg']['receipt_code'][0]);
                }
                if($response['msg']['country_id'] ?? null){
                    flash($response['msg']['country_id'][0]);
                }
                if($response['msg']['status'] ?? null){
                    flash($response['msg']['status'][0]);
                }
            }else{
                flash('SomeThing Went Wrong000');
            }
        }else{
            flash('SomeThing Went Wrong');
        }
        return back();
    }

    public function print($id){
		$order = Order::findOrFail($id);
        $generalsetting = GeneralSetting::first();
        return view('admin.orders.print',compact('order','generalsetting'));
    }

    public function show_details(Request $request){
        $order_details = OrderDetail::findOrFail($request->id);
        
        return view('admin.orders.show_details', compact('order_details'));
    }

    public function update_extra_commission(Request $request){
        $order_details = OrderDetail::findOrFail($request->order_detail_id);
        $order_details->extra_commission = $request->extra_commission;
        if($order_details->save()){
            $order = Order::with('orderDetails')->find($order_details->order_id);
            $extra_commission = 0;
            foreach($order->orderDetails as $raw){
                $extra_commission = $extra_commission + $raw->extra_commission;
            }
            $order->extra_commission = $extra_commission;
            $order->save();
        } 
        flash(__('Extra Comission Updated Successfully'))->success();
        return back();
    }

    public function updatecalling(Request $request){
        $order = Order::findOrFail($request->id);
        $order->calling = $request->status;
        $order->save();
        return 1; 
    } 

    public function update_delivery_man(Request $request)
    {
        $order = Order::with('orderDetails.product')->find($request->order_id);
        $order->delivery_man = $request->user_id;
        $order->save(); 
        return 1;
    }


    public function updatesupplied(Request $request)
    {
        $order = Order::where('code',$request->order_num)->first(); 
        $order->supplied = $request->status;
        $order->save(); 
        return 1;
    }
    
    public function update_delivery_status(Request $request)
    {
        $order = Order::where('code',$request->order_num)->first(); 
        $order->delivery_status = $request->status;
        if($request->status == 'delivered'){
            $order->done_time = strtotime(date('Y-m-d H:i:s'));
        }
        $order->save();

        return 1;
    }
    
    public function update_payment_status(Request $request)
    {
        $order = Order::where('code',$request->order_num)->first(); 
        $order->payment_status = $request->status; 
        $order->save();

        return 1;
    }

    
    public function playlist_users(Request $request){
        $raw = Order::where('code',$request->order_num)->first(); 
        $staffs = User::whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();
        $generalsetting = GeneralSetting::first(); 
        return view('admin.playlist.select_users',compact('raw','staffs','generalsetting'));
    }

    public function update_playlist_status2(Request $request)
    {
        $order = Order::where('code',$request->order_num)->first(); 
        if($order->playlist_status == 'pending'){
            $order->send_to_playlist_date = date('Y-m-d H:i:s');
        }
        $order->playlist_status = $request->status; 
        $order->designer_id = $request->designer_id; 
        $order->manifacturer_id = $request->manifacturer_id; 
        $order->preparer_id = $request->preparer_id; 
        $order->save();

        $title = 'فاتورة جديدة';
        $body = $order->code;
        UserAlert::create([
            'alert_text' => $title . ' ' . $body,
            'alert_link' => route('playlist.index','design'),
            'type' => 'private',
            'user_id' => $request->designer_id ,
        ]);

        $user = User::find($request->designer_id);
        if($user->device_token != null){
            $tokens = array();
            array_push($tokens,$user->device_token);  
            $push_controller = new PushNotificationController();
            $push_controller->sendNotification($title, $body, $tokens,route('playlist.index','design'));
        }

        flash('تم الأرسال')->success();
        return back();
    }

    public function cancel_order_reason(Request $request)
    { 
        $order = Order::where('code',$request->order_num)->first(); 
        $order->cancel_reason = $request->cancel_reason;
        $order->save(); 
        return 1;
    }

    public function order_delay_reason(Request $request)
    { 
        $order = Order::where('code',$request->order_num)->first(); 
        $order->delay_reason = $request->delay_reason;
        $order->save(); 
        return 1;
    }



    public function update_order_note(Request $request)
    {
        
        $order = Order::where('code',$request->order_num)->first();
        $order->note = $request->note;
        $order->save();
        flash(_('Done!!'))->success();
        return back();
    }

    
    public function edit_product_of_order($id){ 
        $order_details = OrderDetail::find($id);
        $order = Order::with('orderDetails')->find($order_details->order_id);

        if(!$order_details){ 
            flash(__('Not found'))->error();
            return back();
        }
        
        return view('admin.orders.edit_product_of_order', compact('order_details','order')); 
    }

    public function delete_product_of_order($id){ 
        $order_detail = OrderDetail::find($id);
        $order = Order::with('orderDetails')->find($order_detail->order_id);
        if(!in_array($order->playlist_status,['pending'])){
            flash('لايمكن تعديل هذه الفاتورة')->error();
            return redirect()->route('admin.orders.index',$order->order_type);
        }
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

    public function index(Request $request,$type)
    {
        $order_from_user_type = $type;

        $orders_count = Order::where('order_type',$order_from_user_type)->get();
        
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors); 
        $countries = Country::get();
        if($order_from_user_type == 'seller'){
            $sellers = User::join('sellers', 'users.id', '=', 'sellers.user_id')->orderBy('sellers.verification_status', 'desc')->select('users.*')->get();
        }elseif($order_from_user_type == 'customer'){
            $sellers = User::where('user_type','customer')->get();
        }

        $phone = null;
        $client_name = null;
        $order_num = null; 
        $delivery_status = null;
        $payment_status = null;
        $seller_id = null;
        $from = null;
        $to = null;
        $calling = null;
        $country_id = null;
        $commission_status = null;
        $playlist_status = null;
        $sent_to_wasla = null;
        $orders_type = null;


        $orders = Order::with('orderDetails')->where('order_type',$order_from_user_type);
        
        if ($request->orders_type != null){
            if($request->orders_type == 'discount_code'){ 
                $orders = $orders->whereNotNull('discount_code');
            }
            $orders_type = $request->orders_type;
        }
        
        if ($request->sent_to_wasla != null){
            $orders = $orders->where('sent_to_wasla',$request->sent_to_wasla);
            $sent_to_wasla = $request->sent_to_wasla;
        }
        if ($request->delivery_status != null) {
            $delivery_status = $request->delivery_status;
            $orders = $orders->where('delivery_status',$delivery_status);
        }
        
        if ($request->payment_status != null) {
            $payment_status = $request->payment_status;
            $orders = $orders->where('payment_status',$payment_status);
        }
        if ($request->playlist_status != null) {
            $playlist_status = $request->playlist_status;
            $orders = $orders->where('playlist_status',$playlist_status);
        }

        if ($request->country_id != null) {
            $country_id = $request->country_id;
            $orders = $orders->where('shipping_country_id',$country_id);
        }

        if ($request->commission_status != null) {
            $commission_status = $request->commission_status;
            $orders = $orders->where('commission_status',$commission_status);
        }
        
        
        if ($request->calling != null) { 
            $calling = $request->calling;
            $orders = $orders->where('calling',$calling);
        }
        
        if ($request->client_name != null){
            $orders = $orders->where('client_name', 'like', '%'.$request->client_name.'%');
            $client_name = $request->client_name;
        }
        if ($request->order_num != null){
            $orders = $orders->where('code', 'like', '%'.$request->order_num.'%');
            $order_num = $request->order_num;
        }
        
        if ($request->seller_id != null) { 
            $seller_id = $request->seller_id;
            $orders = $orders->where('user_id',$request->seller_id);
        }
        
        if ($request->phone != null){
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
        
        $total_total = $orders->sum('required_to_pay') + $orders->sum('extra_commission') ;
        $total_commission = $orders->sum('commission') + $orders->sum('extra_commission') ;

        $statistics = [ 
            'total_total' => $total_total,
            'total_commission' => $total_commission,
        ];
        $orders = $orders->orderBy('created_at', 'desc')->paginate(15); 
        return view('admin.orders.index', compact('statistics','orders','country_id','order_from_user_type',
                                            'payment_status','delivery_status','calling', 'playlist_status',
                                            'client_name','phone' ,'order_num', 'countries','sellers',
                                            'seller_id','from' , 'to','orders_count','generalsetting','commission_status','sent_to_wasla','orders_type'));
    }

    public function edit($id){
        $order = Order::find($id);
        $countries = Country::all();
        return view('admin.orders.edit', compact('order','countries'));
    }

    public function show($id)
    { 
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        $users_staff = User::where('user_type','delivery_man')->get();
        $waslaController = new WaslaController;
        $response = $waslaController->countries(); 
        return view('admin.orders.show', compact('order','users_staff','response'));
    } 

    public function destroy($id){
        
        $order = Order::find($id);

        if(Auth::user()->user_type != 'admin'){
            if(!in_array($order->playlist_status,['pending'])){
                flash('لايمكن حذف هذه الفاتورة')->error();
                return redirect()->route('admin.orders.index',$order->order_type);
            }
        }
        if($order->delivery_status == 'pending'){
            DB::beginTransaction();

            foreach($order->orderDetails as $raw){

                $product = Product::with('stocks')->find($raw->product_id); 
                if($product){
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
                }

                $raw->delete();
            }
            

            if($order->delete()){
                if($order->order_type == 'seller'){
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