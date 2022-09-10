<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt_price_view_products extends Model
{
    protected $table = 'receipt_price_view_products';
    public function receipt_price_view(){
        return $this->belongsTo(Receipt_price_view::class);
    }
}
