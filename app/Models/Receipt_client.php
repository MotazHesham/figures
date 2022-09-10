<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Receipt_client extends Model
{
    
    use Auditable;
    protected $table = 'receipt_clients';

    protected $fillable = [
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
        'created_at',
        'updated_at',
    ];
    public function receipt_client_products(){
        return $this->hasMany(Receipt_client_Product::class);
    }

    public function Staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    
}
