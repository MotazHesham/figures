<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedPhones extends Model
{
    public $table = 'banned_phones';

    protected $fillable = [
        'phone',
        'reason',
        'created_at',
        'updated_at',
    ];
}
