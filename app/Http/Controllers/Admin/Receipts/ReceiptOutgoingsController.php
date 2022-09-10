<?php

namespace App\Http\Controllers\Admin\Receipts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Receipt_outgoings;
use App\Models\Receipt_outgoings_products;
use App\Models\ReceiptOutgoingsExport;
use App\Models\GeneralSetting;
use App\Models\User;
use Validator;
use DB;
use Excel;
use App\Http\Requests\ReceiptOutgoingsRequest;

class ReceiptOutgoingsController extends Controller
{
    protected $view = 'admin.receipts.receipt_outgoings.';

    public function print($id){
        
		$receipt_outgoings = Receipt_outgoings::findOrFail($id);
        $receipt_outgoings->viewed = 1;
        $receipt_outgoings->save();
		return view($this->view . 'receipt_outgoings',compact('receipt_outgoings'));
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

        $receipts = Receipt_outgoings::with(['Staff:id,email','receipt_outgoings_products']); 

        if ($request->done != null){
            $receipts = $receipts->where('done',$request->done);
            $done = $request->done;
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
            return Excel::download(new ReceiptOutgoingsExport($receipts->get()), 'outgoings_receipts_('.format_Date($from).')_('.format_Date($to). ')_('. $request->client_name .').xlsx');
        }  
        
        $total_total = $receipts->sum('total');

        $statistics = [   
            'total_total' => $total_total,
        ];

        $receipts = $receipts->orderBy('created_at', 'desc')->paginate(15); 
        return view($this->view . 'index', compact('statistics','receipts','done', 
                                                    'phone', 'client_name', 'order_num', 
                                                    'staff_id','from','to','generalsetting','staffs'));

    }

    public function add()
    {

        return view($this->view . 'add');
    }

    public function edit($id){
        $receipt = Receipt_outgoings::find($id);
        $products = Receipt_outgoings_products::where('receipt_outgoings_id',$id)->orderBy('updated_at','desc')->paginate(5);
        return view($this->view . 'edit',compact('receipt','products'));
    }


    public function store(ReceiptOutgoingsRequest $request){ 
        $receipt_outgoings = new Receipt_outgoings;
        $receipt_outgoings->client_name = $request->client_name;
        $receipt_outgoings->date_of_receiving_order = strtotime($request->date_of_receiving_order);
        $receipt_outgoings->phone = $request->phone;
        $receipt_outgoings->note = $request->note;
        $receipt_outgoings->staff_id = auth()->user()->id;
        $receipt_outgoings->save();

		$receipt_outgoings->order_num = 'receipt-outgoings#' . $receipt_outgoings->id;

        $receipt_outgoings->save();

        flash(__('Receipt has been inserted successfully'))->success();
        return redirect()->route('receipt.outgoings');
    }

    public function store_product(Request $request){
        $receipt = new Receipt_outgoings_products;
        $receipt->receipt_outgoings_id = $request->receipt_outgoings_id;
        $receipt->description = $request->description;
        $receipt->cost = $request->cost;
        $receipt->quantity = $request->quantity;
        $receipt->total = ($request->quantity * $request->cost);
        $receipt->save();

        $receipt_outgoings = Receipt_outgoings::find($request->receipt_outgoings_id);
        $receipt_products = Receipt_outgoings_products::where('receipt_outgoings_id',$receipt_outgoings->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_outgoings->total = $sum ;
        $receipt_outgoings->save();

        flash(__('Product has been Added To Receipt'))->success();
        return redirect()->back();
    }

    public function edit_product($id){
        $receipt = Receipt_outgoings_products::find($id);
        return view($this->view . 'edit_product_of_receipt',compact('receipt'));
    }

    public function updateDone(Request $request)
    {
        $receipt = Receipt_outgoings::findOrFail($request->id);
        $receipt->done = $request->status;
        $receipt->save();
        return 1;
    }

    public function update_product(Request $request){
        $receipt = Receipt_outgoings_products::find($request->id);
        $receipt->description = $request->description;
        $receipt->quantity = $request->quantity;
        $receipt->cost = $request->cost;
        $receipt->total = ($request->quantity * $request->cost);
        $receipt->save();

        $receipt_outgoings = Receipt_outgoings::find($receipt->receipt_outgoings_id);
        $receipt_products = Receipt_outgoings_products::where('receipt_outgoings_id',$receipt_outgoings->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_outgoings->total = $sum ;
        $receipt_outgoings->save();


        flash(__('Product has been Updated successfully'))->success();
        return redirect()->back();
    }

    public function Update(ReceiptOutgoingsRequest $request){
        $receipt = Receipt_outgoings::find($request->id);
        $receipt->client_name = $request->client_name;
        $receipt->note = $request->note;
        $receipt->phone = $request->phone;
		if (request()->date && request('date') != '' && request('date') != $receipt->date){
            $receipt->date = strtotime($request->date);
        }
        $receipt->save();


        flash(__('Product has been Added To Receipt'))->success();
        return redirect()->route('receipt.outgoings');
    }

    public function destroy($id){
        $receipt = Receipt_outgoings::findOrFail($id);
        $receipt->delete();
        flash(__('Receipt has been deleted successfully'))->success();
        return redirect()->route('receipt.outgoings');
    }
    public function destroy_product($id){
        $receipt = Receipt_outgoings_products::find($id);
        $receipt->delete();

        $receipt_outgoings = Receipt_outgoings::find($receipt->receipt_outgoings_id);
        $receipt_products = Receipt_outgoings_products::where('receipt_outgoings_id',$receipt_outgoings->id)->get();
        $sum = 0;
        foreach($receipt_products as $row){
            $sum += $row->total;
        }
        $receipt_outgoings->total = $sum ;
        $receipt_outgoings->save();
        
        flash(__('Product in receipt has been deleted successfully'))->success();
        return redirect()->back();
    }
}
