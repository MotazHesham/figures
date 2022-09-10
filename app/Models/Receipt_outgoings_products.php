<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt_outgoings_products extends Model
{
    protected $table = 'receipt_outgoings_products';
    public function receipt_outgoings(){
        return $this->belongsTo(Receipt_outgoings::class);
    }
}
