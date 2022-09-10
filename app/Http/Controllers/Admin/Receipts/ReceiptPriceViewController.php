<?php

namespace App\Http\Controllers\Admin\Receipts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Receipt_price_view;
use App\Models\Receipt_price_view_products;
use App\Models\GeneralSetting;
use App\Models\User;
use Validator;
use DB;
use Excel;

class ReceiptPriceViewController extends Controller
{
    protected $view = 'admin.receipts.receipt_price_view.';

    public function print($id){
		$price_view = Receipt_price_view::findOrFail($id);
		return view($this->view . 'receipt_price_view',compact('price_view'));
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
        $place = null;

        $receipts = Receipt_price_view::with(['Staff:id,email','receipt_price_view_products']);

        if ($request->place != null){
            $receipts = $receipts->where('place',$request->place);
            $place = $request->place;
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

        // if($request->has('download')){
        //     return Excel::download(new ReceiptOutgoingsExport($receipts->get()), 'outgoings_receipts_('.format_Date($from).')_('.format_Date($to). ')_('. $request->client_name .').xlsx');
        // }

        $total_total = $receipts->sum('payment');

        $statistics = [
            'total_total' => $total_total,
        ];

        $receipts = $receipts->orderBy('created_at', 'desc')->paginate(15);
        return view($this->view . 'index', compact('statistics','receipts','place',
                                                    'phone', 'client_name', 'order_num',
                                                    'staff_id','from','to','generalsetting','staffs'));

    }

    public function add()
    {

        return view($this->view . 'add');
    }

    public function edit($id){
        $receipt = Receipt_price_view::find($id);
        $products = Receipt_price_view_products::where('receipt_price_view_id',$id)->orderBy('updated_at','desc')->paginate(5);
        return view($this->view . 'edit',compact('receipt','products'));
    }


    public function store(Request $request){
        $price_view = new Receipt_price_view;
        $price_view->client_name = $request->client_name;
        $price_view->phone = $request->phone;
        $price_view->relate_duration = $request->relate_duration;
        $price_view->supply_duration = $request->supply_duration;
        $price_view->payment = $request->payment;
        $price_view->place = $request->place;
        $price_view->added_value = $request->added_value;
        $price_view->staff_id = auth()->user()->id;
        $price_view->save();

		$price_view->order_num = 'receipt-price-view#' . $price_view->id;

        $price_view->save();

        flash(__('Receipt has been inserted successfully'))->success();
        return redirect()->route('receipt.price_view.edit',$price_view->id);
    }

    public function store_product(Request $request){
        $receipt = new Receipt_price_view_products;
        $receipt->receipt_price_view_id = $request->receipt_price_view_id;
        $receipt->description = $request->description;
        $receipt->cost = $request->cost;
        $receipt->quantity = $request->quantity;
        $receipt->total = ($request->quantity * $request->cost);
        $receipt->save();

        $receipt_price_view = Receipt_price_view::find($request->receipt_price_view_id);
        $receipt_products = Receipt_price_view_products::where('receipt_price_view_id',$receipt_price_view->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_price_view->total = $sum ;
        $receipt_price_view->save();

        flash(__('Product has been Added To Receipt'))->success();
        return redirect()->back();
    }

    public function edit_product($id){
        $receipt = Receipt_price_view_products::find($id);
        return view($this->view . 'edit_product_of_receipt',compact('receipt'));
    }

    public function update_product(Request $request){
        $receipt = Receipt_price_view_products::find($request->id);
        $receipt->description = $request->description;
        $receipt->quantity = $request->quantity;
        $receipt->cost = $request->cost;
        $receipt->total = ($request->quantity * $request->cost);
        $receipt->save();

        $receipt_price_view = Receipt_price_view::find($receipt->receipt_price_view_id);
        $receipt_products = Receipt_price_view_products::where('receipt_price_view_id',$receipt_price_view->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_price_view->total = $sum ;
        $receipt_price_view->save();


        flash(__('Product has been Updated successfully'))->success();
        return redirect()->back();
    }
    public function Update(Request $request){
        $price_view = Receipt_price_view::find($request->id);
        $price_view->client_name = $request->client_name;
        $price_view->phone = $request->phone;
        $price_view->relate_duration = $request->relate_duration;
        $price_view->supply_duration = $request->supply_duration;
        $price_view->payment = $request->payment;
        $price_view->place = $request->place;
        $price_view->added_value = $request->added_value;
        $price_view->save();


        flash(__('Product has been Added To Receipt'))->success();
        return redirect()->route('receipt.price_view');
    }

    public function destroy($id){
        $receipt = Receipt_price_view::findOrFail($id);
        $receipt->delete();
        flash(__('Receipt has been deleted successfully'))->success();
        return redirect()->route('receipt.price_view');
    }
    public function destroy_product($id){
        $receipt = Receipt_price_view_products::find($id);
        $receipt->delete();

        $receipt_price_view = Receipt_price_view::find($receipt->receipt_price_view_id);
        $receipt_products = Receipt_price_view_products::where('receipt_price_view_id',$receipt_price_view->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_price_view->total = $sum ;
        $receipt_price_view->save();

        flash(__('Product in receipt has been deleted successfully'))->success();
        return redirect()->back();
    }
}
