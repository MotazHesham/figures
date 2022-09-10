<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public $table = 'attributes';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
}
