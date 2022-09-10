<?php

namespace App\Models;

use App\Models\Receipt_outgoings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReceiptOutgoingsExport implements FromView, ShouldAutoSize
{
    protected $receipts; 

    function __construct($receipts ) {
        $this->receipts = $receipts; 
    }

    public function view(): View
    { 
        return view('excel_exports.receipt_outgoings', [
            'receipts' => $this->receipts
        ]);
    } 

}
