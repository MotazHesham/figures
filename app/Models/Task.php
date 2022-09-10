<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Task extends Model
{
    use Auditable;
    protected $table = 'tasks';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
