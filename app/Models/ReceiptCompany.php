<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ReceiptCompany extends Model
{
    use Auditable;
    protected $table = 'receipt_comapny';

    protected $fillable = [
        'order_num',
        'client_name',
        'phone',
        'phone2',
        'deliver_date',
        'address',
        'order_cost',
        'shipping_country_cost',
        'deposit',
        'need_to_pay',
        'description',
        'staff_id',
        'note',
        'delivery_status',
        'payment_status',
        'shipping_country_id',
        'shipping_country_name',
        'delivery_man',
        'cancel_reason',
        'delay_reason',
        'calling',
        'date_of_receiving_order',
        'viewed',
        'type',
        'quickly',
        'done',
        'trash',
        'photos',
        'created_at',
        'updated_at',
    ];

    public function Staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function DeliveryMan()
    {
        return $this->belongsTo(User::class, 'delivery_man');
    }
}
