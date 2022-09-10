<?php

namespace App\Http\Controllers\Admin\PlayList;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReceiptCompany;
use App\Models\Receipt_social;
use App\Models\Order;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserAlert;
use App\Http\Controllers\PushNotificationController;
use App\Models\Country;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ReceiptCompanyResource;
use App\Http\Resources\ReceiptSocialResource;
use DB;
use App\Support\Collection;
use Auth;

class PlaylistController extends Controller
{
    public function print(Request $request){
        $receipt_company = ReceiptCompany::where('order_num',$request->order_num)->first();

        if($receipt_company){
            $receipt_company->viewed = 1;
            $receipt_company->save();
            return view('admin.receipts.receipt_company.receipt_company',compact('receipt_company'));
        }else{
            $order = Order::where('code',$request->order_num)->first();
            if($order){
                $generalsetting = GeneralSetting::first();
                return view('admin.orders.print',compact('order','generalsetting'));
            }else{
                $receipt_social = Receipt_social::where('order_num',$request->order_num)->first();
                if($receipt_social){
                    $receipt_social->viewed = 1;
                    $receipt_social->save();
                    return view('admin.receipts.receipt_social.new_receipt_social',compact('receipt_social'));
                }else{
                    //flash('Not Found')->error();
                    return 'not found';
                }
            }
        }
    }

    public function index(Request $request){
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);
        $users = User::whereIn('user_type',['staff','seller'])->get();

        $type = $request->type;

        $orders = Order::with('OrderDetails')->orderBy('send_to_playlist_date','desc')->where('playlist_status',$type);
        $receipt_companies = ReceiptCompany::orderBy('send_to_playlist_date','desc')->where('playlist_status',$type);
        $receipt_social = Receipt_social::with('receipt_social_products')->orderBy('send_to_playlist_date','desc')->where('playlist_status',$type);


        $order_num = null;
        $user_id = null;
        $description = null;

        if( $request->user_id != null){
            $user_id = $request->user_id;
            $orders = $orders->where('user_id',$request->user_id);
            $receipt_companies = $receipt_companies->where('staff_id',$request->user_id);
            $receipt_social = $receipt_social->where('staff_id',$request->user_id);
        }
        if ($request->order_num != null){
            $order_num = $request->order_num;
            $orders = $orders->where('code', 'like', '%'.$request->order_num.'%');
            $receipt_companies = $receipt_companies->where('order_num', 'like', '%'.$request->order_num.'%');
            $receipt_social = $receipt_social->where('order_num', 'like', '%'.$request->order_num.'%');
        }

        if ($request->description != null){
            $description = $request->description;
            $orders = $orders->whereHas('orderDetails', function ($query) use ($description){
                $query->where('description', 'like', '%'.$description.'%');
            });
            $receipt_companies = $receipt_companies->where('description', 'like', '%'.$request->description.'%');
            $receipt_social = $receipt_social->whereHas('receipt_social_products', function ($query) use ($description){
                $query->where('description', 'like', '%'.$description.'%')
                    ->orWhere('title', 'like', '%'.$description.'%');
            });
        }

        $orders_collection = collect(OrderResource::collection($orders->get()));
        $receipt_companies_collection = collect(ReceiptCompanyResource::collection($receipt_companies->get()));
        $receipt_social_collection = collect(ReceiptSocialResource::collection($receipt_social->get()));

        $merge = $orders_collection->merge($receipt_companies_collection);
        $items = $merge->merge($receipt_social_collection)->sortBy('send_to_playlist_date')->reverse()->values()->all();
        $items = (new Collection($items))->paginate(15);

        return view('admin.playlist.index',compact('items','generalsetting','users','type', 'order_num','user_id','description'));
    }

    public function show(Request $request){
        $order = ReceiptCompany::where('order_num',$request->order_num)->first();

        if($order){
            $resource = collect(new ReceiptCompanyResource($order));
        }else{
            $order = Order::where('code',$request->order_num)->first();
            if($order){
                $resource = collect(new OrderResource($order));
            }else{
                $order = Receipt_social::where('order_num',$request->order_num)->first();
                if($order){
                    $resource = collect(new ReceiptSocialResource($order));
                }else{
                    //flash('Not Found')->error();
                    return 'not found';
                }
            }
        }
        return view('admin.playlist.photos',compact('resource'));
    }


    public function update_playlist_status(Request $request)
    {
        $order = ReceiptCompany::where('order_num',$request->order_num)->first();

        if($order){
            $code = $order->order_num;
            $route = route('receipt.company');
        }else{
            $order = Order::where('code',$request->order_num)->first();
            if($order){
                $code = $order->code;
                $route = route('admin.orders.index',$order->order_type);
            }else{
                $order = Receipt_social::where('order_num',$request->order_num)->first();
                if($order){
                    $code = $order->order_num;
                    $route = route('receipt.social',['confirm' => $order->confirm , 'receipt_type' => $order->receipt_type]);
                }else{
                    return 0;
                }
            }
        }

        $order->send_to_playlist_date = date('Y-m-d H:i:s');
        $order->playlist_status = $request->status;
        $order->save();

        $id = 0;
        $to = '';
        if($order->playlist_status == 'design'){
            $id = $request->designer_id;
            $to = 'الي الديزاينر';
        }elseif($order->playlist_status == 'manufacturing'){
            $id = $request->manifacturer_id;
            $to = 'الي التصنيع';
        }elseif($order->playlist_status == 'prepare'){
            $id = $request->preparer_id;
            $to = 'الي التجهيز';
        }elseif($order->playlist_status == 'finish'){
            $to = 'الي الشحن';
        }elseif($order->playlist_status == 'pending'){
            $to = 'الي الشركة';
        }

        if($id != 0){
            $title = $code;
            if($request->condition == 'send'){
                $body = 'فاتورة جديدة';
            }else{
                $body = 'تم أرجاع الفاتورة';
            }
            UserAlert::create([
                'alert_text' => $title . ' ' . $body,
                'alert_link' => route('playlist.index',$order->playlist_status),
                'type' => 'private',
                'user_id' => $id ,
            ]);

            $user = User::find($id);
            if($user->device_token != null){
                $tokens = array();
                array_push($tokens,$user->device_token);
                $push_controller = new PushNotificationController();
                $push_controller->sendNotification($title, $body, $tokens,route('playlist.index',$order->playlist_status));
            }
        }


        $title_2 = $code;
        if($request->condition == 'send'){
            $body_2 = 'تم تحويل الفاتورة ' . $to;
        }else{
            $body_2 = 'تم أرجاع الفاتورة '. $to;
        }
        UserAlert::create([
            'alert_text' => $title_2 . ' ' . $body_2,
            'alert_link' => $route,
            'type' => 'playlist',
            'user_id' => 0 ,
        ]);

        $tokens = User::whereNotNull('device_token')->whereIn('user_type',['staff','admin'])->where(function ($query) {
                                                        $query->where('notification_show',1)
                                                                ->orWhere('user_type','admin');
                                                    })->pluck('device_token')->all();
        $push_controller = new PushNotificationController();
        $push_controller->sendNotification($title_2, $body_2, $tokens,$route);


        // history
        UserAlert::create([
            'alert_text' => $title_2 . ' ' . $body_2,
            'alert_link' => $title_2,
            'type' => 'history',
            'user_id' => Auth::id() ,
        ]);


        return 1;
    }

}
