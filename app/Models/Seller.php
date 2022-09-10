<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $table = 'sellers';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
