<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtract extends Model
{
    protected $table = 'subtracts';

    public function subtract_user(){
        return $this->belongsTo(BorrowUser::class,'subtract_user_id');
    }
}
