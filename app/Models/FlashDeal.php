<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class FlashDeal extends Model
{
    use Auditable;
    public function flash_deal_products()
    {
        return $this->hasMany(FlashDealProduct::class);
    }
}
