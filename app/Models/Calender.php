<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calender extends Model
{
    protected $table = 'calender';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
