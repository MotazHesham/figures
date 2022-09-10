<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $table = 'brands';

    protected $fillable = [
        'name',
        'logo',
        'top',
        'slug',
        'meta_title',
        'meta_description',
        'created_at',
        'updated_at',
    ];
}