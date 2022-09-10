<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionRequestOrders extends Model
{
    protected $table = 'commission_request_orders';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
