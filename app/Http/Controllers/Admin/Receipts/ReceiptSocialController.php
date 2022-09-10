<?php

namespace App\Http\Controllers\Admin\Receipts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Receipt_social;
use App\Models\ReceiptCompany;
use App\Models\Receipt_social_Product;
use App\Models\ReceiptSocialExport;
use App\Models\ReceiptSocialDeliveryExport;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\UserAlert;
use App\Http\Controllers\PushNotificationController;
use App\Models\Country;
use App\Models\ReceiptProduct;
use App\Models\Social;
use Validator;
use DB;
use App\Http\Requests\ReceiptSocialRequest;
use App\Http\Controllers\Admin\WaslaController;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptSocialController extends Controller
{
    protected $view = 'admin.receipts.receipt_social.';

    public function print_receive_money($id){
        $receipt = Receipt_social::findOrFail($id);
        return view('admin.receipts.print_receive_money',compact('receipt'));
    }

    public function print($id){
        $receipt_social = Receipt_social::findOrFail($id);
        $receipt_social->viewed = 1;
        $receipt_social->save();
        return view($this->view . 'receipt_social',compact('receipt_social'));
    }

    public function print_new($id){
        $receipt_social = Receipt_social::findOrFail($id);
        $receipt_social->viewed = 1;
        $receipt_social->save();
        return view($this->view . 'new_receipt_social',compact('receipt_social'));
    }

    public function updateDone(Request $request)
    {
        $receipt = Receipt_social::findOrFail($request->id);
        $receipt->done = $request->status;
        if($receipt->done == 1){
            $receipt->quickly = 0;
        }
        $receipt->save();
        return 1;
    }

    public function updatequickly(Request $request)
    {
        $receipt = Receipt_social::findOrFail($request->id);
        $receipt->quickly = $request->status;
        $receipt->save();
        return 1;
    }

    public function updateconfirm(Request $request)
    {
        $receipt = Receipt_social::findOrFail($request->id);
        $receipt->confirm = $request->status;
        $receipt->save();
        return 1;
    }

    public function updatesupplied(Request $request)
    {
        $receipt = Receipt_social::where('order_num',$request->order_num)->first();
        $receipt->supplied = $request->status;
        $receipt->save();
        return 1;
    }

    public function update_delivery_status(Request $request)
    {
        $receipt = Receipt_social::where('order_num',$request->order_num)->first();
        $receipt->delivery_status = $request->status;
        if($request->status == 'delivered'){
            $receipt->done_time = strtotime(date('Y-m-d H:i:s'));
        }
        $receipt->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $receipt = Receipt_social::where('order_num',$request->order_num)->first();
        $receipt->payment_status = $request->status;
        $receipt->save();

        return 1;
    }


    public function playlist_users(Request $request){
        $raw = Receipt_social::where('order_num',$request->order_num)->first();
        $staffs = User::whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();
        $generalsetting = GeneralSetting::first();
        return view('admin.playlist.select_users',compact('raw','staffs','generalsetting'));
    }

    public function view_products(Request $request){
        $receipt_social = Receipt_social::with('receipt_social_products')->find($request->id);
        return view('admin.receipts.receipt_social.products',compact('receipt_social'));
    }

    public function update_playlist_status2(Request $request)
    {
        $receipt = Receipt_social::where('order_num',$request->order_num)->first();
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
        $receipt = Receipt_social::where('order_num',$request->order_num)->first();
        $receipt->cancel_reason = $request->cancel_reason;
        $receipt->save();
        return 1;
    }

    public function order_delay_reason(Request $request)
    {
        $receipt = Receipt_social::where('order_num',$request->order_num)->first();
        $receipt->delay_reason = $request->delay_reason;
        $receipt->save();
        return 1;
    }


    public function update_delivery_man(Request $request){
        $receipt = Receipt_social::find($request->receipt_id);
        $receipt->delivery_man = $request->delivery_man_id;
        $receipt->send_to_deliveryman_date = date('Y-m-d H:i:s');
        $receipt->delivery_status = 'on_delivery';

        $receipt->save();
        flash(__('Done !'))->success();
        return back();
    }

    public function send_to_wasla(Request $request){
        $receipt = Receipt_social::findOrFail($request->receipt_id);
        $company_id = Auth::user()->wasla_company_id;

        $receipt_products = Receipt_social_Product::where('receipt_social_id',$request->receipt_id)->orderBy('updated_at','desc')->get();

        $description = '';
        $note = '';
        foreach($receipt_products as $raw){
            $description .= $raw->title;
            $description .= ' <br> ';

            $note .= $raw->description;
            $note .= ' <br> ';
        }

        $data = [
            //from receipt
            'company_id' => $company_id,
            'receiver_name' => $receipt->client_name,
            'phone_1' => $receipt->phone,
            'phone_2' => $receipt->phone2,
            'address' => $receipt->address,
            'description' => $description,
            'note' => $note,
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
    public function trashed($receipt_type){
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);
        $receipts = Receipt_social::with(['Staff:id,email','DeliveryMan:id,email','socials'])->where('receipt_type',$receipt_type)->where('trash',1)->orderBy('created_at','desc')->get();
        return view($this->view . 'trashed',compact('receipts','generalsetting','receipt_type'));
    }
    public function index(Request $request){

        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);

        $staffs = User::whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();
        $socials = Social::all();

        $phone = null;
        $client_name = null;
        $order_num = null;
        $type = null;
        $delivery_status = null;
        $payment_status = null;
        $staff_id = null;
        $from = null;
        $to = null;
        $from_date = null;
        $to_date = null;
        $social_id = null;
        $exclude = null;
        $include = null;
        $sent_to_wasla = null;
        $calling = null;
        $quickly = null;
        $done = null;
        $country_id = null;
        $playlist_status = null;
        $description = null;

        $confirm = $request->confirm;
        $receipt_type = $request->receipt_type;
        if(!in_array($confirm,[0,1,'all']) || !in_array($receipt_type,['social','figures'])){
            flash('Not Found')->error();
            return redirect()->route('admin.dashboard');
        }

        $receipts2 = Receipt_social::where('trash',0)->where('receipt_type',$receipt_type)->orderBy('created_at','desc')->get();
        if($confirm == 'all'){
            $receipts = Receipt_social::where('trash',0)->where('receipt_type',$receipt_type)->with(['receipt_social_products','Staff:id,email','socials']);
        }else{
            $receipts = Receipt_social::where('trash',0)->where('receipt_type',$receipt_type)->where('confirm',$confirm)->with(['receipt_social_products','Staff:id,email','socials']);
        }

        if ($request->type != null){
            $receipts = $receipts->where('type',$request->type);
            $type = $request->type;
        }
        if ($request->country_id != null) {
            $country_id = $request->country_id;
            $receipts = $receipts->where('shipping_country_id',$country_id);
        }

        if ($request->sent_to_wasla != null){
            $receipts = $receipts->where('sent_to_wasla',$request->sent_to_wasla);
            $sent_to_wasla = $request->sent_to_wasla;
        }
        if ($request->social_id != null){
            $social_id = $request->social_id;
            $GLOBALS['social_id'] = $social_id;
            $receipts = $receipts->whereHas('socials',function($q){
                $q->where('id',$GLOBALS['social_id']);
            });
        }

        if ($request->done != null){
            $receipts = $receipts->where('done',$request->done);
            $done = $request->done;
        }
        if ($request->playlist_status != null){
            $receipts = $receipts->where('playlist_status',$request->playlist_status);
            $playlist_status = $request->playlist_status;
        }
        if ($request->calling != null){
            $receipts = $receipts->where('calling',$request->calling);
            $calling = $request->calling;
        }
        if ($request->quickly != null){
            $receipts = $receipts->where('quickly',$request->quickly);
            $quickly = $request->quickly;
        }

        if ($request->staff_id != null){
            $receipts = $receipts->where('staff_id',$request->staff_id);
            $staff_id = $request->staff_id;
        }

        if ($request->description != null){
            $description = $request->description;
            $receipts = $receipts->whereHas('receipt_social_products', function ($query) use ($description){
                $query->where('description', 'like', '%'.$description.'%');
            });
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
            $receipts = $receipts->where('client_name', 'like', '%'.$request->client_name.'%');
            $client_name = $request->client_name;
        }
        if ($request->order_num != null){
            $receipts = $receipts->where('order_num', 'like', '%'.$request->order_num.'%');
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
        if ($request->from_date != null && $request->to_date != null) {
            $from_date = strtotime($request->from_date);
            $to_date = strtotime($request->to_date);
            $receipts = $receipts->whereBetween('created_at',[date('Y-m-d',$from_date),date('Y-m-d',$to_date + 86400)]);
        }
        if ($request->exclude != null){
            $exclude = $request->exclude;
            $receipts = $receipts->whereNotIn('id',$exclude);
        }
        if ($request->include != null){
            $include = $request->include;
            $receipts = $receipts->whereIn('id',$include);
        }

        if($request->has('download')){
            return Excel::download(new ReceiptSocialExport($receipts->get()), 'social_receipts_('.format_Date($from_date).')_('.format_Date($to_date). ')_('. $request->client_name .').xlsx');
        }

        if($request->has('download_delivery')){
            return Excel::download(new ReceiptSocialDeliveryExport($receipts->get()), 'social_receipts_delivery_('.format_Date($from_date).')_('.format_Date($to_date). ')_('. $request->client_name .').xlsx');
        }

        if($request->has('print')){
            $receipts = $receipts->get();
            return view($this->view . 'new_receipt_social_print_more',compact('receipts'));
        }

        $total_extra_commission = $receipts->sum('extra_commission');
        $total_commission = $receipts->sum('commission');
        $total_shipping_country_cost = $receipts->sum('shipping_country_cost');
        $total_deposit = $receipts->sum('deposit');
        $total_total = $receipts->sum('total');

        $statistics = [
            'total_extra_commission' => $total_extra_commission,
            'total_commission' => $total_extra_commission + $total_commission,
            'total_shipping_country_cost' => $total_shipping_country_cost,
            'total_deposit' => $total_deposit,
            'total_total' => $total_total,
        ];

        $receipts = $receipts->orderBy('quickly','desc')->orderBy('created_at', 'desc')->paginate(15);

        return view($this->view . 'index', compact('statistics','receipts','done','type','delivery_status','payment_status','sent_to_wasla','calling','country_id',
                                                    'phone', 'client_name', 'order_num','quickly', 'playlist_status','description','receipt_type','include', 'socials',
                                                    'staff_id','from','to','from_date','to_date','generalsetting','staffs','confirm','receipts2','exclude','social_id'));
    }

    public function add(Request $request)
    {
        $countries = Country::where('type','countries')->where('status',1)->get();
        $districts = Country::where('type','districts')->where('status',1)->get();
        $metro = Country::where('type','metro')->where('status',1)->get();

        $socials = Social::all();

        $receipt_type = $request->receipt_type;
        if(!in_array($receipt_type,['social','figures'])){
            flash('Not Found')->error();
            return redirect()->route('admin.dashboard');
        }

        global $phone;
        $phone = $request->phone;
        $receipt = Receipt_social::where('phone', 'like', '%'.$phone.'%')->first();
        if(!$receipt){
            $receipt = ReceiptCompany::where(function ($query) {
                                            $query->where('phone', 'like', '%'.$GLOBALS['phone'].'%')
                                                    ->orWhere('phone2', 'like', '%'.$GLOBALS['phone'].'%');
                                        })->orderBy('created_at','desc')->first();
        }
        return view($this->view . 'add',compact('receipt','phone','countries','districts','metro','receipt_type','socials'));
    }

    public function edit($id){
        $users = User::where('user_type','delivery_man')->get();
        $countries = Country::where('type','countries')->where('status',1)->get();
        $districts = Country::where('type','districts')->where('status',1)->get();
        $metro = Country::where('type','metro')->where('status',1)->get();

        $socials = Social::all();

        $receipt = Receipt_social::find($id);
        $products = Receipt_social_Product::where('receipt_social_id',$id)->orderBy('updated_at','desc')->get();
        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);

        $waslaController = new WaslaController;
        $response = $waslaController->countries();
        return view($this->view . 'edit',compact('receipt','products','countries','districts','metro','generalsetting','response','users','socials'));
    }

    public function store(ReceiptSocialRequest $request){

        $validated_request = $request->all();
        $validated_request['receipt_type'] = $request->receipt_type;
        $validated_request['deliver_date'] = strtotime($request->deliver_date);
        $validated_request['date_of_receiving_order'] = strtotime($request->date_of_receiving_order);

        $country = Country::findOrFail($request->shipping_country);
        $validated_request['shipping_country_id'] = $country->id;
        $validated_request['shipping_country_name'] = $country->name;
        $validated_request['shipping_country_cost'] = $country->cost;

        $validated_request['staff_id'] = auth()->user()->id;

        $last_receipt_social = Receipt_social::where('receipt_type',$request->receipt_type)->latest()->first();
        if($last_receipt_social){
            $order_num = $last_receipt_social->order_num ? intval(str_replace('#','',strrchr($last_receipt_social->order_num,"#"))) : 0;
        }else{
            $order_num = 0;
        }
        $validated_request['order_num'] =  'receipt-' . $request->receipt_type . '#' . ($order_num + 1);

        $receipt_social = Receipt_social::create($validated_request);

        $receipt_social->socials()->sync($request->input('socials', []));

        flash(__('Receipt has been inserted successfully'))->success();
        return redirect()->route('receipt.social.edit',$receipt_social->id);
    }

    public function store_product(Request $request){
        $receipt0 = Receipt_social::find($request->receipt_id);

        if($request->description == null){
            flash('حقل الوصف مطلوب')->error();
            return back();
        }
        if(Auth::user()->user_type != 'admin'){
            if(!in_array($receipt0->playlist_status,['pending'])){
                flash('لايمكن أضافة منتج في هذه الفاتورة')->error();
                return redirect()->route('receipt.social',['confirm' => $receipt0->confirm , 'receipt_type' => $receipt0->receipt_type]);
            }
        }

        $product = ReceiptProduct::findOrFail($request->product_id);

        $receipt = new Receipt_social_Product;
        $receipt->receipt_social_id = $request->receipt_id;
        $receipt->title = $request->title;
        $receipt->receipt_product_id = $request->product_id;
        $receipt->description = $request->description;
        $receipt->cost = $request->cost;
        $receipt->commission = ($request->quantity *  $product->commission);
        $receipt->quantity = $request->quantity;
        $receipt->total = ($request->quantity * $request->cost);

        if($request->hasFile('pdf')){
            $receipt->pdf = $request->pdf->store('uploads/receipt_social/pdf');
        }

        $photos = array();
        $photos_note = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/receipt_social/photos');
                array_push($photos, $path);
            }
            $receipt->photos = json_encode($photos);
        }

        if($request->has('photos_note')){
            foreach ($request->photos_note as $key => $note) {
                array_push($photos_note, $note);
            }
            $receipt->photos_note = json_encode($photos_note);
        }

        $receipt->save();

        $receipt_social = Receipt_social::find($request->receipt_id);
        $receipt_products = Receipt_social_Product::where('receipt_social_id',$receipt_social->id)->get();
        $sum = 0;
        $sum2= 0;
        $sum3= 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
            $sum2 += $row->commission;
            $sum3 += $row->extra_commission;
        }
        $receipt_social->total = $sum ;
        $receipt_social->commission = $sum2 ;
        $receipt_social->extra_commission = $sum3 ;
        $receipt_social->save();

        flash(__('Product has been Added To Receipt'))->success();
        return redirect()->back();
    }

    public function edit_product($id){
        $receipt = Receipt_social_Product::find($id);

        return view($this->view . 'edit_product_of_receipt',compact('receipt'));
    }

    public function update_product(Request $request){
        $receipt = Receipt_social_Product::find($request->id);
        $receipt0 = Receipt_social::find($receipt->receipt_social_id);
        if(Auth::user()->user_type != 'admin'){
            if(!in_array($receipt0->playlist_status,['pending'])){
                flash('لايمكن تعديل منتج في هذه الفاتورة')->error();
                return redirect()->route('receipt.social',['confirm' => $receipt0->confirm , 'receipt_type' => $receipt0->receipt_type]);
            }
        }
        $receipt->title = $request->title;
        $receipt->receipt_product_id = $request->product_id;
        $receipt->description = $request->description;
        $receipt->quantity = $request->quantity;
        $receipt->cost = $request->cost;
        if($request->extra_commission != null){
            $receipt->extra_commission = $request->extra_commission;
        }
        $receipt->total = ($request->quantity * $request->cost);

        if($request->hasFile('pdf')){
            $receipt->pdf = $request->pdf->store('uploads/receipt_social/pdf');
        }

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

        $receipt->photos = json_encode($photos);


        if($request->has('photos_note')){
            foreach ($request->photos_note as $key => $note) {
                array_push($photos_note, $note);
            }

            $receipt->photos_note = json_encode($photos_note);
        }

        $receipt->save();

        $receipt_social = Receipt_social::find($receipt->receipt_social_id);
        $receipt_products = Receipt_social_Product::where('receipt_social_id',$receipt_social->id)->get();
        $sum = 0;
        $sum2= 0;
        $sum3= 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
            $sum2 += $row->commission;
            $sum3 += ($row->extra_commission * $row->quantity);
        }
        $receipt_social->total = $sum ;
        $receipt_social->commission = $sum2 ;
        $receipt_social->extra_commission = $sum3 ;
        $receipt_social->save();


        flash(__('Product has been Updated successfully'))->success();
        return redirect()->back();
    }

    public function Update(ReceiptSocialRequest $request){
        $receipt = Receipt_social::find($request->id);

        if(Auth::user()->user_type != 'admin'){
            if(!in_array($receipt->playlist_status,['pending'])){
                flash('لايمكن تعديل هذه الفاتورة')->error();
                return redirect()->route('receipt.social',['confirm' => $receipt->confirm , 'receipt_type' => $receipt->receipt_type]);
            }
        }
        $validated_request = $request->all();
        $validated_request['deliver_date'] = $request->deliver_date ? strtotime($request->deliver_date) : $receipt->deliver_date;
        $validated_request['date_of_receiving_order'] = $request->date_of_receiving_order ? strtotime($request->date_of_receiving_order) : $receipt->date_of_receiving_order;


        $country = Country::findOrFail($request->shipping_country);
        $validated_request['shipping_country_id'] = $country->id;
        $validated_request['shipping_country_name'] = $country->name;
        $validated_request['shipping_country_cost'] = $country->cost;

        $receipt->update($validated_request);
        $receipt->socials()->sync($request->input('socials', []));
        flash(__('Receipt has been Updated successfully'))->success();
        return redirect()->route('receipt.social.edit',$receipt->id);
    }

    public function destroy($id){
        $receipt = Receipt_social::findOrFail($id);
        if(Auth::user()->user_type != 'admin'){
            if(!in_array($receipt->playlist_status,['pending'])){
                flash('لايمكن حذف هذه الفاتورة')->error();
                return redirect()->route('receipt.social',['confirm' => $receipt->confirm , 'receipt_type' => $receipt->receipt_type]);
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
        return redirect()->route('receipt.social',['confirm' => $receipt->confirm , 'receipt_type' => $receipt->receipt_type]);
    }
    public function destroy_product($id){
        $receipt = Receipt_social_Product::find($id);
        $receipt_social = Receipt_social::find($receipt->receipt_social_id);
        if(Auth::user()->user_type != 'admin'){
            if(!in_array($receipt_social->playlist_status,['pending'])){
                flash('لايمكن حذف منتج من هذه الفاتورة')->error();
                return redirect()->route('receipt.social',['confirm' => $receipt->confirm , 'receipt_type' => $receipt->receipt_type]);
            }
        }

        $receipt->delete();

        $receipt_products = Receipt_social_Product::where('receipt_social_id',$receipt_social->id)->get();
        $sum = 0;
        $sum2= 0;
        $sum3= 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
            $sum2 += $row->commission;
            $sum3 += ($row->extra_commission * $row->quantity);
        }
        $receipt_social->total = $sum ;
        $receipt_social->commission = $sum2 ;
        $receipt_social->extra_commission = $sum3 ;
        $receipt_social->save();

        flash(__('Product in receipt has been deleted successfully'))->success();
        return redirect()->back();
    }
}
