<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowUser extends Model
{
    protected $table = 'borrow_users';

    public function borrow(){
        return $this->hasMany(Borrow::class,'borrow_user_id');
    }
}
