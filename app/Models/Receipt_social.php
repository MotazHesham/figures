<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Receipt_social extends Model
{

    use Auditable;
    protected $table = 'receipt_social';

    protected $fillable = [
        'receipt_type',
        'client_name',
        'phone',
        'order_num',
        'staff_id',
        'total',
        'note',
        'done',
        'deposit',
        'discount',
        'viewed',
        'quickly',
        'date_of_receiving_order',
        'phone2',
        'address',
        'deliver_date',
        'shipping_country_cost',
        'delivery_status',
        'payment_status',
        'shipping_country_id',
        'shipping_country_name',
        'cancel_reason',
        'delay_reason',
        'type',
        'designer_id',
        'manifacturer_id',
        'preparer_id',
        'social_id',
        'created_at',
        'updated_at',
    ];
    public function receipt_social_products(){
        return $this->hasMany(Receipt_social_Product::class);
    }

    public function Staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function DeliveryMan()
    {
        return $this->belongsTo(User::class, 'delivery_man');
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function manifacturer()
    {
        return $this->belongsTo(User::class, 'manifacturer_id');
    }

    public function preparer()
    {
        return $this->belongsTo(User::class, 'preparer_id');
    }
    public function socials()
    {
        return $this->belongsToMany(Social::class, 'social_receipt_social','receipt_social_id','social_id');
    }

}
