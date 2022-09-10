<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Receipt_outgoings extends Model
{
    use Auditable;
    protected $table = 'receipt_outgoings';
    
    protected $fillable = [
        'client_name',
        'phone',
        'order_num',
        'staff_id',
        'total',
        'note',
        'done',
        'viewed',
        'date_of_receiving_order',
        'created_at',
        'updated_at',
    ];

    public function receipt_outgoings_products(){
        return $this->hasMany(Receipt_outgoings_products::class );
    }

    public function Staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
