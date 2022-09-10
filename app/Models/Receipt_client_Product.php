<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt_client_Product extends Model
{
    protected $table = 'receipt_client_products';

    public function receipt_client(){
        return $this->belongsTo(Receipt_client::class);
    }
}
