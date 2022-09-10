<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommissionRequest;
use App\Models\CommissionRequestOrders;
use App\Models\Order;
use App\Models\GeneralSetting;
use App\Models\User; 
use App\Models\UserAlert; 
use App\Http\Controllers\PushNotificationController;
use Auth;
use DB;


class CommissionRequestController extends Controller
{
    public function seller(){
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors); 
        $commission_requests = CommissionRequest::where('user_id',Auth::user()->id)->with(['user','by_user','done_by_user','commission_request_orders.order'])
                                                    ->orderBy('created_at','desc')
                                                    ->get(); 
        return view('frontend.orders.commission_requests.index',compact('commission_requests','generalsetting'));
    }

    public function seller_edit($id){ 
        $commission_request = CommissionRequest::findOrFail(decrypt($id));
        return view('frontend.orders.commission_requests.edit',compact('commission_request'));
    }

    public function index(){
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors); 
        $commission_requests = CommissionRequest::with(['user','by_user','done_by_user','commission_request_orders.order'])
                                                    ->orderBy('created_at','desc')
                                                    ->get(); 
        return view('admin.orders.commission_requests.index',compact('commission_requests','generalsetting'));
    }

    public function store(Request $request)
    {
        
        if($request->orders == null){
            flash('Please Select at least one order')->error();
            return back();
        }else{

            DB::beginTransaction();
            $commission_request_orders = [];
            $total_commission = 0;

            $commission_request = new CommissionRequest;
            $commission_request->user_id = Order::find($request->orders[0])->user_id;
            $commission_request->by_user_id = Auth::user()->id;
            $commission_request->total_commission = $total_commission;
            $commission_request->payment_method = $request->payment_method;
            $commission_request->transfer_number = $request->transfer_number;
            $commission_request->save();

            foreach ($request->orders as $order_id) {
                $order = Order::find($order_id);
                $commission_request_orders[] = [
                    'order_id' => $order_id,
                    'commission' => $order->commission + $order->extra_commission,
                    'commission_request_id' => $commission_request->id
                ];
                $total_commission += $order->commission + $order->extra_commission;

                $order->commission_status = 'requested';
                $order->save();
            }

            $commission_request->total_commission = $total_commission;
            $commission_request->save();

            CommissionRequestOrders::insert($commission_request_orders);

            $title = Auth::user()->email;
            $body = 'طلب سحب جديد';
            UserAlert::create([
                'alert_text' => $title . ' ' . $body . ' من ',
                'alert_link' => route('orders.request_commission.index'),
                'type' => 'commission',
                'user_id' => 0 ,
            ]);  

            $tokens = User::whereNotNull('device_token')->whereIn('user_type',['staff','admin'])->where(function ($query) {
                                                            $query->where('notification_show',1)
                                                                    ->orWhere('user_type','admin');
                                                        })->pluck('device_token')->all(); 
            $push_controller = new PushNotificationController();
            $push_controller->sendNotification($title, $body, $tokens,route('orders.request_commission.index')); 

            DB::commit();

            flash('Commission Requested Successfully')->success();
            return back();
        } 
    }
    
    public function edit($id){ 
        $commission_request = CommissionRequest::findOrFail(decrypt($id));
        return view('admin.orders.commission_requests.edit',compact('commission_request'));
    }

    public function update(Request $request){
        $commission_request = CommissionRequest::findOrFail($request->id);
        $commission_request->payment_method = $request->payment_method;
        $commission_request->transfer_number = $request->transfer_number;
        $commission_request->save();
        flash('Updated successfully')->success();  
        return back();
    }
    public function pay($id){
        $commission_request = CommissionRequest::findOrFail(decrypt($id));
        $commission_request_orders = CommissionRequestOrders::where('commission_request_id', decrypt($id))->get();
        foreach($commission_request_orders as $raw){
            $order = Order::find($raw->order_id);
            $order->commission_status = 'delivered';
            $order->save();
            
        }
        $commission_request->status = 'delivered';
        $commission_request->done_by_user_id = Auth::user()->id;
        $commission_request->done_time = time();
        $commission_request->save();  
        flash('Paid successfully')->success();  
        return back();
    }

    public function destroy($id){
        $commission_request = CommissionRequest::findOrFail($id);
        $commission_request_orders = CommissionRequestOrders::where('commission_request_id', $id)->get();
        foreach($commission_request_orders as $raw){
            $order = Order::find($raw->order_id);
            $order->commission_status = 'pending';
            $order->save();

            $raw->delete();
        }
        $commission_request->delete();  
        flash('Deleted successfully')->success();  
        return back();
    }
}
