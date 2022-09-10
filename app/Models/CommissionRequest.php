<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionRequest extends Model
{
    protected $table = 'commission_request';

    //user_type seller
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    } 

    //user_type staff or seller or admin
    public function by_user()
    {
        return $this->belongsTo(User::class,'by_user_id');
    } 

    //user_type staff or admin
    public function done_by_user()
    {
        return $this->belongsTo(User::class,'done_by_user_id');
    } 

    public function commission_request_orders()
    {
        return $this->hasMany(CommissionRequestOrders::class,'commission_request_id');
    } 
}
