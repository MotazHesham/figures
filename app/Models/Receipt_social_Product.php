<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt_social_Product extends Model
{
    protected $table = 'receipt_social_products';

    public function receipt_social(){
        return $this->belongsTo(Receipt_social::class);
    }

    public function product(){
        return $this->belongsTo(ReceiptProduct::class,'receipt_product_id');
    }
}
