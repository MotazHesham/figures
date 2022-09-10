<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MockupStock extends Model
{
    protected $table = 'mockupstocks';

    public function mockup(){
        return $this->belongsTo(Mockup::class);
    }
}
