<?php

namespace App\Http\Controllers\Admin\Receipts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\ReceiptCompany;
use App\Models\Receipt_social;
use App\Models\Receipt_client;
use App\Models\BannedPhones;
use App\Models\Order;
use App\Models\ReceiptCompanyExport;
use App\Models\DeliveryManOrders;
use App\Models\GeneralSetting;
use App\Models\UserAlert;
use Auth;
use DB;
use PDF;
use Excel;
use App;
use Validator;
use Carbon\Carbon;
use App\Http\Requests\ReceiptCompanyRequest;
use App\Http\Controllers\Admin\WaslaController;
use App\Http\Controllers\PushNotificationController;
use Image;


class ReceiptCompanyController extends Controller
{

    protected $view = 'admin.receipts.receipt_company.';


    public function searchByPhone(Request $request){
        global $phone;
        $phone = $request->phone;
        $receipt_social = Receipt_social::where('receipt_type','social')->where('phone', 'like', '%'.$phone.'%')->count();
        $receipt_figures = Receipt_social::where('receipt_type','figures')->where('phone', 'like', '%'.$phone.'%')->count();
        $receipt_company = ReceiptCompany::where(function ($query) {
                                $query->where('phone', 'like', '%'.$GLOBALS['phone'].'%')
                                        ->orWhere('phone2', 'like', '%'.$GLOBALS['phone'].'%');
                            })->orderBy('created_at','desc')->count();

        $receipt_client = Receipt_client::where('phone', 'like', '%'.$phone.'%')->count();
        $customers_orders = Order::where('order_type','customer')->where(function ($query) {
                                                                        $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                                                                ->orWhere('phone_number2', 'like', '%'.$GLOBALS['phone'].'%');
                                                                    })->orderBy('created_at','desc')->count();
        $sellers_orders = Order::where('order_type','seller')->where(function ($query) {
                                                                        $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                                                                ->orWhere('phone_number2', 'like', '%'.$GLOBALS['phone'].'%');
                                                                    })->orderBy('created_at','desc')->count();

        $banned_phones = BannedPhones::where('phone',$phone)->first();
        return view('admin.receipts.search_phone',compact('receipt_social','receipt_figures','receipt_company','receipt_client','customers_orders','sellers_orders','banned_phones'));
    }
    public function updatecalling(Request $request)
    {
        $receipt = ReceiptCompany::findOrFail($request->id);
        $receipt->calling = $request->status;
        $receipt->save();
        return 1;
    }


    public function updatequickly(Request $request)
    {
        $receipt = ReceiptCompany::findOrFail($request->id);
        $receipt->quickly = $request->status;
        $receipt->save();
        return 1;
    }

    public function updateDone(Request $request)
    {
        $receipt = ReceiptCompany::findOrFail($request->id);
        $receipt->done = $request->status;
        $receipt->save();
        return 1;
    }

    public function update_no_answer(Request $request)
    {
        $receipt = ReceiptCompany::findOrFail($request->id);
        $receipt->no_answer = $request->status;
        $receipt->save();
        return 1;
    }


    public function updatesupplied(Request $request)
    {
        $receipt = ReceiptCompany::where('order_num',$request->order_num)->first();
        $receipt->supplied = $request->status;
        $receipt->save();
        return 1;
    }

    public function update_delivery_status(Request $request)
    {
        $receipt = ReceiptCompany::where('order_num',$request->order_num)->first();
        $receipt->delivery_status = $request->status;
        if($request->status == 'delivered'){
            $receipt->done_time = strtotime(date('Y-m-d H:i:s'));
        }
        $receipt->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $receipt = ReceiptCompany::where('order_num',$request->order_num)->first();
        $receipt->payment_status = $request->status;
        $receipt->save();

        return 1;
    }


    public function playlist_users(Request $request){
        $raw = ReceiptCompany::where('order_num',$request->order_num)->first();
        $staffs = User::whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();
        $generalsetting = GeneralSetting::first();
        return view('admin.playlist.select_users',compact('raw','staffs','generalsetting'));
    }

    public function update_playlist_status2(Request $request)
    {
        $receipt = ReceiptCompany::where('order_num',$request->order_num)->first();
        if($receipt->playlist_status == 'pending'){
            $receipt->send_to_playlist_date = date('Y-m-d H:i:s');
        }
        $receipt->playlist_status = $request->status;
        $receipt->designer_id = $request->designer_id;
        $receipt->manifacturer_id = $request->manifacturer_id;
        $receipt->preparer_id = $request->preparer_id;
        $receipt->save();

        $title = 'فاتورة جديدة';
        $body = $receipt->order_num;
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
        $receipt = ReceiptCompany::where('order_num',$request->order_num)->first();
        $receipt->cancel_reason = $request->cancel_reason;
        $receipt->save();
        return 1;
    }

    public function order_delay_reason(Request $request)
    {
        $receipt = ReceiptCompany::where('order_num',$request->order_num)->first();
        $receipt->delay_reason = $request->delay_reason;
        $receipt->save();
        return 1;
    }

    public function send_to_wasla(Request $request){
        $receipt = ReceiptCompany::find($request->receipt_id);
        $company_id = Auth::user()->wasla_company_id;

        $data = [
            //from receipt
            'company_id' => $company_id,
            'receiver_name' => $receipt->client_name,
            'phone_1' => $receipt->phone,
            'phone_2' => $receipt->phone2,
            'address' => $receipt->address,
            'description' => html_entity_decode(strip_tags(nl2br($receipt->description ?? '...'))),
            'note' => $receipt->note,
            'receipt_code' => $receipt->order_num,

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
                $receipt->sent_to_wasla = 1;
                $receipt->save();
                flash('تم أرسال الأوردر لواصلة بنجاح');
            }elseif($response['errNum'] == 401){
                if(gettype($response['msg']) == 'string'){
                    flash($response['msg'])->error();
                }
                if($response['msg']['company_id'] ?? null){
                    flash($response['msg']['company_id'][0])->error();
                }
                if($response['msg']['receiver_name'] ?? null){
                    flash($response['msg']['receiver_name'][0])->error();
                }
                if($response['msg']['phone_1'] ?? null){
                    flash($response['msg']['phone_1'][0])->error();
                }
                if($response['msg']['phone_2'] ?? null){
                    flash($response['msg']['phone_2'][0])->error();
                }
                if($response['msg']['district'] ?? null){
                    flash($response['msg']['district'][0])->error();
                }
                if($response['msg']['address'] ?? null){
                    flash($response['msg']['address'][0])->error();
                }
                if($response['msg']['cost'] ?? null){
                    flash($response['msg']['cost'][0])->error();
                }
                if($response['msg']['in_return_case'] ?? null){
                    flash($response['msg']['in_return_case'][0])->error();
                }
                if($response['msg']['type'] ?? null){
                    flash($response['msg']['type'][0])->error();
                }
                if($response['msg']['description'] ?? null){
                    flash($response['msg']['description'][0])->error();
                }
                if($response['msg']['note'] ?? null){
                    flash($response['msg']['note'][0])->error();
                }
                if($response['msg']['receipt_code'] ?? null){
                    flash($response['msg']['receipt_code'][0])->error();
                }
                if($response['msg']['country_id'] ?? null){
                    flash($response['msg']['country_id'][0])->error();
                }
                if($response['msg']['status'] ?? null){
                    flash($response['msg']['status'][0])->error();
                }
            }else{
                flash('SomeThing Went Wrong000')->error();
            }
        }else{
            flash('SomeThing Went Wrong')->error();
        }
        return back();
    }

    public function update_delivery_man(Request $request){
        $receipt = ReceiptCompany::find($request->receipt_id);
        $receipt->delivery_man = $request->delivery_man_id;
        $receipt->send_to_deliveryman_date = date('Y-m-d H:i:s');
        $receipt->delivery_status = 'on_delivery';

        $receipt->save();
        flash(__('Done !'))->success();
        return back();
    }

    public function print($id){

        $receipt_company = ReceiptCompany::findOrFail($id);
        $receipt_company->viewed = 1;
        $receipt_company->save();
        return view($this->view . 'receipt_company',compact('receipt_company'));

    }

    public function duplicate($id){

        $receipt_company = ReceiptCompany::findOrFail($id);
        $new_receipt = $receipt_company->replicate();
        $new_receipt->order_num = '';
        $new_receipt->staff_id = Auth::id();
        $new_receipt->save();

		$new_receipt->order_num = 'receipt-company#' . $new_receipt->id;
        $new_receipt->viewed = 0;
        $new_receipt->save();
        flash(__('Receipt Duplicated Successfully'))->success();
        return redirect()->route('receipt.company');

    }

    public function trashed(){
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);
        $receipts = ReceiptCompany::with(['Staff:id,email','DeliveryMan:id,email'])->where('trash',1)->orderBy('created_at','desc')->get();
        return view($this->view . 'trashed',compact('receipts','generalsetting'));
    }

    public function index(Request $request){
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);
        $staffs = User::whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();

        $phone = null;
        $client_name = null;
        $order_num = null;
        $type = null;
        $delivery_status = null;
        $payment_status = null;
        $staff_id = null;
        $from = null;
        $to = null;
        $exclude = null;
        $sent_to_wasla = null;
        $calling = null;
        $quickly = null;
        $no_answer = null;
        $playlist_status = null;
        $country_id = null;

        $receipts2 = ReceiptCompany::where('trash',0)->orderBy('created_at','desc')->get();

        $receipts = ReceiptCompany::with(['Staff:id,email','DeliveryMan:id,email'])->where('trash',0);

        if ($request->type != null){
            $receipts = $receipts->where('type',$request->type);
            $type = $request->type;
        }


        if ($request->country_id != null) {
            $country_id = $request->country_id;
            $receipts = $receipts->where('shipping_country_id',$country_id);
        }


        if ($request->no_answer != null){
            $receipts = $receipts->where('no_answer',$request->no_answer);
            $no_answer = $request->no_answer;
        }
        if ($request->sent_to_wasla != null){
            $receipts = $receipts->where('sent_to_wasla',$request->sent_to_wasla);
            $sent_to_wasla = $request->sent_to_wasla;
        }
        if ($request->calling != null){
            $receipts = $receipts->where('calling',$request->calling);
            $calling = $request->calling;
        }
        if ($request->quickly != null){
            $receipts = $receipts->where('quickly',$request->quickly);
            $quickly = $request->quickly;
        }
        if ($request->playlist_status != null){
            $receipts = $receipts->where('playlist_status',$request->playlist_status);
            $playlist_status = $request->playlist_status;
        }
        if ($request->staff_id != null){
            $receipts = $receipts->where('staff_id',$request->staff_id);
            $staff_id = $request->staff_id;
        }
        if ($request->phone != null){
            global $phone;
            $phone = $request->phone;
            $receipts = $receipts->where(function ($query) {
                                    $query->where('phone', 'like', '%'.$GLOBALS['phone'].'%')
                                            ->orWhere('phone2', 'like', '%'.$GLOBALS['phone'].'%');
                                });
        }
        if ($request->client_name != null){
            $receipts = $receipts
                        ->where('client_name', 'like', '%'.$request->client_name.'%');
            $client_name = $request->client_name;
        }
        if ($request->order_num != null){
            $receipts = $receipts
                        ->where('order_num', 'like', '%'.$request->order_num.'%');
            $order_num = $request->order_num;
        }
        if ($request->delivery_status != null){
            $receipts = $receipts
                        ->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->payment_status != null){
            $receipts = $receipts
                        ->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }

        if ($request->from != null && $request->to != null) {
            $from = $request->from;
            $to = $request->to;
            $receipts = $receipts->whereBetween('id',[$from,$to]);
        }
        if ($request->exclude != null){
            $exclude = $request->exclude;
            $receipts = $receipts->whereNotIn('id',$exclude);
        }
        if($request->has('download')){
            return Excel::download(new ReceiptCompanyExport($receipts->get()), 'company_receipts_('.format_Date($from).')_('.format_Date($to).').xlsx');
        }
        if($request->has('print')){
            $receipts = $receipts->get();
            return view($this->view . 'receipt_company_print_more',compact('receipts'));
        }

        $total_shipping_cost = $receipts->sum('shipping_country_cost');
        $total_order_cost = $receipts->sum('order_cost');
        $total_deposit = $receipts->sum('deposit');
        $total_need_to_pay = $receipts->sum('need_to_pay');

        $statistics = [
            'total_shipping_cost' => $total_shipping_cost,
            'total_order_cost' => $total_order_cost,
            'total_deposit' => $total_deposit,
            'total_need_to_pay' => $total_need_to_pay,
        ];
        $receipts = $receipts->orderBy('quickly','desc')->orderBy('created_at', 'desc')->paginate(15);


        return view($this->view . 'index', compact('statistics','country_id','receipts',
                                                    'phone','quickly','no_answer','calling','payment_status',
                                                    'client_name','type','order_num','delivery_status','playlist_status',
                                                    'staff_id','from','to','generalsetting','staffs','receipts2','exclude','sent_to_wasla'));
    }

    public function add(Request $request)
    {
        $countries = Country::where('type','countries')->where('status',1)->get();
        $districts = Country::where('type','districts')->where('status',1)->get();
        $metro = Country::where('type','metro')->where('status',1)->get();

        global $phone;
        $phone = $request->phone;
        $receipt = ReceiptCompany::where(function ($query) {
                                $query->where('phone', 'like', '%'.$GLOBALS['phone'].'%')
                                        ->orWhere('phone2', 'like', '%'.$GLOBALS['phone'].'%');
                            })->orderBy('created_at','desc')->first();
        if(!$receipt){
            $receipt = Receipt_social::where('phone', 'like', '%'.$phone.'%')->first();
        }
        return view($this->view . 'add',compact('countries','districts','metro','receipt','phone'));
    }

    public function edit($id){
        $receipt = ReceiptCompany::find($id);
        $users = User::where('user_type','delivery_man')->get();
        $countries = Country::where('type','countries')->where('status',1)->get();
        $districts = Country::where('type','districts')->where('status',1)->get();
        $metro = Country::where('type','metro')->where('status',1)->get();
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);

        $waslaController = new WaslaController;
        $response = $waslaController->countries();

        return view($this->view . 'edit',compact('receipt','users','countries','districts','metro','generalsetting','response'));
    }

    public function store(ReceiptCompanyRequest $request){

        $validated_request = $request->all();
        $validated_request['deliver_date'] = strtotime($request->deliver_date);
        $validated_request['date_of_receiving_order'] = strtotime($request->date_of_receiving_order);

        $country = Country::findOrFail($request->shipping_country);
        $validated_request['shipping_country_id'] = $country->id;
        $validated_request['shipping_country_name'] = $country->name;
        $validated_request['shipping_country_cost'] = $country->cost;

        $validated_request['need_to_pay'] = $request->order_cost + $country->cost - $request->deposit;
        $validated_request['staff_id'] = auth()->user()->id;

        $receipt_company = ReceiptCompany::create($validated_request);

        $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $image_name = time() . '_' . $key . rand(1,20) . '.' .$photo->getClientOriginalExtension();
                $destinationPath = public_path('uploads/receipt_social/photos/'.$image_name);
                $img = Image::make($photo->getRealPath());
                $img->resize(700, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath);
                array_push($photos, 'uploads/receipt_social/photos/'.$image_name);
            }
        }
        $receipt_company->photos = json_encode($photos);
		$receipt_company->order_num = 'receipt-company#' . $receipt_company->id;
        $receipt_company->save();

        flash(__('Receipt has been inserted successfully'))->success();
        return redirect()->route('receipt.company');
    }



    public function update(ReceiptCompanyRequest $request){
        $receipt_company = ReceiptCompany::findOrFail($request->id);
        if(Auth::user()->user_type != 'admin'){
            if(!in_array($receipt_company->playlist_status,['pending'])){
                flash('لايمكن تعديل هذه الفاتورة')->error();
                return redirect()->route('receipt.company');
            }
        }
        $validated_request = $request->all();

        $validated_request['deliver_date'] = strtotime($request->deliver_date);
        $validated_request['date_of_receiving_order'] = strtotime($request->date_of_receiving_order);


        $country = Country::findOrFail($request->shipping_country);
        $validated_request['shipping_country_id'] = $country->id;
        $validated_request['shipping_country_name'] = $country->name;
        $validated_request['shipping_country_cost'] = $country->cost;

        $validated_request['need_to_pay'] = $request->order_cost + $country->cost - $request->deposit;

        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $image_name = time() . '_' . $key . rand(1,20) . '.' . $photo->getClientOriginalExtension();
                $destinationPath = public_path('uploads/receipt_social/photos/'.$image_name);
                $img = Image::make($photo->getRealPath());
                $img->resize(700, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath);
                array_push($photos, 'uploads/receipt_social/photos/'.$image_name);
            }
        }
        $validated_request['photos'] = json_encode($photos);

        $receipt_company->update($validated_request);

        flash(__('Receipt has been Updated successfully'))->success();
        return redirect()->route('receipt.company');
    }

    public function destroy($id){
        $receipt = ReceiptCompany::findOrFail($id);
        if(Auth::user()->user_type != 'admin'){
            if(!in_array($receipt->playlist_status,['pending'])){
                flash('لايمكن حذف هذه الفاتورة')->error();
                return redirect()->route('receipt.company');
            }
        }
        if($receipt->trash == 0){
            $receipt->trash = 1;
        }else{
            $receipt->trash = 0;
        }
        $receipt->delivery_man = null;
        $receipt->save();
        flash(__('Receipt has been deleted successfully'))->success();
        return redirect()->route('receipt.company');
    }


}
