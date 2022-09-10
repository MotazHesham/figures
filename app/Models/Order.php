<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable; 
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use Auditable;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('completed', function (Builder $builder) {
            $builder->where('completed', 1);
        });
    }

    public $table = 'orders';

    protected $fillable = [
        'user_id',
        'completed',
        'paymob_order_id',
        'shipping_address',
        'payment_status',
        'delivery_status',
        'total_cost_by_seller',
        'code',
        'viewed',
        'commission',
        'delivery_viewed',
        'shipping_country_id',
        'shipping_country_name',
        'shipping_country_cost',
        'phone_number',
        'phone_number2',
        'required_to_pay',
        'delivery_man',
        'cancel_reason',
        'delay_reason',
        'date_of_receiving_order',
        'client_name',
        'excepected_deliverd_date',
        'deposit',
        'deposit_amount',
        'free_shipping',
        'free_shipping_reason',
        'shipping_cost_by_seller',
        'calling',
        'note',
        'created_at',
        'updated_at',
    ];

    const DELIVERY_STATUS_SELECT = [ 
        'pending' => 'Pending',
        'on_review' => 'On review',
        'on_delivery' => 'On delivery',
        'delivered' => 'Delivered',
        'delay' => 'Delay',
        'cancel' => 'Cancel',
    ];

    const PAYMENT_STATUS_SELECT = [
        'paid' => 'Paid',
        'unpaid' => 'Unpaid'
    ]; 

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function DeliveryMan()
    {
        return $this->belongsTo(User::class, 'delivery_man');
    }
    

    public function refund_requests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }
}
