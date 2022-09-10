<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $table = 'borrow';

    public function borrow_user(){
        return $this->belongsTo(BorrowUser::class,'borrow_user_id');
    }
}
