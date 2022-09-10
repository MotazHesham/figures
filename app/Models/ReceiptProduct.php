<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ReceiptProduct extends Model
{
    use Auditable;
    protected $table = 'receipt_products';
}
