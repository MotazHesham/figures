<?php

namespace App\Http\Controllers\Admin\Receipts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Receipt_client;
use App\Models\Receipt_client_Product;
use App\Models\ReceiptClientExport;
use App\Models\GeneralSetting;
use App\Models\User;
use Validator;
use DB;
use Excel;
use Auth;
use App\Http\Requests\ReceiptClientRequest;

class ReceiptClientController extends Controller
{
    protected $view = 'admin.receipts.receipt_client.';


    public function print_receive_money($id){
        $receipt = Receipt_client::findOrFail($id);
        return view('admin.receipts.print_receive_money',compact('receipt'));
    }

    public function duplicate($id){

        $receipt_client = Receipt_client::findOrFail($id);
        $new_receipt = $receipt_client->replicate();
        $new_receipt->order_num = '';
        $new_receipt->staff_id = Auth::id();
        $new_receipt->save();

		$new_receipt->order_num = 'receipt-client#' . $new_receipt->id;
        $new_receipt->viewed = 0;
        $new_receipt->save();

        $receipt_products = Receipt_client_Product::where('receipt_client_id',$receipt_client->id)->get();

        foreach($receipt_products as $row){
            $new_receipt_product = $row->replicate();
            $new_receipt_product->receipt_client_id = $new_receipt->id;
            $new_receipt_product->save();
        }

        flash(__('Receipt Duplicated Successfully'))->success();
        return redirect()->route('receipt.client');

    }

    public function print($id){
        $receipt_client = Receipt_client::findOrFail($id);
        $receipt_client->viewed = 1;
        $receipt_client->save();
        return view($this->view . 'receipt_client',compact('receipt_client'));
    }


    public function updateDone(Request $request)
    {
        $receipt = Receipt_client::findOrFail($request->id);
        $receipt->done = $request->status;
        $receipt->save();
        return 1;
    }

    public function updatequickly(Request $request)
    {
        $receipt = Receipt_client::findOrFail($request->id);
        $receipt->quickly = $request->status;
        $receipt->save();
        return 1;
    }

    public function index(Request $request){

        $generalsetting = json_decode(GeneralSetting::first()->receipt_colors);

        $staffs = User::whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();

        $phone = null;
        $client_name = null;
        $order_num = null;
        $staff_id = null;
        $from = null;
        $to = null;
        $done = null;
        $quickly = null;

        $receipts = Receipt_client::with(['receipt_client_products','Staff:id,email']);

        if ($request->done != null){
            $receipts = $receipts->where('done',$request->done);
            $done = $request->done;
        }
        if ($request->quickly != null){
            $receipts = $receipts->where('quickly',$request->quickly);
            $quickly = $request->quickly;
        }
        if ($request->staff_id != null){
            $receipts = $receipts->where('staff_id',$request->staff_id);
            $staff_id = $request->staff_id;
        }
        if ($request->phone != null){
            $phone = $request->phone;
            $receipts = $receipts->where('phone', 'like', '%'.$request->phone.'%');
        }
        if ($request->client_name != null){
            $receipts = $receipts->where('client_name', 'like', '%'.$request->client_name.'%');
            $client_name = $request->client_name;
        }
        if ($request->order_num != null){
            $receipts = $receipts->where('order_num', 'like', '%'.$request->order_num.'%');
            $order_num = $request->order_num;
        }
        if ($request->from != null && $request->to != null) {
            $from = strtotime($request->from);
            $to = strtotime($request->to);
            $receipts = $receipts->whereBetween('created_at',[date('Y-m-d',$from),date('Y-m-d',$to + 86400)]);
        }

        if($request->has('download')){
            return Excel::download(new ReceiptClientExport($receipts->get()), 'client_receipts_('.format_Date($from).')_('.format_Date($to). ')_('. $request->client_name .').xlsx');
        }

        $total_discount = $receipts->sum('discount');
        $total_deposit = $receipts->sum('deposit');
        $total_total = $receipts->sum('total');

        $statistics = [
            'total_discount' => $total_discount,
            'total_deposit' => $total_deposit,
            'total_total' => $total_total,
        ];

        $receipts = $receipts->orderBy('created_at', 'desc')->paginate(15);

        return view($this->view . 'index', compact('statistics','receipts','done',
                                                    'phone', 'client_name', 'order_num','quickly',
                                                    'staff_id','from','to','generalsetting','staffs'));
    }

    public function add(Request $request)
    {
        $phone = $request->phone;
        $receipt = Receipt_client::where('phone', 'like', '%'.$phone.'%')->first();
        return view($this->view . 'add',compact('receipt','phone'));
    }

    public function edit($id){
        $receipt = Receipt_client::find($id);
        $products = Receipt_client_Product::where('receipt_client_id',$id)->orderBy('updated_at','desc')->paginate(5);
        return view($this->view . 'edit',compact('receipt','products'));
    }

    public function store(ReceiptClientRequest $request){

        $validated_request = $request->all();

        $validated_request['date_of_receiving_order'] = strtotime($request->date_of_receiving_order);
        $validated_request['staff_id'] = auth()->user()->id;

        $receipt_client = Receipt_client::create($validated_request);

		$receipt_client->order_num = 'receipt-client#' . $receipt_client->id;

        $receipt_client->save();


        flash(__('Receipt has been inserted successfully'))->success();
        return redirect()->route('receipt.client');
    }

    public function store_product(Request $request){
        $receipt = new Receipt_client_Product;
        $receipt->receipt_client_id = $request->receipt_id;
        $receipt->description = $request->description;
        $receipt->cost = $request->cost;
        $receipt->quantity = $request->quantity;
        $receipt->total = ($request->quantity * $request->cost);
        $receipt->save();

        $receipt_client = Receipt_client::find($request->receipt_id);
        $receipt_products = Receipt_client_Product::where('receipt_client_id',$receipt_client->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_client->total = $sum ;
        $receipt_client->save();

        flash(__('Product has been Added To Receipt'))->success();
        return redirect()->back();
    }

    public function edit_product($id){
        $receipt = Receipt_client_Product::find($id);
        return view($this->view . 'edit_product_of_receipt',compact('receipt'));
    }

    public function update_product(Request $request){
        $receipt = Receipt_client_Product::find($request->id);
        $receipt->description = $request->description;
        $receipt->quantity = $request->quantity;
        $receipt->cost = $request->cost;
        $receipt->total = ($request->quantity * $request->cost);
        $receipt->save();

        $receipt_client = Receipt_client::find($receipt->receipt_client_id);
        $receipt_products = Receipt_client_Product::where('receipt_client_id',$receipt_client->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_client->total = $sum ;
        $receipt_client->save();


        flash(__('Product has been Updated successfully'))->success();
        return redirect()->back();
    }

    public function Update(ReceiptClientRequest $request){
        $receipt = Receipt_client::find($request->id);
        $validated_request = $request->all();
        $validated_request['date_of_receiving_order'] = strtotime($request->date_of_receiving_order);
        $receipt->update($validated_request);
        flash(__('Receipt has been Updated successfully'))->success();
        return redirect()->route('receipt.client');
    }

    public function destroy($id){
        $receipt = Receipt_client::findOrFail($id);
        $receipt->delete();
        flash(__('Receipt has been deleted successfully'))->success();
        return redirect()->route('receipt.client');
    }
    public function destroy_product($id){
        $receipt = Receipt_client_Product::find($id);
        $receipt->delete();

        $receipt_client = Receipt_client::find($receipt->receipt_client_id);
        $receipt_products = Receipt_client_Product::where('receipt_client_id',$receipt_client->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_client->total = $sum ;
        $receipt_client->save();

        flash(__('Product in receipt has been deleted successfully'))->success();
        return redirect()->back();
    }
}
